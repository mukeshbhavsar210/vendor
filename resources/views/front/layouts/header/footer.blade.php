<footer>
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-12">	
				<div class="row">
					<div class="col-md-3 col-6">			
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
					</div>
					<div class="col-md-3 col-6">				
						<h5>Customer Policies</h5>
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
						<div class="popular-search">
							<h5>Popular Search</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-3 col-12">
				<div class="original-banner">
					<div class="icon"></div>
					<div class="text">
						<h6><span>100% Original</span>
							guarantee for all products at {{ config('app.name') }}
						</h6>						
					</div>
				</div>

				<div class="original-banner">
					<div class="icon"></div>
					<div class="text">
						<h6>
							<span>Returns within 7 days</span>
							of your order
						</h6>
					</div>
				</div>
			</div>
		</div>				
	</div>

	<div class="copyright-area">
		<div class="container">
			<div class="row">
				<div class="col-12 mt-3">
					<div class="copy-right text-center">
						<p>© Copyright 2022 {{ config('app.name') }}. All Rights Reserved</p>
					</div>
				</div>
			</div>
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