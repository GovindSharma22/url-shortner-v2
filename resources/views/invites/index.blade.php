@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Send an Invite</h2>
    <form method="POST" action="{{ route('invites.send') }}">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Invite</button>
    </form>
    <hr>
    <h3>Invited Users</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invites as $invite)
            <tr>
                <td>{{ $invite->email }}</td>
                <td>{{ $invite->expires_at > now() ? 'Pending' : 'Expired' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $invites->links() }}
</div>
@endsection