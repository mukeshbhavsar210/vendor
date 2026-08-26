<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductCard extends Component {
    public $product;    
    public $item1;
    public $item2;
    public $item3;
    public $selected_item1;
    public $selected_item2;
    public $selected_item3;
    public $category;
    public $subcategory;
    public $wishlist;
    public $brand;

    public function __construct(
            $product=null, 
            $selected_item1=null, 
            $selected_item2=null, 
            $selected_item3=null, 
            $item1=null, 
            $item2=null, 
            $item3=null, 
            $category=null, 
            $subcategory=null, 
            $wishlist=null,
            $brand=null
        ) {
            $this->product = $product;
            $this->item1 = $item1;
            $this->item2 = $item2;
            $this->item3 = $item3;        
            $this->selected_item1 = $selected_item1;
            $this->selected_item2 = $selected_item2;
            $this->selected_item3 = $selected_item3;        
            $this->category = $category;
            $this->subcategory = $subcategory;
            $this->wishlist = $wishlist;
            $this->brand = $brand;
        }    

    public function render() {
        return view('components.products');
    }
}