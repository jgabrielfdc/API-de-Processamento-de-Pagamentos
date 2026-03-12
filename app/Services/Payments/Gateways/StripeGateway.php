<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class StripeGateway implements PaymentGatewayInterface
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function charge(array $data): array
    {
        $response = Http::post($this->url . '/transactions', [
            'amount' => $data['amount'],
            'name' => $data['name'],
            'email' => $data['email'],
            'cardNumber' => $data['card_number'],
            'cvv' => $data['cvv']
        ]);

        $result = $response->json();

        if ($response->failed() || isset($result['error'])) {
            throw new Exception($result['error'] ?? 'Erro no gateway 1');
        }

        return [
            'transaction_id' => $result['id'],
            'status' => 'paid',
            'gateway' => 'stripe'
        ];
    }

    public function refund(string $transactionId): array
    {
        $response = Http::post($this->url . "/transactions/{$transactionId}/charge_back");

        if ($response->failed()) {
            throw new Exception('Erro ao realizar reembolso');
        }

        return [
            'status' => 'refunded',
            'transaction_id' => $transactionId,
            'gateway' => 'stripe'
        ];
    }
}