<div class="modal fade" id="ratingsModal_{{ $product->id }}" tabindex="-1" aria-labelledby="ratingsModal_{{ $product->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="ratingsModal__{{ $product->id }}Label">Write Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    @php
                        $userReview = $userReviews[$product->id] ?? null;
                        $productImage = optional($product->images->first())->image;
                    @endphp

                    <div class="review-out">
                        <div class="img">
                            <img src="{{ $productImage 
                                ? asset('uploads/product/small/'.$productImage) 
                                : asset('admin-assets/img/default-150x150.png') }}" class="rounded" />
                        </div>
                        
                        <div class="details">
                            <h5>{{ Str::limit($product->title, 60) }}</h5>
                            <p class="text-muted tiny-font">{{ Str::limit($product->short_description, 80) }}</p>

                            @if(!$userReview)
                                <div class="d-flex">                                                                                        
                                    <div class="modal-rating mt-2" data-product="{{ $product->id }}">
                                        @for ($i = 1; $i <= 5; $i++)                                                            
                                            <i class="star" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating[{{ $product->id }}]" class="rating-value">                                                    
                                </div>
                            @else                                                                                                    
                                <div class="mt-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="star {{ $i <= $userReview->rating ? 'active' : '' }}"></i>                                                        
                                    @endfor
                                </div>                                                
                            @endif

                            <div class="already-comments">
                                @if(!$userReview)
                                    <textarea name="review[{{ $product->id }}]" class="form-control mt-2" cols="4" rows="4"
                                        placeholder="Write your Product review..."></textarea>
                                @else                                            
                                    <p><b>Your comments:</b></p>
                                    <p class="text-muted tiny-font" >{{ $userReview->review }}</p>                                            
                                @endif
                            </div>    

                            <button type="submit" class="btn btn-primary mt-2 mb-3">Submit</button>
                        </div>
                    </div>
                </div>                                                                                
            </form>
        </div>
    </div>
</div>


@section('customJs')
<script>
    $(document).ready(function(){   
        $(document).on('click', '.open-modal', function () {
            let value = $(this).data('value');
            let product_id = $(this).data('product');
            let modal = $('#ratingsModal_' + product_id);
            let stars = modal.find('.modal-rating .star');

            modal.find('.rating-value').val(value);
        });

        // ⭐ HOVER EFFECT
        $(document).on('mouseenter', '.star', function () {
            let value = $(this).data('value');
            let parent = $(this).closest('.rating, .modal-rating');

            parent.find('.star').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= value);
            });
        });

        // ⭐ REMOVE HOVER (restore selected)
        $(document).on('mouseleave', '.rating, .modal-rating', function () {
            let parent = $(this);
            let selected = parent.data('selected') || 0;
            parent.find('.star').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= selected);
            });
        });


        // ⭐ CLICK (final selection)
        $(document).on('click', '.star', function () {
            let value = $(this).data('value');
            let parent = $(this).closest('.rating, .modal-rating');
            let product_id = parent.data('product');

            // store selected value
            parent.data('selected', value);

            // update UI
            parent.find('.star').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= value);
            });

            // update hidden input
            $('input[name="rating[' + product_id + ']"]').val(value);
        });
    });
</script>
@endsection