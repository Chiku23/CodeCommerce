@extends('shop.layouts.app')

@section('content')
{{-- Include the Stripe Script --}}
<script src="https://js.stripe.com/v3/"></script>

<div class="paymentsPage w-full p-4 bg-gray-100 bg-white shadow-md">
    <div class="container">
        <form action="" method="" id="payment-form">
            @csrf
            <div class="purchasedetails mb-4">
                <p><strong>Name:</strong> {{ $purchaser['name'] }}</p>
                <p><strong>Email:</strong> {{ $purchaser['email'] }}</p>
                <p><strong>Phone:</strong> {{ $purchaser['phone'] }}</p>
            </div>
            
            <div class="mb-4">
                <label for="card-element" class="block mb-2">Credit or debit card</label>
                <div id="card-element" class="border p-3 rounded"></div>
                <div id="card-errors" class="text-red-600 mt-2"></div>
            </div>

            <button id="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Payment</button>
        </form>
    </div>
</div>

<script>
    const stripe = Stripe("{{ config('services.stripe.public') }}");
    const elements = stripe.elements();
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSize: '16px',
            '::placeholder': {
                color: '#a0aec0',
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const card = elements.create('card', {
        style: style,
        hidePostalCode: true // Optional to simplify the form
    });

    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit');
    const purchaser = @json($purchaser);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitButton.disabled = true;
        submitButton.textContent = 'Confirming Payment';
        try {
            // Fetch client secret
            const response = await fetch("{{ route('shop.checkout.payment.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: purchaser.name,
                    email: purchaser.email,
                    phone: purchaser.phone
                })
            });

            // Check if the response from your backend was successful
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Failed to get client secret from server.');
            }

            const { clientSecret } = await response.json();

            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: purchaser.name,
                        email: purchaser.email,
                        phone: purchaser.phone
                    }
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Submit';
                window.location.href = "{{ route('shop.checkout.payment') }}";
            } else {
                // Payment was successful, now send paymentIntent.id to backend via POST
                const confirmOrderResponse = await fetch("{{ route('shop.checkout.order.process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id
                    })
                });

                if (confirmOrderResponse.ok) {
                    window.location.href = "{{ route('shop.checkout.payment.success') }}"; // Redirect to the GET route for display
                } else {
                    const errorData = await confirmOrderResponse.json();
                    document.getElementById('card-errors').textContent = errorData.error || 'Failed to confirm order on server.';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Submit';
                }
            }
        } catch (err) {
            // Handle any errors that occurred during fetch or Stripe confirmation
            document.getElementById('card-errors').textContent = err.message || 'An unexpected error occurred.';
            submitButton.disabled = false; // Re-enable button on error
            submitButton.textContent = 'Submit';
            console.error('Payment processing error:', err);
        }
    });
</script>
@endsection