    
        <div class="row">
            <nav class="navbar navbar-expand-lg">							
                <div class="col-md-11 col-4">
                    <div class="flex">                        
                        <a href="{{ url()->previous() }}" class="navbar-toggler mobile-back-icon">
                            <span class="sprites"></span>
                        </a>
                        <a href="{{ route('front.home') }}" class="logo" >
                            <img src="{{ asset('front-assets/images/logo.png') }}" alt="Business">
                        </a>
                    </div>
                </div>

                <div class="col-md-1 col-4">
                    <ul class="icon-controls mt-2">                                                 
                        <li class="item d-none d-md-block">
                            @if (Auth::check())
                                <a href="{{ route('account.profile') }}" class="link user-link">
                                    @if (!empty(Auth::user()->image))                        
                                        <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" class="profile-pic">
                                    @else                            
                                        @php
                                            $name = Auth::user()->name;
                                            $words = explode(' ', $name);
                                            $initials = '';
                                            foreach ($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }
                                        @endphp
                                        <div class="avatar" style="background-color: {{ Auth::user()->avatar_color ?? '#777' }};">
                                            {{ $initials }}
                                        </div>                                            
                                    @endif                                                                              
                                </a>                                
                            @endif                                                                
                        </li>
                    </ul>
                </div>                
            </nav>
        </div>							
    