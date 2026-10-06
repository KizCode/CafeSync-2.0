<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function laporan(): View
    {
        $this->authorizeBusinessReports();

        $monthStart = now()->startOfMonth();
        $monthRevenue = $this->paidSumBetween($monthStart, now());
        $monthCount = $this->paidCountBetween($monthStart, now());

        return view('admin.laporan', [
            'monthRevenue' => $monthRevenue,
            'monthCount' => $monthCount,
            'monthExpense' => 0,
            'monthProfit' => $monthRevenue,
            'monthlySales' => $this->monthlySales(),
            'recentTransactions' => Transaction::query()
                ->where('status', 'lunas')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }

    public function analitik(): View
    {
        $this->authorizeBusinessReports();

        return view('admin.analitik', [
            'monthlySales' => $this->monthlySales(),
            'paymentMix' => Transaction::query()
                ->where('status', 'lunas')
                ->selectRaw('payment_method, count(*) as aggregate, sum(grand_total) as revenue')
                ->groupBy('payment_method')
                ->get(),
        ]);
    }

    public function export(): StreamedResponse
    {
        $this->authorizeBusinessReports();

        $filename = 'laporan-penjualan-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Invoice', 'Tanggal', 'Pelanggan', 'Metode', 'Total']);

            Transaction::query()
                ->where('status', 'lunas')
                ->latest()
                ->lazy()
                ->each(function (Transaction $transaction) use ($handle): void {
                    fputcsv($handle, [
                        $transaction->invoice_number,
                        $transaction->created_at?->format('Y-m-d H:i'),
                        $transaction->customer_name,
                        $transaction->payment_method,
                        $transaction->grand_total,
                    ]);
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function authorizeBusinessReports(): void
    {
        abort_unless(in_array(request()->user()->role, ['admin', 'owner'], true), 403);
    }

    /**
     * @return Collection<int, array{label: string, total: float, percent: int}>
     */
    private function monthlySales(): Collection
    {
        $points = collect();

        for ($offset = 5; $offset >= 0; $offset--) {
            $month = now()->subMonths($offset);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $points->push([
                'label' => $month->translatedFormat('M'),
                'total' => $this->paidSumBetween($start, $end),
            ]);
        }

        $max = max($points->max('total') ?: 0, 1);

        return $points->map(fn (array $point): array => [
            ...$point,
            'percent' => (int) round(($point['total'] / $max) * 100),
        ]);
    }

    private function paidSumBetween(Carbon $start, Carbon $end): float
    {
        return (float) Transaction::query()
            ->where('status', 'lunas')
            ->whereBetween('created_at', [$start, $end])
            ->sum('grand_total');
    }

    private function paidCountBetween(Carbon $start, Carbon $end): int
    {
        return Transaction::query()
            ->where('status', 'lunas')
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }
}
