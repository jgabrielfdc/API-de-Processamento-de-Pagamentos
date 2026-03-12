<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentController;

Route::prefix('/payments')->group(function(){

    // Faz o pagamento
    Route::post('/',[PaymentController::class,'store']);

    // Lista pagamentos
    Route::get('/',[PaymentController::class,'index']);

    // Mostra os detalhes do pagamento
    Route::get('/{payment}',[PaymentController::class,'show']);

    // Reembolso
    Route::post('/{payment}/refund',[PaymentController::class,'refund']);
});