// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/apikeys

const stripe = Stripe(
    "pk_test_51PXj5FCqn9fKiyjTliDqads8ddgwbd7McSuGE5CjzVR4RKvOI2rYnsjE6CiJRBtmPMrFW56fe8IspEu1GO4iXMAN002SrUiunG",
);
const response = await fetch("/user/subscription/setData", {
    method: "post",
    headers: { "Content-Type": "application/json" },
});

const { clientSecret } = await response.json();

const options = {
    clientSecret: clientSecret,
    // Fully customizable with appearance API.
};

// Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 5
const elements = stripe.elements(options);

// Create and mount the Payment Element
const paymentElement = elements.create("payment");
paymentElement.mount("#payment-element");
