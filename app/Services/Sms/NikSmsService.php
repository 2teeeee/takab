<?php

namespace App\Services\Sms;

class NikSmsService
{
    protected string $username;
    protected string $password;
    protected string $sender;
    protected int $timeout;

    // https://niksms.com/fa/publicapi/groupsms?username=-----&password=-----&numbers=-----&sendernumber=-----&message=testApi

    protected string $baseUrl = 'https://niksms.com/fa/PublicApi';

    public function __construct()
    {
        $this->username = config('sms.niksms.username');
        $this->password = config('sms.niksms.password');
        $this->sender   = config('sms.niksms.sender');
        $this->timeout  = 30;
    }

    /**
     * ارسال پیامک گروهی (یک به چند)
     */
    public function sendGroup(
        string $mobiles,
        string $message,
        array $yourMessageIds = [],
        ?string $sendOn = null,
        int $sendType = 1,
        ?string $senderNumber = null
    ): array {
        $arr = [
            'message'        => $message,
            'numbers'        => $mobiles,
            'sendernumber'   => $senderNumber ?? $this->sender,
            'sendon'         => $sendOn,
            'sendtype'       => $sendType,
        ];
        return $this->post('GroupSms', $arr);
    }

    /**
     * ارسال پیامک تکی (Wrapper)
     */
    public function sendSingle(
        string $mobile,
        string $message,
        ?int $yourMessageId = null
    ): array {
        return $this->sendGroup(
            mobiles: $mobile,
            message: $message,
            yourMessageIds: $yourMessageId ? [$yourMessageId] : []
        );
    }

    /**
     * ارسال OTP
     */
    public function sendOtp(string $mobile, string $code): array
    {
        $message = "کد تایید شما: {$code}";

        return $this->sendSingle(
            mobile: $mobile,
            message: $message
        );
    }

    /**
     * درخواست POST به API
     */
    protected function post(string $method, array $data): array
    {
        $url = "{$this->baseUrl}/{$method}";

        $postData = array_filter(array_merge([
            'username' => $this->username,
            'password' => $this->password,
        ], $data), fn($v) => $v !== null && $v !== '');
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT        => $this->timeout,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return [
                'error' => true,
                'status' => null,
                'message' => $error,
            ];
        }

        $json = json_decode($response, true);

        return [
            'error'   => ($json['Status'] ?? 0) !== 1,
            'status'  => $json['Status'] ?? null,
            'id'      => $json['Id'] ?? null,
            'nik_ids' => $json['NikIds'] ?? [],
            'message' => $json['WarningMessage'] ?? null,
            'raw'     => $json,
        ];
    }
}
