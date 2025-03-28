@extends('shared.layouts.app')

@section('content')
    <h2 class="mt-3">Buyers</h2>
    <p>List of registered buyers in UTMThrift.</p>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Buyer Name</th>
                <th>Email</th>
                <th>Registered Since</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Ali Hassan</td>
                <td>alihassan@email.com</td>
                <td>March 2025</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Nora Lee</td>
                <td>noralee@email.com</td>
                <td>February 2025</td>
            </tr>
        </tbody>
    </table>
@endsection
