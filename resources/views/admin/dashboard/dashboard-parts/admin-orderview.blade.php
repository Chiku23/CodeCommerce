@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    // $objOrder
    $tdBorderClass = 'border-2 border-gray-300';
    $td1Class = "px-2 w-1/4 py-3 whitespace-nowrap text-sm text-center font-medium text-gray-900";
    $tdClass = "px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-500 text-center";

    // Decode shipping address to get purchaser name
    $shippingAddress = json_decode($objOrder->shipping_address, true);
    $purchaserEmail = $shippingAddress['email'] ?? 'N/A';
@endphp

<div class="OrderDetails">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Order Details - {{ $objOrder->id }}</h2>
    <table class="w-1/2 border-2 border-gray-800">
                <tbody class="bg-white">
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Order ID
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $objOrder->id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            User ID
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $objOrder->user_id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Order Amount
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {!! dollarSign() !!} {{ number_format($objOrder->total_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Order Status
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($objOrder->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($objOrder->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($objOrder->status == 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($objOrder->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Purchaser Email
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $purchaserEmail }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Payment Method
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $objOrder->payment_method }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Payment Status
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($objOrder->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($objOrder->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($objOrder->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Payment Intent
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $objOrder->payment_intent_id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Tracking Number
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ $objOrder->tracking_number ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="{{ $td1Class }} {{ $tdBorderClass }}">
                            Ordered At
                        </td>
                        <td class="{{ $tdClass }} {{ $tdBorderClass }}">
                            {{ \Carbon\Carbon::parse($objOrder->created_at)->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                </tbody>
            </table>
</div>


@endsection