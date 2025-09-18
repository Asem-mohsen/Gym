@extends('layout.user.master')

@section('title', 'Payment Redirect')

@section('content')

    <div class="container mx-auto my-10 text-center">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg mx-auto">
            <h1 class="text-2xl font-bold text-green-600 mb-4">
                🎉 Thank You!
            </h1>

            <p class="text-gray-700 mb-6">
                Your payment has been processed successfully. 
            </p>

            <a href="{{ url('/') }}"
            class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Back to Home
            </a>
        </div>
    </div>

@endsection
