<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // return 'hello';
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::whereIn('status', ['delivered', 'shipped'])->sum('total_amount'),
            'low_stock_products' => Product::where('stock_quantity', '<', 10)->count(),
            'visitors_today' => Visitor::whereDate('visited_at', $today)->count(),
            'visitors_yesterday' => Visitor::whereDate('visited_at', $yesterday)->count(),
            'visitors_week' => Visitor::where('visited_at', '>=', $thisWeek)->count(),
            'visitors_month' => Visitor::where('visited_at', '>=', $thisMonth)->count(),
            'total_visitors' => Visitor::count(),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();
        $top_products = Product::orderBy('stock_quantity', 'desc')->take(5)->get();
        $recent_users = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'top_products', 'recent_users'));
    }
}
