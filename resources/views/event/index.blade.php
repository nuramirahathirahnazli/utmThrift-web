@extends('shared.layouts.app')

@section('content')
    <h2 class="mt-3">Events</h2>
    <p>List of upcoming events in UTMThrift.</p>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Thrift Sale 2025</td>
                <td>April 10, 2025</td>
                <td>UTM Hall</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Student Market Day</td>
                <td>May 5, 2025</td>
                <td>Main Campus</td>
            </tr>
        </tbody>
    </table>
@endsection
