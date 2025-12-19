@extends('layouts.maincustomer')

@section('title', 'View Customization')

@section('content')
<div class="container mt-5">
    <h2>Customization #{{ $custom->id }}</h2>

    <h5>Options:</h5>
    <pre>{{ json_encode($custom->options, JSON_PRETTY_PRINT) }}</pre>

    <a href="{{ route('customizations.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
