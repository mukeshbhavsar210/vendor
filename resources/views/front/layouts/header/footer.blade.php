<footer>
	<div class="container">
		<a href="{{ route('front.home') }}" class="logo" >
			<img src="{{ asset('front-assets/images/logo.png') }}" alt="Business" style="width: 150px;">
		</a>

		<div class="row mt-3">
			{{-- <div class="col-md-3 col-6">			
				<h5>Online Shopping</h5>
				<ul class="footer-card">
					@if (getCategories()->isNotEmpty())
						@foreach (getCategories() as $category)
							<li>
								<a href="{{ route('front.category', [$category->category_slug]) }}" title="{{ $category->category_name }}" >
									{{ $category->category_name }}
								</a>
							</li>
						@endforeach
					@endif
				</ul>				
			</div> --}}
			<div class="col-md-3 col-6">
				<h4>Company</h4>
				<ul class="footer-card">
					@if(staticPages()->isNotEmpty())
						@foreach (staticPages() as $page)
							<li><a href="{{ route('front.page',$page->slug) }}" title="{{ $page->name }}">{{ $page->name }}</a></li>
						@endforeach
					@endif
					<li><a href="{{ route('front.faqs') }}">FAQ</a></li>
					<li><a href="{{ route('front.deals') }}">Deals</a></li>
					<li><a href="{{ route('front.deals') }}">Track Orders</a></li>
				</ul>							
			</div>
			
			<div class="col-md-3 col-6">
				<h4>For customers</h4>
				<ul class="footer-card">
					<li><a href="#">UC reviews</a></li>
					<li><a href="#">Categories near you</a></li>
					<li><a href="#">Contact us</a></li>
				</ul>
			</div>

			<div class="col-md-3 col-6">
				<h4>For professionals</h4>
				<ul class="footer-card">
					<li><a href="#">Register as a professional</a></li>					
				</ul>
			</div>

			<div class="col-md-3 col-6">
				<h4>Social links</h4>
			</div>
		</div>
			
		<div class="mt-4">
			<p>© Copyright 2022 {{ config('app.name') }}. All Rights Reserved</p>						
		</div>
	</div>

	<div id="chat-toggle" onclick="toggleChat()">
		💬
	</div>

	<div id="chat-box" class="d-none">
		<div class="chat-header">
			Order Support
			<span onclick="toggleChat()" style="cursor:pointer;">✖</span>
		</div>

		<div id="messages" class="chat-body"></div>

		<div class="chat-footer">
			<input type="text" id="chatInput" placeholder="Enter Order ID">
			<button onclick="sendMessage()">Send</button>
		</div>
	</div>
</footer>