<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentService;
use App\Models\Payment;

class PaymentController extends Controller
{

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'card_number' => 'required|digits:16',
            'cvv' => 'required|digits_between:3,4'
        ]);

        $response = $this->paymentService->processPayment($data);

        $payment = Payment::create([
            'gateway' => $response['gateway'],
            'transaction_id' => $response['transaction_id'],
            'amount' => $data['amount'],
            'status' => $response['status'],
            'payload' => [
                'gateway' => $response['gateway'],
                'transaction_id' => $response['transaction_id'],
                'status' => $response['status']
            ]
        ]);

        return response()->json($payment);
    }

    public function index()
    {
        return response()->json(Payment::all());
    }

    public function show(Payment $payment)
    {
        return response()->json($payment);
    }

    public function refund(Payment $payment)
    {
        $response = $this->paymentService->refundPayment($payment);

        $payment->update([
            'status' => 'refunded'
        ]);

        return response()->json($response);
    }
}