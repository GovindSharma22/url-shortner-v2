@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invite New Client</h2>
    <form action="{{ route('superadmin.invites.send') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Client Name</label>
            <input type="text" name="client_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Invitation</button>
    </form>

    <h2 class="mt-4">Invited Clients</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Client</th>
                <th>Status</th>
                <th>Invited At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invites as $invite)
                <tr>
                    <td>{{ $invite->email }}</td>
                    <td>{{ $invite->client->name }}</td>
                    <td>{{ $invite->accepted_at ? 'Signed Up' : 'Pending' }}</td>
                    <td>{{ $invite->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $invites->links() }}
</div>
@endsection
