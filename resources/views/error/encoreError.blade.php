@extends('base')
@section('title', $errorCode.' - '.$errorMessage)

@section('page')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        h1 {
            font-size: 100px;
        }

        p {
            font-size: 24px;
        }
    </style>

    <div class="container">
        <img src="{{asset('images/icon-dark-big.jpg')}}" alt="" style="border-radius: 50%; width: 150px;">
        <h1 class="encore-text-red fw-bold">OOPS!</h1>
        <p class="encore-text-dark fw-bold">{{ $errorCode }} - {{ $errorMessage }}</p>
    </div>
@endsection