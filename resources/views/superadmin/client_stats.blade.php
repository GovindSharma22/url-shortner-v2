@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Client Wise URL Statistics</h2>
    
  
    
    <!-- Navigation Links -->
    <div class="mb-3">
        <a href="{{ route('shorturls.index') }}" class="btn btn-primary">View All Short URLs</a>
        <a href="{{ route('superadmin.invite') }}" class="btn btn-secondary">Send Invites</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Total Short URLs</th>
                <th>Total Hits</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->total_urls }}</td>
                    <td>{{ $client->total_hits }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $clients->links() }}
</div>
@endsection
