<header class="cart-header">
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-expand-lg">							
                <div class="col-md-2 col-4">
                    <div class="flex">                        
                        <a href="{{ url()->previous() }}" class="navbar-toggler mobile-back-icon">
                            <span class="sprites"></span>
                        </a>
                        <a href="{{ route('front.home') }}" class="logo" >
                            <img src="{{ asset('front-assets/images/logo.png') }}" alt="Business">
                        </a>
                    </div>
                </div>
            </nav>
        </div>							
    </div>    
</header>

