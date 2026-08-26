<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilterCheckbox extends Component {
    public $items;
    public $type;
    public $valueField;
    public $labelField;
    public $title;
    public $showColor;

    public function __construct($items, $type, $valueField = 'name', $labelField = 'name', $title = '', $showColor = false) {
        $this->items = $items;        
        $this->type = $type;
        $this->valueField = $valueField;
        $this->labelField = $labelField;
        $this->title = $title;
        $this->showColor = $showColor;
    }

    public function render() {
        return view('components.filters');
    }
}
