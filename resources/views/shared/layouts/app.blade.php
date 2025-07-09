<!-- views/shared/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTMThrift Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #002366;  /* UTM Navy Blue */
            --secondary-color: #FFD700;  /* UTM Gold */
        }
        
        .sidebar-item.active {
            background-color: rgba(0, 35, 102, 0.1);
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg fixed h-full">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-[var(--primary-color)]">
                    <i class="fas fa-recycle mr-2 text-[var(--secondary-color)]"></i>
                    UTMThrift Admin
                </h1>
            </div>
            
            <nav class="mt-4">
                <a href="{{ url('/dashboard') }}" 
                   class="sidebar-item flex items-center p-4 hover:bg-gray-100 {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie w-6 text-gray-500"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                
                <a href="{{ url('/admin/sellers') }}" 
                   class="sidebar-item flex items-center p-4 hover:bg-gray-100 {{ request()->is('sellers*') ? 'active' : '' }}">
                    <i class="fas fa-store w-6 text-gray-500"></i>
                    <span class="ml-3">Sellers</span>
                </a>
                
                <a href="{{ url('/admin/events') }}" 
                   class="sidebar-item flex items-center p-4 hover:bg-gray-100 {{ request()->is('events*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt w-6 text-gray-500"></i>
                    <span class="ml-3">Events</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-semibold text-gray-800">@yield('title')</h2>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=Admin+User" 
                            class="w-10 h-10 rounded-full" 
                            alt="Admin Avatar">
                    </button>
                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 4000)" 
                    class="mb-6 p-4 border border-green-400 text-green-700 bg-green-100 rounded transition-all"
                >
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                @yield('content')
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('scripts')
</body>
</html>


