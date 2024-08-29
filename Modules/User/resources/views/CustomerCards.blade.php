<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
</head>
<x-navbar></x-navbar>

<body>
    <div class="defaultMethod">
        @if ($defaultMethod)
            <div>
                <strong>Default Payment Method:</strong>
                <h5 class="card-title">{{ $defaultMethod->card->brand }}</h5>
                <p class="card-text">
                    <strong>Country:</strong> {{ $defaultMethod->card->country }}<br>
                    <strong>Expiry:</strong> {{ $defaultMethod->card->exp_month }}/{{ $defaultMethod->card->exp_year }}<br>
                    <strong>Last 4 Digits:</strong> **** **** **** {{ $defaultMethod->card->last4 }}
                </p>
            </div>
        @else
            <div>
                <strong>Default Payment Method has not been selected.</strong>
            </div>
        @endif
    </div>
    <form action="{{route('User.setCard')}}" method='post'>
        @csrf
        @foreach ($paymentMethods as $paymentMethod)
            <div>
                <input type="radio" id="{{ $paymentMethod->id }}" name="paymentMethod" value="{{ $paymentMethod->id }}">
                <label for="{{ $paymentMethod->id }}">
                    <div class="card mb-3 p-3 shadow-sm" style="border-radius: 10px; background-color: #f8f9fa;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $paymentMethod->card->brand }}</h5>
                            <p class="card-text">
                                <strong>Country:</strong> {{ $paymentMethod->card->country }}<br>
                                <strong>Expiry:</strong>
                                {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}<br>
                                <strong>Last 4 Digits:</strong> **** **** **** {{ $paymentMethod->card->last4 }}
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        @endforeach
        <button type="submit">Submit</button>
    </form>
    <button><a href="{{route('card.view')}}">Add Card</a></button>
</body>

</html>