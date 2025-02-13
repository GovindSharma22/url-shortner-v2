@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Shortened URLs</h2>
     <!-- Add the "Create Short URL" button -->
    <a href="{{ route('shorturls.create') }}" class="btn btn-primary mb-3">Create Short URL</a>

    <table class="table">
        <thead>
            <tr>
                <th>Short URL</th>
                <th>Long URL</th>
                <th>Hits</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($urls as $url)
            <tr>
                <td><a href="{{ url('/'.$url->short_code) }}" target="_blank">{{ url('/'.$url->short_code) }}</a></td>
                <td>{{ $url->long_url }}</td>
                <td>{{ $url->hit_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $urls->links() }}
    <!-- CSV Export Button -->
    
    
</div>
@endsection
