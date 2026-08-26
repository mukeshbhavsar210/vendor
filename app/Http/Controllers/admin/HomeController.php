<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller {
    public function index(){
        $totalRevenue = Order::where('status', 'delivered')->sum('grandtotal');
        $todayRevenue = Order::where('status', 'delivered')->whereDate('created_at', today())->sum('grandtotal');
        $yesterdayRevenue = Order::where('status', 'delivered')->whereDate('created_at', today()->subDay())->sum('grandtotal');
        $newOrdersToday = Order::whereDate('created_at', today())->count();
        //$recentOrders = Order::with('user')->where('status', 'delivered')->latest()->take(5)->get();
        $recentOrders = Order::with('user')->whereDate('created_at', today())->latest()->take(5)->get();
        $categories = Category::withCount('products')->orderByDesc('products_count')->take(8)->get();

        $percentageChange = 0;
        if ($yesterdayRevenue > 0) {
            $percentageChange = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
        }

        $topProducts = OrderItem::selectRaw('product_id, SUM(qty) as total_sold')
            ->groupBy('product_id')
            ->havingRaw('SUM(qty) > 0') // 👉 ensures only purchased
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

        return view('admin.dashboard.index', compact(
                'totalRevenue', 'todayRevenue', 'percentageChange', 'newOrdersToday', 
                'recentOrders', 'categories', 'topProducts'
            ));
        
        //$admin = Auth::guard('admin')->user();
        //echo 'Welcome '.$admin->name.' <a href="'.route('admin.logout').'">Logout</a>';
    }

   public function topProducts(Request $request) {
        $filter = $request->top_filter ?? 'today';

        $query = OrderItem::selectRaw('product_id, SUM(qty) as total_sold')
            ->whereHas('order', function($q) use ($filter) {

                if ($filter == 'today') {
                    $q->whereDate('created_at', today());

                } elseif ($filter == 'week') {
                    $q->whereBetween('created_at', [
                        now()->subDays(7), now()
                    ]); // 👉 last 7 days (better than week)

                } elseif ($filter == 'month') {
                    $q->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);

                } elseif ($filter == 'year') {
                    $q->whereYear('created_at', now()->year);
                }
            });

            $topProducts = $query
                ->with(['product'])
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();                
                
            return response()->json($topProducts);
        }


    public function dashboardStats(Request $request) {
        $filter = $request->filter ?? 'year';

        $data = [];
        $growth = [];

        // 👉 COMMON STATS (add this)
        $totalIncome = Order::sum('grandtotal');
        $totalOrders = Order::count();

        $avgOrderValue = $totalOrders > 0 
            ? round($totalIncome / $totalOrders, 2) 
            : 0;

        // Example (adjust based on your logic)
        //$totalExpenses = Expense::sum('amount') ?? 0;

        // Dummy conversion (replace with real logic)
        $visitors = 1000; 
        $conversionRate = $visitors > 0 
            ? round(($totalOrders / $visitors) * 100, 2) 
            : 0;

        if ($filter == 'year') {
            $raw = Order::whereYear('created_at', now()->year)
                ->selectRaw('MONTH(created_at) as label, SUM(grandtotal) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $data[$i] = $raw[$i] ?? 0;
            }
        }

        elseif ($filter == 'week') {
            $start = now()->startOfWeek();
            $end   = now()->endOfWeek();

            $raw = Order::whereBetween('created_at', [$start, $end])
                ->selectRaw('DAYOFWEEK(created_at) as label, SUM(grandtotal) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            $map = [
                2 => 'Mon', 3 => 'Tue', 4 => 'Wed',
                5 => 'Thu', 6 => 'Fri', 7 => 'Sat', 1 => 'Sun'
            ];

            foreach ($map as $key => $day) {
                $data[$day] = $raw[$key] ?? 0;
            }
        }

        elseif ($filter == 'today') {
            $raw = Order::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) as label, SUM(grandtotal) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            for ($i = 0; $i < 24; $i++) {
                $data[$i] = $raw[$i] ?? 0;
            }
        }

        else {
            $daysInMonth = now()->daysInMonth;
            $raw = Order::whereMonth('created_at', now()->month)
                ->selectRaw('DAY(created_at) as label, SUM(grandtotal) as total')
                ->groupBy('label')
                ->pluck('total', 'label')
                ->toArray();

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $data[$i] = $raw[$i] ?? 0;
            }
        }

        $prev = null;

        foreach ($data as $key => $value) {
            if ($prev !== null && $prev > 0) {
                $growth[$key] = $prev > 0 
                    ? abs(round((($value - $prev) / $prev) * 100, 1)) 
                    : 0;                
            } else {
                $growth[$key] = 0;
            }
            $prev = $value;
        }

        return response()->json([
            'data' => $data,
            'growth' => $growth,
            'filter' => $filter,
            'totalIncome' => $totalIncome,
            'conversionRate' => $conversionRate,
            'avgOrderValue' => $avgOrderValue,
            //'totalExpenses' => $totalExpenses,
        ]);
    }


    public function recentOrders(Request $request) {
        $filter = $request->filter;

        $query = Order::with('user');

        $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);

        if ($filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        }

        if ($filter == 'week') {
            $query->whereBetween('created_at', [
                Carbon::now()->subWeek(),
                Carbon::now()
            ]);
        }

        if ($filter == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        if ($filter == 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $orders = $query->latest()->take(5)->get();

        $html = view('admin.dashboard.recent-orders', compact('orders'))->render();

        return response()->json(['html' => $html]);
    }



    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
