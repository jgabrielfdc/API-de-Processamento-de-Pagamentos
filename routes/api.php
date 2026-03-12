<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentController;

Route::prefix('/payments')->group(function(){

    Route::middleware('throttle:10,1')->group(function(){
        // Faz o pagamento
        Route::post('/',[PaymentController::class,'store']);
        // Reembolso
        Route::post('/{payment}/refund',[PaymentController::class,'refund']);
    });
    
    // Lista pagamentos
    Route::get('/',[PaymentController::class,'index']);

    // Mostra os detalhes do pagamento
    Route::get('/{payment}',[PaymentController::class,'show']);

    
});