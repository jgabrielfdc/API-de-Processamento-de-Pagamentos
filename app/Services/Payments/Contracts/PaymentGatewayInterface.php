<?php

namespace App\Services\Payments\Contracts;

interface PaymentGatewayInterface
{
    public function charge(array $data): array;

}
