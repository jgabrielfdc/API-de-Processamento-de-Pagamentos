<?php
namespace App\Services\Payments;
use App\Services\Payments\Gateways\StripeGateway;
use App\Services\Payments\Gateways\PayPalGateway;
use Exception;
class PaymentService
    {
        protected $gateways = [];

        public function __construct(){
            $this->gateways = [
                'stripe' => new StripeGateway(config('gateways.stripe.url')),
                'paypal' => new PayPalGateway(config('gateways.paypal.url')),
            ];
        }

        public function processPayment(array $data){
            foreach ($this->gateways as $name => $gateway) {

                try {

                    $response = $gateway->charge($data);

                    if (($response['status'] ?? null) === 'paid') {

                        $response['gateway'] = $name;

                        return $response;
                    }

                } catch (Exception $e) {

                    continue;

                }

            }

            throw new Exception('Todos os gateways falharam');
        }

        public function refundPayment($payment){
            if (!isset($this->gateways[$payment->gateway])) {
                throw new \Exception("Gateway não suportado");
            }

            $gateway = $this->gateways[$payment->gateway];

            return $gateway->refund($payment->transaction_id);
        }

}
