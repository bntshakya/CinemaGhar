initialize();

async function initialize() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const sessionId = urlParams.get("session_id");
    const response = await fetch("localhost:8000/", {
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        method: "get",
        body: JSON.stringify({ session_id: sessionId }),
    });
    const session = await response.json();

    if (session.status == "open") {
        window.replace("http://localhost:8000/tickets/purchase/embed");
    } else if (session.status == "complete") {
        document.getElementById("success").classList.remove("hidden");
        document.getElementById("customer-email").textContent =
            session.customer_email;
    }
}
