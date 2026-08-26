@extends('front.layouts.app')

@section('title', 'Delete Account')

@section('content')

<div class="container">    
    @include('front.account.common.sidebar')  
        
            <div class="col-md-9 col-12 px-0">
                <div class="details-accounts">
                    @include('front.account.common.message')
                    <h3>Delete Account</h3>    

                    <div class="order-history">
                        <div class="individual">
                            <p><b>Is this goodbye? Are you sure you don't want to reconsider?</b></p>
                            <ul>
                                <li>
                                    <p><b>You'll lose your order history, saved details, Myntra Credit, MynCash, SuperCoins and all other coupons and benefits.</b></p>
                                    <p class="tiny-font mt-2">Any account related benefits will be forfeited once the account is deleted and will no longer be available to you. You cannot recover the same. However, you can always create a new account. By deleting your account, you acknowledge you have read our Privacy Policy.</p>
                                </li>
                                <li>
                                    <p><b>Any pending orders, exchanges, returns or refunds will no longer be accessible via your account.</b></p>
                                    <p class="tiny-font mt-2">Myntra will try to complete the open transactions in the next 30 days on a best effort basis. However, we cannot ensure tracking & traceability of transactions once the account is deleted.</p>
                                </li>
                                <li>
                                    <p><b>Myntra may not extend New User coupon if an account is created with the same mobile number or email id.</b></p>
                                </li>
                                <li>
                                    <p><b>Myntra may refuse or delay deletion in case there are any pending grievances related to orders, shipments, cancellations or any other services offered by Myntra.</b></p>
                                </li>
                                <li>
                                    <p><b>Myntra may retain certain data for legitimate reasons such as security, fraud prevention, future abuse, regulatory compliance including exercise of legal rights or comply with legal orders under applicable laws.</b></p>
                                </li>
                            </ul>

                            <form method="POST" action="{{ route('account.delete') }}">
                                @csrf
                            
                                <label>
                                    <input type="checkbox" name="agree" required>
                                    I agree to all the terms and conditions*
                                </label>
                                <br><br>

                                <button type="submit" class="btn btn-outline-danger">Delete Anyway</button>
                                <a href="{{ route('front.home') }}" class="btn btn-primary">Keep Account</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>                              
    </div>
</div>

@endsection

@section('customJs')
<script>
    
</script>
@endsection