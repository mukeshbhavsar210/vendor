<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class ShiprocketService {

    protected $baseUrl;
    protected $token;

    public function __construct() {
        $this->baseUrl = config('services.shiprocket.api_url');
        $this->token = $this->authenticate();
    }

    protected function authenticate() {
        $response = Http::post("{$this->baseUrl}/auth/login", [
            'email' => config('services.shiprocket.email'),
            'password' => config('services.shiprocket.password'),
        ]);

        return $response['token'] ?? null;
    }

    public function createOrder($orderData) {
        return Http::withToken($this->token)->post("{$this->baseUrl}/orders/create/adhoc", $orderData);
    }

    public function getShippingRates($params) {
        return Http::withToken($this->token)->get("{$this->baseUrl}/courier/serviceability/", $params);
    }


    // public static function getToken() {
    //     $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
    //         'email' => env('SHIPROCKET_EMAIL'),
    //         'password' => env('SHIPROCKET_PASSWORD'),
    //     ]);

    //     return $response['token'];
    // }




    // public static function createOrder($order) {
    //     $token = self::getToken();

    //     $user = $order->user;
    //     $shipping = $order->customerAddress;
    //     $product = $order->product;

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
    //         "order_id" => $order->id,
    //         "order_date" => now()->toDateString(),
    //         "pickup_location" => "Primary", // from Shiprocket panel
    //         "billing_customer_name" => $user->first_name,
    //         "billing_address" => $shipping->address,
    //         "billing_city" => $shipping->city,
    //         "billing_pincode" => $shipping->zip,
    //         "billing_state" => $shipping->country_id,
    //         "billing_country" => "India",
    //         "billing_email" => $user->email,
    //         "billing_phone" => $user->phone,
    //         "order_items" => [
    //             [
    //                 "name" => $product->name,
    //                 "sku" => $order->subtotal,
    //                 "units" => 1,
    //                 "selling_price" => $order->grandtotal,
    //             ]
    //         ],
    //         "payment_method" => 'Prepaid', //$order->payment_method, // 'Prepaid' or 'COD'
    //         "shipping_charges" => 0,
    //         "giftwrap_charges" => 0,
    //         "transaction_charges" => 0,
    //         "total_discount" => 0,
    //         "sub_total" => $order->price,
    //         "length" => 10,
    //         "breadth" => 15,
    //         "height" => 20,
    //         "weight" => 0.5,
    //     ]);

    //     return $response->json();
    // }



    // public static function trackOrder($awbCode) {
    //     $token = self::getToken();

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get("https://apiv2.shiprocket.in/v1/external/courier/track/awb/{$awbCode}");

    //     return $response->json();
    // }
}

?>