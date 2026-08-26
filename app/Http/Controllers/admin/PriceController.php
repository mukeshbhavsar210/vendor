<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceController extends Controller {
    public function calculatePrice(Request $request)
    {
        $text = $request->input('text', '');
        $pricePerCharacter = 3650; // Example: 2 Rupees per character
        $totalPrice = mb_strlen($text) * $pricePerCharacter; // Count characters, including special ones

        return response()->json(['price' => $totalPrice]);
    }
}
