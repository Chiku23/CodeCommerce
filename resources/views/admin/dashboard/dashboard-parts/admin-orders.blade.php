@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrOrders = $orders->toArray();
    // Define common classes for reusability
    $thClasses = 'px-6 py-3 text-left text-xs text-center font-bold text-gray-800 uppercase tracking-wider border-2 border-gray-300';
    $tdBorderClass = 'border-2 border-gray-300';
    $tdClass = "px-6 py-3 whitespace-nowrap text-sm text-center";
@endphp
<div class="admin-orders p-6 min-h-screen">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Orders</h2>

    @if (!empty($arrOrders))
        <div class="bg-white shadow-md">
            <table class="w-full border-2 border-gray-800">
                <thead class="bg-gray-200">
                    <tr>
                        <th scope="col" class="{{ $thClasses }}">
                            ID
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            User ID
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Status
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Total Amount
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Purchaser Email
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Payment Method
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Payment Status
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Payment Intent ID
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Tracking Number
                        </th>
                        <th scope="col" class="{{ $thClasses }}">
                            Created At
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($arrOrders as $order)
                        @php
                            // Decode shipping address to get purchaser name
                            $shippingAddress = json_decode($order['shipping_address'], true);
                            $purchaserEmail = $shippingAddress['email'] ?? 'N/A';
                        @endphp
                        <tr>
                            <td class="{{ $tdClass }} font-medium text-gray-900 {{ $tdBorderClass }}">
                                {{ $order['id'] }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ $order['user_id'] }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order['status'] == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order['status'] == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order['status'] == 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {!! dollarSign() !!} {{ number_format($order['total_amount'], 2) }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ $purchaserEmail }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ $order['payment_method'] ?? 'N/A' }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order['payment_status'] == 'paid') bg-green-100 text-green-800
                                    @elseif($order['payment_status'] == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order['payment_status']) }}
                                </span>
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ $order['payment_intent_id'] ?? 'N/A' }}
                            </td>
                             <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ $order['tracking_number'] ?? 'N/A' }}
                            </td>
                            <td class="{{ $tdClass }} text-gray-500 {{ $tdBorderClass }}">
                                {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 text-lg">No orders found.</p>
    @endif
</div>
@endsection