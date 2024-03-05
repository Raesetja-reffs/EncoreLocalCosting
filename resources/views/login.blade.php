@extends('base')
@section('title', 'Login')
<link rel="stylesheet" href="{{asset('css/login.css')}}">

@section('page')
    <div style="background-image: url('{{ asset('images/loginBackground.jpg') }}');">
        <div class="wrapper fadeInDown">
            <div id="formContent">
                <!-- Tabs Titles -->
                <h2 class="active encore-text-dark"> EDI LOCAL COSTING </h2>
                <!-- Icon -->
                <div class="fadeIn first">
                    <img src="{{asset('images/icon-light-big.jpg')}}" id="icon" alt="User Icon" />
                </div>
                <!-- Login Form -->
                <form>
                    <input type="text" id="username" class="fadeIn second" name="username" placeholder="username">
                    <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                    <p id="message">Invalid Credentials</p>
                </form>
                <input type="submit" class="fadeIn fourth" id="login" value="Log In">
                
                <!-- Remind Passowrd -->
                <div id="formFooter">
                    {{-- <a class="underlineHover" href="">Forgot Password?</a> --}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });
    $(document).ready(function() {
        $('#message').hide();
        
        $('#login').click(function() {
            $.ajax({
                url: '{!!url("/getLogin")!!}',
                type: "GET",
                data: {
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                success: function(data) {
                    if (data.success) {
                        // If login is successful, redirect to the desired page
                        window.location = '{!!url("/")!!}';
                    } else {
                        // If login failed, show the error message to the user
                        $('#username').css('border-color', 'red');
                        $('#password').css('border-color', 'red');
                        $('#message').css('border-color', 'red');
                        $('#message').text(data.message).show();
                        $('#message').css('color', 'red');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    
                }
            });
        });
    });
</script>

<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
        alert(msg);
    }
</script>

@endsection