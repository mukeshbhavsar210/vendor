@php
    $productImage = getProductImage($item->product_id)
@endphp

<div class="flex-end">
    <div class="flex">
        <div class="photo">
            @if (!empty($productImage->image))
                <img class="product" src="{{ asset('uploads/product/small/'.$productImage->image) }}" >
            @else
                <img class="product" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
            @endif 
        </div>
        <div class="product-details-name">
            <h3 class="{{ ucfirst($status) == 'Delivered' ? '' : 'mt-2' }}">{{ $item->product->title }}</h3>
            <p>{{ $item->product->short_description }}</p>

            @php
                $size = !empty($item->size_id) ? \App\Models\Size::find($item->size_id) : null;
                $color = !empty($item->color_id) ? \App\Models\Color::find($item->color_id) : null;
            @endphp

            <p class="text-muted tiny-font">
                @if(!empty($size?->name))
                    Size: <b>{{ $size->name }}</b>
                @endif                                  
            
                @if(!empty($color?->name))
                    Color: <b>{{ $color->name }}</b>                                        
                @endif  
            </p>                                    

            @if(ucfirst($status) == 'Delivered')
                <div class="exchange-text">
                    <span class="sprites tick-icon"></span>                    
                    <p class="tiny-font">Exchange/Return window closed on Sun, <b>2 Mar</b> 2025</p>
                </div>
            @endif
        </div>
    </div>
    <div class="next-arrow"> 
        <span class="sprites"></span>
    </div> 
</div> 