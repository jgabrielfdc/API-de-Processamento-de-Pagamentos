<?php
namespace App\Services\Payments\Gateways;

use App\Services\Payments\Contracts\PaymentGatewayInterface;
class StripeGateway implements PaymentGatewayInterface
{
    public function charge(array $data): array
    {
        return [
            'transaction_id' => uniqid('stripe_'),
            'status' => 'paid'
        ];
    }
}
?>