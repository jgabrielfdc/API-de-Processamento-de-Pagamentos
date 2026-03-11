<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Payments\PaymentService;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'gateway' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string'
        ]);

        $service = new PaymentService();

        $response = $service->processPayment(
            $data['gateway'],
            $data
        );

        $payment = Payment::create([
            'gateway' => $data['gateway'],
            'transaction_id' => $response['transaction_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => $response['status'],
            'payload' => $response
        ]);

        return response()->json($payment);
    }
}