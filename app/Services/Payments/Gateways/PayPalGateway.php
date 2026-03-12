<?php

namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class PayPalGateway implements PaymentGatewayInterface
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function charge(array $data): array
    {
        $response = Http::post($this->url . '/transacoes', [
            'valor' => $data['amount'],
            'nome' => $data['name'],
            'email' => $data['email'],
            'numeroCartao' => $data['card_number'],
            'cvv' => $data['cvv']
        ]);

        $result = $response->json();

        if ($response->failed() || isset($result['error'])) {
            throw new Exception($result['error'] ?? 'Erro no gateway 2');
        }

        return [
            'transaction_id' => $result['id'],
            'status' => 'paid',
            'gateway' => 'paypal'
        ];
    }

    public function refund(string $transactionId): array
    {
        $response = Http::post($this->url . '/transacoes/reembolso', [
            'id' => $transactionId
        ]);

        if ($response->failed()) {
            throw new Exception('Erro ao realizar reembolso');
        }

        return [
            'status' => 'refunded',
            'transaction_id' => $transactionId,
            'gateway' => 'paypal'
        ];
    }
}