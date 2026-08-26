<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller {
    public function index(){
        return view('razorpay');
    }

    public function payment(Request $request){
        $api = new Api(config('razorpay.key'), config('razorpay.secret'));
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        if($payment){
            $response = $payment->capture([
                'amount' => $payment['amount']
            ]);

            return redirect()->back()->with('success','Payment Successful');
        }
   }
}