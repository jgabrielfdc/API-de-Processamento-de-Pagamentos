<?php
namespace App\Services\Payments\Gateways;
use App\Services\Payments\Contracts\PaymentGatewayInterface;
    class PayPalGateway implements PaymentGatewayInterface
    {
        public function charge(array $data): array
        {
            return [
                'transaction_id' => uniqid('paypal_'),
                'status' => 'paid'
            ];
        }
    }
?>