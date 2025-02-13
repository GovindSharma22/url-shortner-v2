@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Generated Short URLs</h2>
    <a href="{{ route('superadmin.shorturls.export') }}" class="btn btn-success mb-3">Download CSV</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Short URL</th>
                <th>Long URL</th>
                <th>Hit Count</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($urls as $url)
                <tr>
                    <td>{{ $url->user->name }}</td>
                    <td><a href="{{ url($url->short_code) }}" target="_blank">{{ url($url->short_code) }}</a></td>
                    <td>{{ $url->long_url }}</td>
                    <td>{{ $url->hit_count }}</td>
                    <td>{{ $url->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $urls->links() }}
</div>
@endsection
