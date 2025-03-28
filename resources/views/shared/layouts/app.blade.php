<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTMThrift - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">UTMThrift Admin</a>
        </div>
    </nav>

    <!-- Tab Menu -->
    <ul class="nav nav-tabs mt-3 container">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('users') ? 'active' : '' }}" href="{{ url('/users/buyers') }}">Buyers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('sellers') ? 'active' : '' }}" href="{{ url('/users/sellers') }}">Sellers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('events') ? 'active' : '' }}" href="{{ url('/events') }}">Events</a>
        </li>
    </ul>

    <!-- Main Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
