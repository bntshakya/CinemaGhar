<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="A demo of a payment on Stripe" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://js.stripe.com/v3/"></script>
    @Vite('resources/js/checkout.js')
  </head>
  <body>
    <script>
        window.postData = {!! json_encode($data) !!};
    </script>
    
    <!-- Display a payment form -->
      <div id="checkout">
        <!-- Checkout will insert the payment form here -->
      </div>
  </body>
</html>