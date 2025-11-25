<?php

namespace App\Services;

use Exception;

class ZarinpalService
{
    protected string $merchantId;

    public function __construct()
    {
        $this->merchantId = config('services.zarinpal.merchant_id');
    }

    /**
     * ایجاد درخواست پرداخت
     *
     * @param int $amount مبلغ به ریال
     * @param string $callbackUrl
     * @param string|null $description
     * @param array $metadata (mobile, email, order_id)
     * @return array ['authority' => string, 'payment_url' => string]
     * @throws Exception
     */
    public function requestPayment(int $amount, string $callbackUrl, ?string $description = null, array $metadata = []): array
    {
        $data = [
            'merchant_id' => $this->merchantId,
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'description' => $description ?? 'پرداخت آنلاین',
            'metadata' => $metadata,
        ];

        $ch = curl_init('https://payment.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('خطا در اتصال به درگاه پرداخت.');
        }

        $result = json_decode($response, true);

        if (!isset($result['data']['code']) || $result['data']['code'] != 100) {
            $message = $result['errors'][0]['message'] ?? $result['errors'];
            throw new Exception($message);
        }

        $authority = $result['data']['authority'];
        $paymentUrl = "https://www.zarinpal.com/pg/StartPay/{$authority}";

        return ['authority' => $authority, 'payment_url' => $paymentUrl];
    }

    /**
     * تایید پرداخت
     *
     * @param int $amount مبلغ تراکنش به ریال
     * @param string $authority
     * @return array ['ref_id' => string, 'card_hash' => string, 'card_pan' => string]
     * @throws Exception
     */
    public function verifyPayment(int $amount, string $authority): array
    {
        $data = [
            'merchant_id' => $this->merchantId,
            'amount' => $amount,
            'authority' => $authority,
        ];

        $ch = curl_init('https://payment.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('خطا در اتصال به درگاه پرداخت.');
        }

        $result = json_decode($response, true);

        if (!isset($result['data']['code']) || !in_array($result['data']['code'], [100, 101])) {
            $message = $result['errors'][0]['message'] ?? 'پرداخت ناموفق';
            throw new Exception($message);
        }

        return [
            'ref_id' => $result['data']['ref_id'],
            'card_hash' => $result['data']['card_hash'],
            'card_pan' => $result['data']['card_pan'],
        ];
    }
}
