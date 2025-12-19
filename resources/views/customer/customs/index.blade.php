@extends('layouts.maincustomer')

@section('title', 'My Saved Customizations')

@section('content')
<div class="container mt-5">
     <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Saved Customizations</h2>
        <a href="{{ route('customer.custom.create') }}" class="btn btn-success">
            + Create New Customization
        </a>
    </div>

    @if($customizations->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Options</th>
                    <th>Date Saved</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customizations as $custom)
                    <tr>
                        <td>{{ $custom->id }}</td>
                        <td>
                            <pre>{{ json_encode($custom->options, JSON_PRETTY_PRINT) }}</pre>
                        </td>
                        <td>{{ $custom->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('customer.custom.show', $custom->id) }}" class="btn btn-sm btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No saved customizations yet.</div>
    @endif
</div>
@endsection
