$(document).ready(function() {    
    $('#openLogin').on('click', function () {
        $('#authModal').fadeIn(200);
        $('#loginForm').show();
        $('#registerForm').hide();
    });

    $('#openRegister').on('click', function () {
        $('#authModal').fadeIn(200);
        $('#loginForm').hide();
        $('#registerForm').show();
    });

    $('#showRegister').on('click', function () {
        $('#loginForm').hide();
        $('#registerForm').fadeIn(200);
    });

    $('#showLogin').on('click', function () {
        $('#registerForm').hide();
        $('#loginForm').fadeIn(200);
    });

    $('#closeAuthModal').on('click', function () {
        $('#authModal').fadeOut(200);
    });

    $('.auth-overlay').on('click', function () {
        $('#authModal').fadeOut(200);
    });   
});

$("#registrationForm").submit(function(event){
    event.preventDefault();

    $("button[type='submit']").prop('disabled', true);

    $.ajax({
        url: '{{ route("account.processRegistration") }}',
        type: 'post',
        data: $("#registrationForm").serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type='submit']").prop('disabled', false);

            var errors = response.errors;

            if(response.status == false){
                if(errors.name){
                    $("#name").siblings("p").addClass('invalid-feedback').html(errors.name);
                    $("#name").addClass('is-invalid');
                } else {
                    $("#name").siblings("p").removeClass('invalid-feedback').html();
                    $("#name").removeClass('is-invalid');
                }

                if(errors.email){
                    $("#email").siblings("p").addClass('invalid-feedback').html(errors.email);
                    $("#email").addClass('is-invalid');
                } else {
                    $("#email").siblings("p").removeClass('invalid-feedback').html();
                    $("#email").removeClass('is-invalid');
                }

                if(errors.password){
                    $("#password").siblings("p").addClass('invalid-feedback').html(errors.password);
                    $("#password").addClass('is-invalid');
                } else {
                    $("#password").siblings("p").removeClass('invalid-feedback').html();
                    $("#password").removeClass('is-invalid');
                }

                if(errors.confirm_password){
                    $("#confirm_password").siblings("p").addClass('invalid-feedback').html(errors.confirm_password);
                    $("#confirm_password").addClass('is-invalid');
                } else {
                    $("#confirm_password").siblings("p").removeClass('invalid-feedback').html();
                    $("#confirm_password").removeClass('is-invalid');
                }
            } else {
                $("#name").siblings("p").removeClass('invalid-feedback').html();
                $("#name").removeClass('is-invalid');
                $("#email").siblings("p").removeClass('invalid-feedback').html();
                $("#email").removeClass('is-invalid');
                $("#password").siblings("p").removeClass('invalid-feedback').html();
                $("#password").removeClass('is-invalid');
                $("#confirm_password").siblings("p").removeClass('invalid-feedback').html();
                $("#confirm_password").removeClass('is-invalid');

                window.location.href='{{ route("front.home") }}'
            }
        },
        error: function(JQXHR, exception){
            console.log("Something went wrong");
        }
    })
}); 

//Login form
$('#loginForm').submit(function(e) {
    e.preventDefault(); // prevent page refresh
    $('.invalid-feedback').text('').addClass('d-none');
    $('.form-control').removeClass('is-invalid');
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if(response.status) {
                window.location.href = '/account/dashboard';
            }
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $('#error_' + key).text(value[0]).removeClass('d-none');
                    $('#login_' + key).addClass('is-invalid'); // adds red border
                });
            }
        }
    });
});

$('#forgotPasswordForm').submit(function(e) {
    e.preventDefault();

    // Remove previous errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    let form = $(this);
    let url = form.attr('action');

    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            alert(response.message); // or display inside modal
            form[0].reset();
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $('#forgot_' + key).addClass('is-invalid'); // add red border
                    $('#error_' + key).text(value[0]); // show error message
                });
            }
        }
    });
});