@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Shorten a URL</h2>
    <form method="POST" action="{{ route('shorturls.store') }}">
        @csrf
        <div class="mb-3">
            <label for="long_url" class="form-label">Enter Long URL:</label>
            <input type="url" name="long_url" id="long_url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Short URL</button>
    </form>
</div>
@endsection
