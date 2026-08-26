@extends('front.layouts.app')

@section('title', 'My Wishlist')

@section('content')

<div class="container">                
    <h5 class="h5 mb-4 mt-3">
        My Wishlist
        <span class="text-muted">- {{ $wishlists->count() }} items</span>
    </h5>
    
    @include('front.account.common.message')

    <div class="row">
        @if ($wishlists->isNotEmpty())
            @foreach ($wishlists as $wishlist)
                <div class="col-md-3 col-6">
                    <x-products :item="$wishlist" section="show_wishlist" gallery="yes" variable="wishlist" class="wishlist" :producttitle="true" :hover="true" :description="true" :amount="true" :title_limit="27" :short_limit="35"  />                            
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-md-7 mx-auto">
                    <div class="card">
                        <div class="card-body text-center p-5">
                            <h3 class="mb-2">Your Wishlist is Empty</h3>
                            <p>Add items that you like to your wishlist. <br />Review them anytime and easily move them to the bag.</p>

                            <a href="{{ route('front.home') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('customJs')
    <script>
       function removeProduct(id){            
    $.ajax({
        url: '{{ route("account.removeProductFromWishlist") }}',
        type: 'POST',
        data: {
            id: id,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(response){

            var toastEl = document.getElementById('wishlistToast');

            if(response.status === true){

                // Remove item visually
                $("#wishlist-item-" + id).fadeOut(300, function(){
                    $(this).remove();
                });

                // Update wishlist count badge (if exists)
                $(".wishlist-count").text(response.wishlistCount);

                // Success toast
                toastEl.classList.remove('bg-danger');
                toastEl.classList.add('bg-success');

            } else {

                toastEl.classList.remove('bg-success');
                toastEl.classList.add('bg-danger');
            }

            $("#wishlistToastBody").text(response.message);

            var toast = new bootstrap.Toast(toastEl);
            toast.show();

            setTimeout(function(){
                location.reload();
            }, 1000);
        }
    });
}
    </script>
@endsection
