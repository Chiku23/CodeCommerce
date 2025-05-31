@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrUsers = $users->toArray();
    $thClass = "px-4 py-2 text-gray-700 border border-gray-500 bg-gray-300 text-center";
    $tdClass = "px-4 py-2 text-gray-700 border border-gray-400 text-center";
@endphp
<div class="admin-UsersList">
    <div class="text-3xl font-bold text-gray-800 mb-6">Users</div>
    @if (!empty($arrUsers))
        <table class="w-full border border-gray-300 shadow-lg">
            <thead>
                <th class="{{ $thClass }}">ID</th>
                <th class="{{ $thClass }}">User Name</th>
                <th class="{{ $thClass }}">User Email</th>
                <th class="{{ $thClass }}">Phone</th>
                <th class="{{ $thClass }}">Role</th>
            </thead>
            <tbody>
                @foreach ($arrUsers as $user)
                    <tr class="w-full bg-white hover:bg-gray-50 border-b border-gray-300">
                        <td class="{{ $tdClass }}">{{ $user['id'] }}</td>
                        <td class="{{ $tdClass }}">{{ $user['name'] }}</td>
                        <td class="{{ $tdClass }}">{{ $user['email'] }}</td>
                        <td class="{{ $tdClass }}">{{ $user['phone']?? 'NA' }}</td>
                        <td class="{{ $tdClass }}">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($user['is_admin'] == false) bg-green-100 text-green-800
                                @elseif($user['is_admin'] == true) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $user['is_admin'] ? 'Admin' : 'User' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection