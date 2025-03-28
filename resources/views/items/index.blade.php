@extends('shared.layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard - Items List</h2>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Marketplace Items</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Price (RM)</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>RM {{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
