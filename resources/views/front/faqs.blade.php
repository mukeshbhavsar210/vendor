@extends('front.layouts.app')

@section('title', 'FAQS')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-12 mx-auto">
            <h2>Frequently Asked Questions</h2>

            <hr />

            <div class="row mt-4">
                <div class="col-md-3 col-12">
                    <ul id="faq-nav" class="nav flex-column position-sticky" style="top: 20px;">
                        <li><a class=" active" href="#faq1">Top Queries</a></li>
                        <li><a class="" href="#faq2">Terms and Conditions</a></li>
                        <li><a class="" href="#faq3">Shipping, Order, Tracking & Delivery</a></li>
                        <li><a class="" href="#faq4">Cancellations & Modifications</a></li>
                        <li><a class="" href="#faq5">Return & Exchange</a></li>
                        <li><a class="" href="#faq6">Sign Up & Login</a></li>
                        <li><a class="" href="#faq7">Payments</a></li>
                        <li><a class="" href="#faq8">Coupons and "My Cashback"</a></li>
                    </ul>
                </div>

                <div class="col-md-9 col-12">
                    <div data-bs-spy="scroll" data-bs-target="#faq-nav" data-bs-offset="0" class="scrollspy-example" tabindex="0" >
                        <div id="faq1" class="faq-section">
                            <h4>Top Queries</h4>
                            <p class="mt-3">You can track your orders in 'My Orders.'</p>
                            <hr />
                            
                            <div class="accordion-faqs" id="accordionExample">
                                <div class="accordion-item">
                                    <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faqs-1" aria-expanded="true" aria-controls="collapseOne">
                                        Why are there different prices for the same product? Is it legal? 
                                    </h6>
                                    
                                    <div id="faqs-1" class="accordion-collapse collapse show" aria-labelledby="faqs-1" data-bs-parent="#accordionExample">
                                        {{ config('app.name') }} is an online marketplace platform that enables independent sellers to sell their products to buyers. The prices are solely decided by the sellers, and Myntra does not interfere in the same. There could be a possibility that the same product is sold by different sellers at different prices. Myntra rightfully fulfils all legal compliances of onboarding multiple sellers on its forum as it is a marketplace platform.
                                    </div>
                                </div>

                                <div class="accordion-item">                                
                                    <h5 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faqs-2" aria-expanded="false" aria-controls="collapseTwo">
                                         How can I contact any seller? 
                                    </h5>
                                
                                    <div id="faqs-2" class="accordion-collapse collapse" aria-labelledby="faqs-2" data-bs-parent="#accordionExample">
                                        <p>{{ config('app.name') }} is a marketplace on which third-party sellers sell products to customers. To contact a seller or raise any grievance against them, please send a letter with the below address on the envelope and include product page URL so that it can be forwarded to the seller.</p>
                                        <p>To,<br />
                                        'Include Seller's name'<br />
                                        Seller Mailbox: Contact Seller<br />
                                        C/O {{ config('app.name') }}<br />
                                        Buildings Alyssa, Begonia and Clover situated in Embassy Tech Village,<br />
                                        Outer Ring Road, Devarabeesanahalli Village, Varthur Hobli,<br />
                                        Bengaluru – 560103, India<br />
                                        Telephone: +91-80-61561999</p>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h5 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faqs-3" aria-expanded="false" aria-controls="collapseThree">
                                         I saw the product at Rs. 1000 but post clicking on the product, there are multiple prices and the size which I want is being sold for Rs. 1600. Why is there a change in price in the product description page? 
                                    </h5>

                                    <div id="faqs-3" class="accordion-collapse collapse" aria-labelledby="faqs-3" data-bs-parent="#accordionExample">
                                        {{ config('app.name') }} is an online marketplace, and multiple sellers could be selling a particular style at different prices as may be set by each such seller respectively. The product price on the listing page of the platform, may not always reflect the lowest price for that particular style. This is because the seller whose price is displayed on the list page is selected based on the application of a number of parameters and price is only one such parameter. However, once you land on the product display page on the platform for a specific style, you will have access to the price offered by all sellers on the platform for the relevant style.
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h5 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faqs-4" aria-expanded="false" aria-controls="collapseThree">
                                         How will I detect fraudulent emails/calls seeking sensitive personal and confidential information?
                                    </h5>

                                    <div id="faqs-4" class="accordion-collapse collapse" aria-labelledby="faqs-4" data-bs-parent="#accordionExample">
                                        <p>If you receive an e-mail, a call from a person/association claiming to be from Myntra seeking sensitive confidential information like debit/credit card PIN, net-banking or mobile banking password, we request you to never provide such confidential and personal data. We at Myntra or our affiliate logistics partner never ask for such confidential and personal data. If you have already revealed such information, report it immediately to an appropriate law enforcement agency.</p>
                                        <p>Here are a couple of baits fraudsters often use to cheat consumers:</p>
                                        <p>Congratulations! You have been nominated as a ‘Top Myntra customer’ and are now eligible for a luxury gift item. Please share your proof of address and your debit/credit card details to avail this great offer.</p>
                                        <p>Hi, I’m calling from Myntra. We are happy to let you know that you have won an exclusive lucky draw coupon of Rs. 5000 on your latest purchase. Please share your credit/debit card number so we can credit the money directly into your bank account.</p>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h5 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faqs-5" aria-expanded="false" aria-controls="collapseThree">
                                         How do I cancel the order, I have placed?
                                    </h5>
                                    <div id="faqs-5" class="accordion-collapse collapse" aria-labelledby="faqs-5" data-bs-parent="#accordionExample">
                                        <p>Order can be canceled till the same is out for delivery. Note: This may not be applicable for certain logistics partner. You would see an option to cancel within 'My Orders' section under the main menu of your App/Website/M-site then select the item or order you want to cancel. In case you are unable to cancel the order from'My Orders' section, you can refuse it at the time of delivery and refund will be processed into the source account, if order amount was paid online. </p>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h5 class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faqs-6" aria-expanded="false" aria-controls="collapseThree">
                                         How do I create a Return Request?
                                    </h5>
                                    <div id="faqs-6" class="accordion-collapse collapse" aria-labelledby="faqs-6" data-bs-parent="#accordionExample">
                                        <p>You can create a Return in three simple steps</p>
                                        <ol class="mt-3">
                                            <li>Tap on MyOrders</li>
                                            <li>Choose the item to be Returned</li>
                                            <li>Enter details requested and create a return request</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="faq2" class="faq-section d-none">
                            <h4>Terms and Conditions</h4>   
                            
                            <div class="accordion-faqs" >
                                <div class="accordion-item">
                                    <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faqs-1">
                                        Why are there different prices for the same product? Is it legal? 
                                    </h6>
                                    
                                    <div id="faqs-1" class="accordion-collapse collapse">
                                        <p>{{ config('app.name') }} Social Carnival is a social-commerce event to be held on 11th Nov, ‘21 exclusively on Myntra mobile app (“Platform”), in which fashion and beauty content creators will feature their looks and chosen products on Myntra Studio (“Studio”) and Myntra-Live (“M-Live”). Along with this, Myntra Fashion Superstar (“MFS”) Season 3 would also premiere on Studio on the same day.</p>
                                        <p>Customers can share their feedback by writing to support_socialcarnival@myntra.com </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="faq3" class="faq-section d-none">
                            <h4>Shipping, Order, Tracking & Delivery</h4> 
                            <p>You can track your orders in MyMyntra</p>
                            <hr />
                            
                            <div class="accordion-faqs" >
                                <div class="accordion-item">
                                    <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faqs-1" aria-expanded="true" >
                                        What is {{ config('app.name') }}'s Platform Fee?
                                    </h6>
                                    
                                    <div id="faqs-1" class="accordion-collapse collapse" >
                                        <p>Platform fee is levied by {{ config('app.name') }} to sustain the efficient operations and continuous improvement of the platform, for a hassle-free app experience.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-faqs">
                                <div class="accordion-item">
                                    <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faqs-2" aria-expanded="true">
                                        How do I check the status of my order?
                                    </h6>
                                    
                                    <div id="faqs-2" class="accordion-collapse collapse" >
                                        <p>Please tap on “My Orders” section under main menu of App/Website/M-site to check your order status.</p>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h6 class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faqs-3" aria-expanded="true" >
                                        How can I get my order delivered faster?
                                    </h6>
                                    
                                    <div id="faqs-3" class="accordion-collapse collapse" >
                                        <p>Sorry, currently we do not have any service available to expedite the order delivery. In future, if we are offering such service and your area pincode is serviceable, you will receive a communication from our end.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="faq4" class="faq-section d-none">
                            <h4>Cancellations & Modifications</h4>                    
                        </div>

                        <div id="faq5" class="faq-section d-none">
                            <h4>Return & Exchange</h4>                    
                        </div>

                        <div id="faq6" class="faq-section d-none">
                            <h4>Sign Up & Login</h4>                    
                        </div>

                        <div id="faq7" class="faq-section d-none">
                            <h4>Payments</h4>                    
                        </div>

                        <div id="faq8" class="faq-section d-none">
                            <h4>Coupons and "My Cashback"</h4>                    
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