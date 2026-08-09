<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class N8nService
{
    /**
     * Send product data to n8n AI workflow.
     */
    public function generateProductContent(array $data): array
    {
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-N8N-SECRET' => config('services.n8n.webhook_secret'),
                ])
                ->post(
                    config('services.n8n.product_ai_webhook_url'),
                    $data
                );

            $response->throw();

            return $response->json();

        } catch (ConnectionException $e) {
            throw new \RuntimeException(
                'ارتباط با سرویس AI برقرار نشد.'
            );
        }
    }
}