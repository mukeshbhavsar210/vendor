<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-10">                      
                <h4 class="card-title">Popular Products</h4>                      
            </div>
            <div class="col-2"> 
                <div class="dropdown">
                    <select onchange="loadTopProducts(this.value)" class="form-select">
                        <option value="today" selected>Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year" >This Year</option>
                    </select>

                    {{-- <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icofont-calendar fs-5 me-1"></i> This Year<i class="las la-angle-down ms-1"></i>
                    </a>
                
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Today</a>
                        <a class="dropdown-item" href="#">Last Week</a>
                        <a class="dropdown-item" href="#">Last Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div> --}}
                </div>               
            </div>
        </div>                                    
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Product ID</th>                        
                        <th class="border-top-0 text-end">Price</th>
                        <th class="border-top-0 text-end">Discount Price</th>
                        <th class="border-top-0 text-end">Stock</th>
                        <th class="border-top-0 text-end">Sold</th>
                    </tr>                
                </thead>
                <tbody>
                   @foreach($topProducts as $item)
                        @php $product = $item->product; @endphp

                        @if($product)
                            @php
                                $productImage = $product->product_images->first();
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ $product->url }}" target="_blank">
                                            <img 
                                                src="{{ !empty($productImage?->image) 
                                                    ? asset('uploads/product/small/'.$productImage->image) 
                                                    : asset('admin-assets/img/default-150x150.png') }}"
                                                height="60" class="me-3 rounded" />

                                            <span class="tooltip" style="bottom:0; left:90px;">
                                                {{ $product->category->category_name ?? '-' }} /
                                                {{ $product->subCategory->sub_category_name ?? '-' }} /
                                                {{ $product->subSubCategory->sub_sub_category_name ?? '-' }} /
                                                {{ $product->brand->name ?? '-' }}
                                            </span>
                                        </a>

                                        <div class="flex-grow-1 text-truncate"> 
                                            <h5 class="m-0">{{ $product->title }}</h5>
                                            <a href="{{ $product->url }}" target="_blank" class="fs-12 text-primary">
                                                ID: {{ $product->id }}
                                            </a>                                                                                           
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end">₹{{ round($product->price) }}</td>

                                <td class="text-end">
                                    ₹{{ $product->discount_percent > 0 
                                        ? round($product->discount_price) 
                                        : number_format($product->price, 2) }}
                                </td>

                                <td class="text-end">
                                    @if($product->qty > 0)
                                        <span class="badge bg-primary-subtle text-primary px-2">Stock</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $item->total_sold }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>                                                        
        </div>
    </div> 
</div> 