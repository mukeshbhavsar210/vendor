<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CustomerAddressForm extends Component {
    public $address;
    public $states;
    public $action;
    public $method;
    public $buttonText;

    public function __construct(
        $address = null,
        $states = [],
        $action = '',
        $method = 'POST',
        $buttonText = 'Save Address'
    ) {
        $this->address = $address;
        $this->states = $states;
        $this->action = $action;
        $this->method = $method;
        $this->buttonText = $buttonText;
    }

    public function render() {
        return view('components.customer-address-form');
    }
}
