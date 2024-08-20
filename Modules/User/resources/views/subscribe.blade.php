<head>
  <title>Checkout</title>
  <script src="https://js.stripe.com/v3/"></script>
  @vite('resources/js/subscribe.js')
</head>
<body>
  <!-- content here -->
    <form id="payment-form">
        <div id="payment-element">
            <!-- Elements will create form elements here -->
        </div>
        <button id="submit">Subscribe</button>
        <div id="error-message">
            <!-- Display error message to your customers here -->
        </div>
    </form>
</body>