// Initialize Stripe with your publishable key
const stripe = Stripe('pk_test_51QaGP5R1E4g3P8Pbki5DgaFXVrfB2hS4aV9lAv1JRhZLkzgRw4eRbWZaNH3WepYM7e0LDEeoTJ2gMo3ICiHXDJBp00YqscFooG');

// Set up Stripe Elements
const elements = stripe.elements();
const cardElement = elements.create('card');
cardElement.mount('#card-element');

// Handle real-time validation errors from the card Element
cardElement.on('change', (event) => {
    const errorDisplay = document.getElementById('card-error');
    errorDisplay.textContent = event.error ? event.error.message : '';
});

// Handle form submission
document.getElementById('payment-form').addEventListener('submit', confirmOrder);

function confirmOrder(event) {
    event.preventDefault();

    const submitButton = document.getElementById('btn-payment');
    submitButton.disabled = true;

    // Collect form data
    const customerName = document.getElementById('customer_name').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;
    const country = document.getElementById('country').value;
    const postalCode = document.getElementById('postal_code').value;
    const notes = document.getElementById('notes').value;
    const price = document.getElementById('price').value;
    const currency = document.getElementById('currency').value;

    if (!customerName || !email || !address || !country || !postalCode || !price || !currency) {
        alert('Please fill in all required fields.');
        submitButton.disabled = false;
        return;
    }

    // Create a PaymentMethod
    stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: {
            name: customerName,
            email: email,
            address: {
                line1: address,
                postal_code: postalCode,
                country: country,
            },
        },
    }).then((result) => {
        if (result.error) {
            document.getElementById('card-error').textContent = result.error.message;
            submitButton.disabled = false;
        } else {
            const paymentMethodId = result.paymentMethod.id;

            // Send payment data to the backend
            fetch('https://1401-111-68-102-25.ngrok-free.app/studypool_clone/webhook.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    customer_name: customerName,
                    email: email,
                    address: address,
                    country: country,
                    postal_code: postalCode,
                    notes: notes,
                    price: price,
                    currency: currency,
                    payment_method_id: paymentMethodId,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert('Payment successful! Payment ID: ' + data.payment_id);
                    window.location.href = '/success-page';
                } else {
                    alert('Payment failed: ' + data.error);
                }
                submitButton.disabled = false;
            })
            .catch((error) => {
                alert('Something went wrong. Please try again.');
                submitButton.disabled = false;
            });
        }
    });
}
