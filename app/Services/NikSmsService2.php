<?php

namespace App\Services;

use SoapClient;
use Exception;

class NikSmsService2
{
    private SoapClient $client;
    private array $auth;

    public function __construct()
    {
        // نسخه بدون WSDL
        $this->client = new SoapClient(null, [
            'location'   => 'http://94.182.154.28:1370/NiksmsWebservice.svc',
            'uri'        => 'http://tempuri.org/',
            'trace'      => true,
            'exceptions' => true,
        ]);

        $this->auth = [
            'Username' => config('sms.niksms.username'),
            'Password' => config('sms.niksms.password'),
        ];
    }

    public function sendGroupSms(array $numbers, string $message)
    {
        $params = [
            'authentication' => $this->auth,
            'groupSmsModel' => [
                'SendOn'        => now()->format('Y/m/d-H:i'),
                'SendType'      => 1,
                'SenderNumber'  => config('sms.niksms.sender'),
                'Numbers'       => $numbers,
                'YourMessageId' => array_fill(0, count($numbers), time()),
                'Message'       => $message,
            ],
        ];

        try {
            $response = $this->client->__soapCall('GroupSms', [$params]);

            return $response->GroupSmsResult ?? $response;

        } catch (Exception $e) {
            return [
                'error' => true,
                'msg' => $e->getMessage(),
            ];
        }
    }
}




