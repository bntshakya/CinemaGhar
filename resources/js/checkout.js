// This is your test secret API key.
const stripe = Stripe(
    "pk_test_51PXj5FCqn9fKiyjTliDqads8ddgwbd7McSuGE5CjzVR4RKvOI2rYnsjE6CiJRBtmPMrFW56fe8IspEu1GO4iXMAN002SrUiunG",
);

initialize();

// Create a Checkout Session
async function initialize() {
    const fetchClientSecret = async () => {
        const response = await fetch(
            "http://localhost:8000/tickets/embed/buy",
            {
                method: "post",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(window.postData),
            },
        );
        const { clientSecret } = await response.json();
        return clientSecret;
    };

    const checkout = await stripe.initEmbeddedCheckout({
        fetchClientSecret,
    });

    // Mount Checkout
    checkout.mount("#checkout");
}
const form = document.getElementById("payment-form");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const { error } = await stripe.confirmPayment({
        // The client secret of the PaymentIntent
        clientSecret,
        confirmParams: {
            return_url: "https://google.com",
        },
    });

    if (error) {
        // This point will only be reached if there is an immediate error when
        // confirming the payment. Show error to your customer (for example, payment
        // details incomplete)
        const messageContainer = document.querySelector("#error-message");
        messageContainer.textContent = error.message;
    } else {
        // Your customer will be redirected to your `return_url`. For some payment
        // methods like iDEAL, your customer will be redirected to an intermediate
        // site first to authorize the payment, then redirected to the `return_url`.
    }
});
