<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OrderSummary extends Component
{
    public $shippingCharge;
    public $couponDiscount;
    public $couponCode;
    public $buttonType;

    public function __construct($shippingCharge = 0, $couponDiscount = 0, $couponCode = null, $buttonType = 'checkout') {
        $this->shippingCharge = $shippingCharge;
        $this->couponDiscount = $couponDiscount;
        $this->couponCode = $couponCode;
        $this->buttonType = $buttonType;
    }

    public function render() {
        return view('components.order-summary');
    }
}