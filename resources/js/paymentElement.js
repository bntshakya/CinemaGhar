const stripe = Stripe(
    "pk_test_51PXj5FCqn9fKiyjTliDqads8ddgwbd7McSuGE5CjzVR4RKvOI2rYnsjE6CiJRBtmPMrFW56fe8IspEu1GO4iXMAN002SrUiunG",
);

const options = {
    mode: "setup",
    currency: "usd",
    paymentMethodCreation: "manual",
    // Fully customizable with appearance API.
    appearance: {
        theme: "stripe",
    },
};

// Set up Stripe.js and Elements to use in checkout form
const elements = stripe.elements(options);

// Create and mount the Payment Element
const paymentElement = elements.create("payment", {
    layout: {
        type: "tabs",
        defaultCollapsed: false,
    },
});
paymentElement.mount("#payment-element");

const form = document.getElementById("payment-form");
const submitBtn = document.getElementById("submit");

const handleError = (error) => {
    const messageContainer = document.querySelector("#error-message");
    messageContainer.textContent = error.message;
    submitBtn.disabled = false;
};

form.addEventListener("submit", async (event) => {
    // We don't want to let default form submission happen here,
    // which would refresh the page.
    event.preventDefault();

    // Prevent multiple form submissions
    if (submitBtn.disabled) {
        return;
    }

    // Disable form submission while loading
    submitBtn.disabled = true;

    // Trigger form validation and wallet collection
    const { error: submitError } = await elements.submit();
    if (submitError) {
        handleError(submitError);
        return;
    }

    // Create the ConfirmationToken using the details collected by the Payment Element
    // and additional shipping information
    const { error, confirmationToken } = await stripe.createConfirmationToken({
        elements,
        params: {
            return_url: "https://localhost:8000/card/view",
        },
    });

    if (error) {
        // This point is only reached if there's an immediate error when
        // creating the ConfirmationToken. Show the error to your customer (for example, payment details incomplete)
        handleError(error);
        return;
    }

    // Create the SetupIntent
    const res = await fetch("/create-confirm-intent", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            confirmationTokenId: confirmationToken.id,
        }),
    });

    const data = await res.json();
    // Handle any next actions or errors. See the Handle any next actions step for implementation.
    handleServerResponse(data);
});

const handleServerResponse = async (response) => {
    if (response.error) {
        // Show error from server in payment setup form
        console.log("Error: ", response.error);
    } else if (response.status === "requires_action") {
        // Use Stripe.js to handle the required next action
        const { error, setupIntent } = await stripe.handleNextAction({
            clientSecret: response.clientSecret,
        });

        if (error) {
            // Show error from Stripe.js in payment setup form
            console.log("Stripe.js Error: ", error);
        } else {
            // Actions handled, show success message
            window.location.href = "/customer/cards";
        }
    } else {
        // No actions needed, show success message
        window.location.href = "/customer/cards";
    }
};
