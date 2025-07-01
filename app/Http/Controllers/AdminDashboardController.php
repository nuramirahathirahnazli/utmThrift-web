<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalSellers = Seller::whereHas('user', function ($query) {
            $query->where('user_type', 'Seller');
        })->count();

        $totalEvents = Event::count();

        $todayEvents = Event::whereDate('event_date', now()->toDateString())->count();

        $pendingSellers = Seller::where('verification_status', 'pending')
        ->whereHas('user', function ($query) {
            $query->where('user_type', 'Seller');
        })
        ->with('user')
        ->latest()
        ->take(5)
        ->get();


        $eventStats = Event::selectRaw('MONTH(event_date) as month, COUNT(*) as total')
            ->groupBy(DB::raw('MONTH(event_date)'))
            ->pluck('total', 'month')
            ->toArray();

        return view('welcome', compact(
            'totalUsers',
            'totalSellers',
            'totalEvents',
            'todayEvents',
            'pendingSellers',
            'eventStats'
        ));
    }


}

