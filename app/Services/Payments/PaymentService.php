<?php
namespace App\Services\Payments;
use App\Services\Payments\Gateways\StripeGateway;
use App\Services\Payments\Gateways\PayPalGateway;
use FFI\Exception;
class PaymentService
    {
        protected $gateways = [];

        public function __construct()
        {
            $this->gateways = [
                'stripe' => new StripeGateway(),
                'paypal' => new PayPalGateway(),
            ];
        }

        public function processPayment(string $gateway, array $data)
        {
            if (!isset($this->gateways[$gateway])) {
                throw new Exception("Gateway não suportado");
            }

            return $this->gateways[$gateway]->charge($data);
        }
    }
?>