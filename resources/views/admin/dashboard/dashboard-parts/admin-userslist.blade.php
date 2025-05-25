@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrUsers = $users->toArray();
@endphp
<div class="admin-UsersList">
    All Users
    @if (!empty($arrUsers))
        <table class="w-full border border-gray-300">
            <tbody>
                @foreach ($arrUsers as $user)
                    <tr class="w-full bg-white hover:bg-gray-50 border-b border-gray-300">
                        <td class="px-4 py-2 border-b border-gray-300 font-semibold text-gray-800">{{ $user['name'] }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $user['email'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection