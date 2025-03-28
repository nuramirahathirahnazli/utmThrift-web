@extends('shared.layouts.app')

@section('content')
    <h2 class="mt-3">Sellers</h2>
    <p>List of registered sellers in UTMThrift.</p>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Seller Name</th>
                <th>Shop Name</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ahmad Razak</td>
                <td>ThriftHub</td>
                <td>012-3456789</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>UTM Fashion</td>
                <td>019-9876543</td>
            </tr>
        </tbody>
    </table>
@endsection
