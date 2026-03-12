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
            'amount' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'card_number' => 'required|string|size:16',
            'cvv' => 'required|string|size:3'
        ]);

        $response = $this->paymentService->processPayment($data);

        $payment = Payment::create([
            'gateway' => $response['gateway'],
            'transaction_id' => $response['transaction_id'],
            'amount' => $data['amount'],
            'status' => $response['status'],
            'payload' => $response
        ]);

        return response()->json($payment);
    }

    public function index()
    {
        // Retorna todos os pagamento presentes no bd
        return response()->json(Payment::all());
    }

    public function show(Payment $payment)
    {
        // Retorna somente o pagamento especificado
        return response()->json($payment);
    }

    public function refund(Payment $payment)
    {
        $service = new PaymentService();

        $response = $service->refundPayment($payment);

        $payment->update([
            'status' => 'refunded'
        ]);

        return response()->json($response);
    }
}
