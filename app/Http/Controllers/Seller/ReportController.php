<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'today');
        if (!in_array($range, ['today', 'month', 'year'])) $range = 'today';

        [$start, $end] = $this->getRangeDates($range);

        $qtyColumn = $this->detectQtyColumn();
        $paidMode  = $this->detectPaidMode(); // 'is_paid' or 'paid_at'
        $totalColumn = $this->detectOrderTotalColumn(); // 'total' or 'subtotal' or null

        // Best seller (Qty) & Revenue per produk
        $productAggQuery = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end]);

        if ($paidMode === 'is_paid') {
            $productAggQuery->where('orders.is_paid', 1);
        } else {
            $productAggQuery->whereNotNull('orders.paid_at');
        }

        $productAgg = $productAggQuery
            ->selectRaw("
                order_items.product_name as name,
                SUM(order_items.$qtyColumn) as qty,
                SUM(order_items.line_total) as revenue
            ")
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->get();

        // Revenue by date
        if ($totalColumn) {
            $revQuery = DB::table('orders')
                ->whereBetween('created_at', [$start, $end]);

            if ($paidMode === 'is_paid') {
                $revQuery->where('is_paid', 1);
            } else {
                $revQuery->whereNotNull('paid_at');
            }

            $revenueByDate = $revQuery
                ->selectRaw("DATE(created_at) as d, SUM($totalColumn) as revenue")
                ->groupBy('d')
                ->orderBy('d')
                ->get();
        } else {
            // fallback: hitung dari order_items.line_total
            $revQuery = DB::table('orders')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$start, $end]);

            if ($paidMode === 'is_paid') {
                $revQuery->where('orders.is_paid', 1);
            } else {
                $revQuery->whereNotNull('orders.paid_at');
            }

            $revenueByDate = $revQuery
                ->selectRaw('DATE(orders.created_at) as d, SUM(order_items.line_total) as revenue')
                ->groupBy('d')
                ->orderBy('d')
                ->get();
        }

        return view('seller.reports.index', [
            'range' => $range,
            'productAgg' => $productAgg,
            'revenueByDate' => $revenueByDate,
        ]);
    }

    /**
     * Export XLSX + charts
     * URL: /seller/reports/export?range=today|month|year
     */
    public function exportXlsx(Request $request)
    {
        $range = $request->get('range', 'today');
        if (!in_array($range, ['today', 'month', 'year'])) $range = 'today';

        [$start, $end] = $this->getRangeDates($range);

        $qtyColumn = $this->detectQtyColumn();
        $paidMode  = $this->detectPaidMode();
        $totalColumn = $this->detectOrderTotalColumn();

        // 1) product summary rows
        $productAggQuery = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end]);

        if ($paidMode === 'is_paid') {
            $productAggQuery->where('orders.is_paid', 1);
        } else {
            $productAggQuery->whereNotNull('orders.paid_at');
        }

        $productRows = $productAggQuery
            ->selectRaw("
                order_items.product_name as product_name,
                SUM(order_items.$qtyColumn) as qty,
                SUM(order_items.line_total) as revenue
            ")
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->get();

        // 2) revenue by date rows
        if ($totalColumn) {
            $revQuery = DB::table('orders')
                ->whereBetween('created_at', [$start, $end]);

            if ($paidMode === 'is_paid') {
                $revQuery->where('is_paid', 1);
            } else {
                $revQuery->whereNotNull('paid_at');
            }

            $revenueRows = $revQuery
                ->selectRaw("DATE(created_at) as d, SUM($totalColumn) as revenue")
                ->groupBy('d')
                ->orderBy('d')
                ->get();
        } else {
            $revQuery = DB::table('orders')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$start, $end]);

            if ($paidMode === 'is_paid') {
                $revQuery->where('orders.is_paid', 1);
            } else {
                $revQuery->whereNotNull('orders.paid_at');
            }

            $revenueRows = $revQuery
                ->selectRaw('DATE(orders.created_at) as d, SUM(order_items.line_total) as revenue')
                ->groupBy('d')
                ->orderBy('d')
                ->get();
        }

        // ====== Build Spreadsheet ======
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('FOUR Cafe & Coffee')
            ->setTitle('FOUR Report')
            ->setSubject('Sales Report')
            ->setDescription("Report range: $range");

        // Sheet 1: Summary
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Ringkasan Produk');

        $sheet1->setCellValue('A1', 'FOUR Cafe & Coffee - Ringkasan Produk');
        $sheet1->setCellValue('A2', 'Range');
        $sheet1->setCellValue('B2', strtoupper($range));
        $sheet1->setCellValue('A3', 'Periode');
        $sheet1->setCellValue('B3', $start->format('Y-m-d') . ' s/d ' . $end->format('Y-m-d'));

        $sheet1->setCellValue('A5', 'Produk');
        $sheet1->setCellValue('B5', 'Qty Terjual');
        $sheet1->setCellValue('C5', 'Revenue (Rp)');

        $r = 6;
        $totalQty = 0;
        $totalRev = 0;

        foreach ($productRows as $row) {
            $sheet1->setCellValue("A{$r}", $row->product_name);
            $sheet1->setCellValue("B{$r}", (int)$row->qty);
            $sheet1->setCellValue("C{$r}", (int)$row->revenue);
            $totalQty += (int)$row->qty;
            $totalRev += (int)$row->revenue;
            $r++;
        }

        $sheet1->setCellValue("A{$r}", 'TOTAL');
        $sheet1->setCellValue("B{$r}", $totalQty);
        $sheet1->setCellValue("C{$r}", $totalRev);

        $sheet1->getStyle('A5:C5')->getFont()->setBold(true);
        $sheet1->getStyle("A{$r}:C{$r}")->getFont()->setBold(true);

        $sheet1->getColumnDimension('A')->setWidth(30);
        $sheet1->getColumnDimension('B')->setWidth(14);
        $sheet1->getColumnDimension('C')->setWidth(18);

        // format angka
        $sheet1->getStyle("B6:B{$r}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet1->getStyle("C6:C{$r}")->getNumberFormat()->setFormatCode('#,##0');

        // Sheet 2: Revenue by Date + Line Chart
        $sheet2 = new Worksheet($spreadsheet, 'Revenue Harian');
        $spreadsheet->addSheet($sheet2, 1);

        $sheet2->setCellValue('A1', 'Revenue Harian (Paid Only)');
        $sheet2->setCellValue('A3', 'Tanggal');
        $sheet2->setCellValue('B3', 'Revenue');

        $rowIdx = 4;
        foreach ($revenueRows as $rev) {
            $sheet2->setCellValue("A{$rowIdx}", (string)$rev->d);
            $sheet2->setCellValue("B{$rowIdx}", (int)$rev->revenue);
            $rowIdx++;
        }

        $lastDataRow = $rowIdx - 1;
        $sheet2->getStyle('A3:B3')->getFont()->setBold(true);
        $sheet2->getColumnDimension('A')->setWidth(14);
        $sheet2->getColumnDimension('B')->setWidth(18);
        if ($lastDataRow >= 4) {
            $sheet2->getStyle("B4:B{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0');
        }

        // LINE CHART
        if ($lastDataRow >= 4) {
            $labels = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Revenue Harian'!\$B\$3", null, 1),
            ];
            $categories = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Revenue Harian'!\$A\$4:\$A\${$lastDataRow}", null, ($lastDataRow - 3)),
            ];
            $values = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Revenue Harian'!\$B\$4:\$B\${$lastDataRow}", null, ($lastDataRow - 3)),
            ];

            $series = new DataSeries(
                DataSeries::TYPE_LINECHART,
                DataSeries::GROUPING_STANDARD,
                range(0, count($values) - 1),
                $labels,
                $categories,
                $values
            );

            $plotArea = new PlotArea(null, [$series]);
            $legend = new Legend(Legend::POSITION_RIGHT, null, false);
            $title = new Title('Revenue per Hari');

            $chart = new Chart(
                'revenue_chart',
                $title,
                $legend,
                $plotArea
            );

            // posisi chart
            $chart->setTopLeftPosition('D3');
            $chart->setBottomRightPosition('L20');

            $sheet2->addChart($chart);
        }

        // Sheet 3: Best Seller Qty + Bar Chart
        $sheet3 = new Worksheet($spreadsheet, 'Best Seller');
        $spreadsheet->addSheet($sheet3, 2);

        $sheet3->setCellValue('A1', 'Best Seller (Qty)');
        $sheet3->setCellValue('A3', 'Produk');
        $sheet3->setCellValue('B3', 'Qty');

        $rr = 4;
        foreach ($productRows as $row) {
            $sheet3->setCellValue("A{$rr}", $row->product_name);
            $sheet3->setCellValue("B{$rr}", (int)$row->qty);
            $rr++;
        }
        $lastBestRow = $rr - 1;

        $sheet3->getStyle('A3:B3')->getFont()->setBold(true);
        $sheet3->getColumnDimension('A')->setWidth(30);
        $sheet3->getColumnDimension('B')->setWidth(10);

        if ($lastBestRow >= 4) {
            $sheet3->getStyle("B4:B{$lastBestRow}")->getNumberFormat()->setFormatCode('#,##0');
        }

        // BAR CHART
        if ($lastBestRow >= 4) {
            $labels = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Best Seller'!\$B\$3", null, 1),
            ];
            $categories = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Best Seller'!\$A\$4:\$A\${$lastBestRow}", null, ($lastBestRow - 3)),
            ];
            $values = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Best Seller'!\$B\$4:\$B\${$lastBestRow}", null, ($lastBestRow - 3)),
            ];

            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, count($values) - 1),
                $labels,
                $categories,
                $values
            );
            $series->setPlotDirection(DataSeries::DIRECTION_COL);

            $plotArea = new PlotArea(null, [$series]);
            $legend = new Legend(Legend::POSITION_RIGHT, null, false);
            $title = new Title('Best Seller (Qty)');

            $chart = new Chart(
                'bestseller_chart',
                $title,
                $legend,
                $plotArea
            );

            $chart->setTopLeftPosition('D3');
            $chart->setBottomRightPosition('L20');

            $sheet3->addChart($chart);
        }

        // set active sheet back
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'FOUR-Report-' . $range . '-' . now()->format('Y-m-d_H-i') . '.xlsx';

        // Output XLSX with charts
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true); // ✅ penting biar chart ikut
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ================= Helpers =================

    private function getRangeDates(string $range): array
    {
        $now = Carbon::now();
        return match ($range) {
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'year'  => [$now->copy()->startOfYear(),  $now->copy()->endOfYear()],
            default => [$now->copy()->startOfDay(),   $now->copy()->endOfDay()],
        };
    }

    private function detectQtyColumn(): string
    {
        if (Schema::hasColumn('order_items', 'qty')) return 'qty';
        if (Schema::hasColumn('order_items', 'quantity')) return 'quantity';
        if (Schema::hasColumn('order_items', 'jumlah')) return 'jumlah';
        abort(500, "Kolom qty tidak ditemukan di tabel order_items. Buat kolom 'qty' atau 'quantity'.");
    }

    private function detectPaidMode(): string
    {
        // kalau ada orders.is_paid gunakan itu, kalau tidak pakai paid_at
        if (Schema::hasColumn('orders', 'is_paid')) return 'is_paid';
        return 'paid_at';
    }

    private function detectOrderTotalColumn(): ?string
    {
        if (Schema::hasColumn('orders', 'total')) return 'total';
        if (Schema::hasColumn('orders', 'subtotal')) return 'subtotal';
        return null;
    }
}
