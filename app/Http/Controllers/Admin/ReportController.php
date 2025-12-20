<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        // Users Statistics
        $totalUsers = User::count();
        $newUsers = User::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        
        // Products Statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('stock_quantity', '>', 0)->count();
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->count();
        
        // Orders Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Revenue Statistics
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total_amount');
        
        // Complaints Statistics
        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $resolvedComplaints = Complaint::where('status', 'resolved')->count();
        
        // Top Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly Revenue Chart Data
        $monthlyRevenueData = Order::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('admin.reports.index', compact(
            'totalUsers', 'newUsers', 'totalProducts', 'activeProducts', 'lowStockProducts',
            'totalOrders', 'pendingOrders', 'completedOrders', 'cancelledOrders',
            'totalRevenue', 'monthlyRevenue', 'totalComplaints', 'pendingComplaints', 
            'resolvedComplaints', 'topProducts', 'monthlyRevenueData', 'dateFrom', 'dateTo'
        ));
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $filename = "report_{$type}_" . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        return response()->stream(function() use ($type, $dateFrom, $dateTo) {
            $handle = fopen('php://output', 'w');
            
            switch($type) {
                case 'users':
                    $this->exportUsers($handle, $dateFrom, $dateTo);
                    break;
                case 'products':
                    $this->exportProducts($handle);
                    break;
                case 'orders':
                    $this->exportOrders($handle, $dateFrom, $dateTo);
                    break;
                case 'complaints':
                    $this->exportComplaints($handle, $dateFrom, $dateTo);
                    break;
                default:
                    $this->exportOverview($handle, $dateFrom, $dateTo);
            }
            
            fclose($handle);
        }, 200, $headers);
    }
    
    private function exportOverview($handle, $dateFrom, $dateTo)
    {
        fputcsv($handle, ['Report Type', 'Value', 'Date Range']);
        fputcsv($handle, ['Total Users', User::count(), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['New Users', User::whereBetween('created_at', [$dateFrom, $dateTo])->count(), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['Total Products', Product::count(), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['Total Orders', Order::count(), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['Total Revenue', Order::where('status', 'completed')->sum('total_amount'), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['Monthly Revenue', Order::where('status', 'completed')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount'), "$dateFrom to $dateTo"]);
        fputcsv($handle, ['Total Complaints', Complaint::count(), "$dateFrom to $dateTo"]);
    }
    
    private function exportUsers($handle, $dateFrom, $dateTo)
    {
        fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Created At', 'Orders Count']);
        
        User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->withCount('orders')
            ->chunk(100, function($users) use ($handle) {
                foreach($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone,
                        $user->created_at->format('Y-m-d H:i:s'),
                        $user->orders_count
                    ]);
                }
            });
    }
    
    private function exportProducts($handle)
    {
        fputcsv($handle, ['ID', 'Name', 'Category', 'Price', 'Stock', 'Status', 'Created At']);
        
        Product::with('category')->chunk(100, function($products) use ($handle) {
            foreach($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->price,
                    $product->stock_quantity,
                    $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock',
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }
        });
    }
    
    private function exportOrders($handle, $dateFrom, $dateTo)
    {
        fputcsv($handle, ['ID', 'User', 'Total Amount', 'Status', 'Payment Status', 'Created At']);
        
        Order::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->chunk(100, function($orders) use ($handle) {
                foreach($orders as $order) {
                    fputcsv($handle, [
                        $order->id,
                        $order->user->name ?? 'N/A',
                        $order->total_amount,
                        $order->status,
                        $order->payment_status,
                        $order->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });
    }
    
    private function exportComplaints($handle, $dateFrom, $dateTo)
    {
        fputcsv($handle, ['ID', 'User', 'Subject', 'Status', 'Priority', 'Created At']);
        
        Complaint::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->chunk(100, function($complaints) use ($handle) {
                foreach($complaints as $complaint) {
                    fputcsv($handle, [
                        $complaint->id,
                        $complaint->user->name ?? 'N/A',
                        $complaint->subject,
                        $complaint->status,
                        $complaint->priority,
                        $complaint->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });
    }
}