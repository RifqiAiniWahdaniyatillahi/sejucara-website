<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class SalesReportController extends Controller
{
    private const SALES_STATUSES = ['diproses', 'dikirim', 'selesai'];

    public function index(Request $request): View
    {
        [$startAt, $endAt] = $this->dateRange($request);

        $orderQuery = Order::query()
            ->whereIn('status', self::SALES_STATUSES)
            ->whereBetween('created_at', [$startAt, $endAt]);

        $orders = (clone $orderQuery)
            ->with('items.product')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalOrders = (clone $orderQuery)->count();
        $totalRevenue = $this->productRevenue($startAt, $endAt);
        $totalProductsSold = OrderItem::query()
            ->whereHas('order', fn ($query) => $query
                ->whereIn('status', self::SALES_STATUSES)
                ->whereBetween('created_at', [$startAt, $endAt]))
            ->sum('quantity');

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topProducts = OrderItem::query()
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_sales')
            ->with('product:id,name,deleted_at')
            ->whereNotNull('product_id')
            ->whereHas('order', fn ($query) => $query
                ->whereIn('status', self::SALES_STATUSES)
                ->whereBetween('created_at', [$startAt, $endAt]))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $dailySales = OrderItem::query()
            ->selectRaw('DATE(orders.created_at) as sale_date, COUNT(DISTINCT orders.id) as total_orders, SUM(order_items.subtotal) as total_revenue')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', self::SALES_STATUSES)
            ->whereBetween('orders.created_at', [$startAt, $endAt])
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        return view('admin.reports.sales', [
            'orders' => $orders,
            'topProducts' => $topProducts,
            'dailySales' => $dailySales,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalProductsSold' => $totalProductsSold,
            'averageOrderValue' => $averageOrderValue,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'salesStatuses' => self::SALES_STATUSES,
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        [$startAt, $endAt] = $this->dateRange($request);

        $orderQuery = Order::query()
            ->whereIn('status', self::SALES_STATUSES)
            ->whereBetween('created_at', [$startAt, $endAt]);

        $orders = (clone $orderQuery)
            ->with('items.product')
            ->latest()
            ->get();

        $totalOrders = (clone $orderQuery)->count();
        $totalRevenue = $this->productRevenue($startAt, $endAt);
        $totalProductsSold = OrderItem::query()
            ->whereHas('order', fn ($query) => $query
                ->whereIn('status', self::SALES_STATUSES)
                ->whereBetween('created_at', [$startAt, $endAt]))
            ->sum('quantity');

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topProducts = OrderItem::query()
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_sales')
            ->with('product:id,name,deleted_at')
            ->whereNotNull('product_id')
            ->whereHas('order', fn ($query) => $query
                ->whereIn('status', self::SALES_STATUSES)
                ->whereBetween('created_at', [$startAt, $endAt]))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();

        $dailySales = OrderItem::query()
            ->selectRaw('DATE(orders.created_at) as sale_date, COUNT(DISTINCT orders.id) as total_orders, SUM(order_items.subtotal) as total_revenue')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', self::SALES_STATUSES)
            ->whereBetween('orders.created_at', [$startAt, $endAt])
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        $filename = 'laporan-penjualan-sejucara-'.$startAt->format('Ymd').'-'.$endAt->format('Ymd').'.xls';

        return response()->streamDownload(function () use ($startAt, $endAt, $orders, $topProducts, $dailySales, $totalRevenue, $totalOrders, $totalProductsSold, $averageOrderValue): void {
            echo '<html><head><meta charset="UTF-8">';
            echo '<style>
                body { font-family: Arial, sans-serif; color: #222222; font-size: 11pt; }
                .title { font-size: 18pt; font-weight: bold; color: #222222; }
                .subtitle { color: #555555; margin-top: 4px; }
                .note { color: #555555; font-style: italic; margin-top: 4px; }
                .section { margin-top: 18px; font-size: 13pt; font-weight: bold; color: #222222; }
                table { border-collapse: collapse; margin-top: 8px; }
                th { background: #eeeeee; color: #222222; font-weight: bold; text-align: center; }
                th, td { border: 1px solid #999999; padding: 7px; vertical-align: top; }
                .summary th { background: #eeeeee; }
                .summary td { text-align: center; font-weight: bold; }
                .money { text-align: right; white-space: nowrap; mso-number-format: "\@"; }
                .center { text-align: center; }
                .text { mso-number-format: "\@"; }
                .total-row td { font-weight: bold; background: #f6f6f6; }
            </style></head><body>';

            echo '<div class="title">Laporan Penjualan Sejucara</div>';
            echo '<div class="subtitle">Periode: '.e($startAt->format('d/m/Y')).' sampai '.e($endAt->format('d/m/Y')).'</div>';
            echo '<div class="note">Omzet penjualan dihitung dari subtotal produk. Ongkir dicatat sebagai informasi transaksi, tetapi tidak dimasukkan sebagai pendapatan Sejucara.</div>';

            echo '<div class="section">Ringkasan Penjualan</div>';
            echo '<table class="summary">';
            echo '<tr><th style="width:180px;">Total Omzet Produk</th><th style="width:140px;">Pesanan Valid</th><th style="width:140px;">Produk Terjual</th><th style="width:180px;">Rata-rata Transaksi</th></tr>';
            echo '<tr>';
            echo '<td class="money">'.$this->excelRupiah($totalRevenue).'</td>';
            echo '<td>'.number_format($totalOrders, 0, ',', '.').'</td>';
            echo '<td>'.number_format($totalProductsSold, 0, ',', '.').'</td>';
            echo '<td class="money">'.$this->excelRupiah((int) round($averageOrderValue)).'</td>';
            echo '</tr>';
            echo '</table>';

            echo '<div class="section">Rincian Transaksi</div>';
            echo '<table>';
            echo '<tr><th style="width:45px;">No</th><th style="width:90px;">ID Pesanan</th><th style="width:130px;">Tanggal</th><th style="width:160px;">Pelanggan</th><th style="width:120px;">Status</th><th style="width:260px;">Item</th><th style="width:130px;">Omzet Produk</th><th style="width:110px;">Ongkir</th><th style="width:130px;">Total Dibayar</th></tr>';
            foreach ($orders as $index => $order) {
                $productSubtotal = $order->items->sum('subtotal');
                $shippingCost = max((int) $order->total_amount - (int) $productSubtotal, 0);
                $items = $order->items
                    ->map(fn ($item) => e($item->product?->name ?? 'Produk terhapus').' ('.e((string) $item->quantity).'x)')
                    ->join('<br>');

                echo '<tr>';
                echo '<td class="center">'.($index + 1).'</td>';
                echo '<td class="text">#'.e((string) $order->id).'</td>';
                echo '<td class="text">'.e($order->created_at->format('d/m/Y H:i')).'</td>';
                echo '<td>'.e($order->customer_name).'</td>';
                echo '<td>'.e(ucfirst(str_replace('_', ' ', $order->status))).'</td>';
                echo '<td>'.$items.'</td>';
                echo '<td class="money">'.$this->excelRupiah((int) $productSubtotal).'</td>';
                echo '<td class="money">'.$this->excelRupiah($shippingCost).'</td>';
                echo '<td class="money">'.$this->excelRupiah((int) $order->total_amount).'</td>';
                echo '</tr>';
            }
            echo '<tr class="total-row"><td colspan="6">Total Omzet Produk</td><td class="money">'.$this->excelRupiah($totalRevenue).'</td><td></td><td></td></tr>';
            echo '</table>';

            echo '<div class="section">Rekap Produk Terjual</div>';
            echo '<table>';
            echo '<tr><th style="width:45px;">No</th><th style="width:260px;">Produk</th><th style="width:130px;">Jumlah Terjual</th><th style="width:150px;">Total Penjualan</th></tr>';
            foreach ($topProducts as $index => $item) {
                echo '<tr>';
                echo '<td class="center">'.($index + 1).'</td>';
                echo '<td>'.e($item->product?->name ?? 'Produk terhapus').'</td>';
                echo '<td class="center">'.number_format((int) $item->total_quantity, 0, ',', '.').'</td>';
                echo '<td class="money">'.$this->excelRupiah((int) $item->total_sales).'</td>';
                echo '</tr>';
            }
            echo '</table>';

            echo '<div class="section">Rekap Penjualan Harian</div>';
            echo '<table>';
            echo '<tr><th style="width:45px;">No</th><th style="width:130px;">Tanggal</th><th style="width:120px;">Jumlah Pesanan</th><th style="width:150px;">Omzet Produk</th></tr>';
            foreach ($dailySales as $index => $day) {
                echo '<tr>';
                echo '<td class="center">'.($index + 1).'</td>';
                echo '<td class="text">'.e(Carbon::parse($day->sale_date)->format('d/m/Y')).'</td>';
                echo '<td class="center">'.number_format((int) $day->total_orders, 0, ',', '.').'</td>';
                echo '<td class="money">'.$this->excelRupiah((int) $day->total_revenue).'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function dateRange(Request $request): array
    {
        $startDate = $request->date('start_date') ?? now()->startOfMonth();
        $endDate = $request->date('end_date') ?? now()->endOfDay();

        $startAt = Carbon::parse($startDate)->startOfDay();
        $endAt = Carbon::parse($endDate)->endOfDay();

        if ($startAt->gt($endAt)) {
            [$startAt, $endAt] = [$endAt->copy()->startOfDay(), $startAt->copy()->endOfDay()];
        }

        return [$startAt, $endAt];
    }

    private function productRevenue(Carbon $startAt, Carbon $endAt): int
    {
        return (int) OrderItem::query()
            ->whereHas('order', fn ($query) => $query
                ->whereIn('status', self::SALES_STATUSES)
                ->whereBetween('created_at', [$startAt, $endAt]))
            ->sum('subtotal');
    }

    private function excelRupiah(int|float $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
