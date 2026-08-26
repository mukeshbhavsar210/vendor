@extends('front.layouts.app')

@section('title', 'My Dashboard')

@section('content')

<div class="container">    
            @include('front.account.common.sidebar')  
                <div class="col-md-9 col-12 px-md-0">
                    @include('front.account.common.message')

                    <div class="orders-details">
                        <ul class="nav nav-tabs" id="notifyTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#productTab" type="button">
                                    Products
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="affiliate-tab" data-bs-toggle="tab" data-bs-target="#affiliateTab" type="button">
                                    Affiliate Deals
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="notifyTabsContent">
                            <div class="tab-pane fade show active" id="productTab" role="tabpanel">
                                <div class="row">
                                    @forelse ($notifications as $notify)
                                        <div class="col-md-4 col-6">
                                            <x-products 
                                                :item="$notify->product" 
                                                :notifyData="$notify"
                                                section="show_notify"
                                                gallery="yes"
                                                variable="notify"
                                                class="notify"
                                                :producttitle="true"
                                                :hover="true"
                                                :description="true"
                                                :amount="true"
                                                :title_limit="27"
                                                :short_limit="35"
                                            />
                                        </div>
                                    @empty
                                        <p>No product notifications</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="affiliateTab" role="tabpanel">
                                <div class="row">
                                    @forelse ($affiliate_notifications as $affiliate)
                                        <div class="col-md-4 col-6">
                                            <x-products 
                                                :item="$affiliate->product" 
                                                :notifyData="$affiliate"
                                                section="show_affiliate"
                                                gallery="affiliate_image"
                                                variable="affiliate"
                                                class="notify"
                                                :producttitle="true"
                                                :hover="true"
                                                :description="true"
                                                :amount="true"
                                                :title_limit="20"
                                            />
                                        </div>
                                    @empty
                                        <p>No affiliate notifications</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>            
@endsection

@section('customJs')

@endsection