<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">                      
                <h4 class="card-title">Categories Data</h4>                      
            </div>
            
        </div>                                    
    </div>
    <div class="card-body pt-0">
        @php
            $max = $categories->max('products_count') ?: 1;
        @endphp

        @foreach($categories as $category)
            @php
                $ratio = $category->products_count / $max;
                $width = $ratio * 100;
                $minWidth = 18;
                $width = max($width, $minWidth);
                $alpha = 0.1 + ($ratio * 0.4); // 0.1 → 0.5
            @endphp

            <div class="category-row">
                <div class="bar-container">
                    <div class="bar show-tooltip" style="width: {{ $width }}%; background: rgba(40,167,69, {{ $alpha }});" >
                        {{ $category->category_name }} - {{ $category->products_count }}
                    </div>
                </div>
            </div>
        @endforeach
    </div> 
</div> 