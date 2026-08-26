<form action="{{ route('payment') }}" method="POST">
@csrf

<script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="{{ config('razorpay.key') }}"
    data-amount="50000"
    data-buttontext="Pay 500 INR"
    data-name="My Website"
    data-description="Test Payment"
    data-theme.color="#3399cc">
</script>

</form>