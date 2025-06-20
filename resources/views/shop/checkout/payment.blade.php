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
    const purchaser = @json($purchaser);
    const amountInCents = {{ $amountInCents ?? 1000 }};
    let elements;
    let clientSecret;

    document.addEventListener('DOMContentLoaded', async () => {
        // Fetch clientSecret
        clientSecret = await createPaymentIntent(purchaser.name, purchaser.email, purchaser.phone);
        if (!clientSecret) return;

        // Initialize Elements
        elements = stripe.elements({ clientSecret });
        const paymentElement = elements.create('payment', {
            layout: {
                type: 'tabs',
                defaultCollapsed: false,
            }
        });
        paymentElement.mount('#card-element');

    });

    async function createPaymentIntent(name, email, phone) {
        try {
            const res = await fetch("{{ route('shop.checkout.payment.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ name, email, phone })
            });

            if (!res.ok) {
                const error = await res.json();
                showError(error.error || 'Failed to get payment intent.');
                return null;
            }

            const data = await res.json();
            return data.clientSecret;
        } catch (err) {
            console.error(err);
            showError(err.message || 'Unexpected error.');
            return null;
        }
    }

    document.getElementById('payment-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const button = document.getElementById('submit');
        button.disabled = true;
        button.textContent = 'Confirming Payment';

        // Submit the Payment Element (required step)
        await elements.submit();

        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: "{{ route('shop.checkout.order.process') }}?from=redirect",
                payment_method_data: {
                    billing_details: {
                        name: purchaser.name,
                        email: purchaser.email,
                        phone: purchaser.phone
                    }
                }
            },
            clientSecret
        });

        if (error) {
            showError(error.message);
            button.disabled = false;
            button.textContent = 'Submit';
        } else {
            await handlePostPayment(paymentIntent);
        }
    });

    async function handlePostPayment(paymentIntent) {
        try {
            if (paymentIntent.status === 'requires_action') {
                const result = await stripe.confirmCardPayment(clientSecret);
                if (result.error) {
                    showError(result.error.message);
                    return;
                }
            }

            const confirmRes = await fetch("{{ route('shop.checkout.order.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ payment_intent_id: paymentIntent.id })
            });

            if (confirmRes.ok) {
                window.location.href = "{{ route('shop.checkout.payment.success') }}";
            } else {
                const errorData = await confirmRes.json();
                showError(errorData.error || 'Order confirmation failed.');
            }
        } catch (err) {
            console.error(err);
            showError(err.message || 'Unexpected error.');
        }
    }

    function showError(message) {
        document.getElementById('card-errors').textContent = message;
    }
</script>
@endsection