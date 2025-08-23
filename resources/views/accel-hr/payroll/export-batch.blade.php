@php
    use Carbon\Carbon;
    use Illuminate\Support\Number;
@endphp

<!DOCTYPE html>
<html>
<head>
    <style>
        @font-face {
            font-family: 'Roboto';
            font-weight: normal;
            font-style: normal;
            src: url('{{ storage_path("app/fonts/Roboto-Regular.ttf") }}') format("truetype");
        }
        @font-face {
            font-family: 'Roboto';
            font-weight: bold;
            font-style: normal;
            src: url('{{ storage_path("app/fonts/Roboto-Bold.ttf") }}') format("truetype");
        }
        @font-face {
            font-family: 'Roboto';
            font-weight: normal;
            font-style: italic;
            src: url('{{ storage_path("app/fonts/Roboto-Italic.ttf") }}') format("truetype");
        }

        body { font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .container { width: 100%; border: 1px solid #ddd; padding: 15px; box-sizing: border-box; margin-bottom: 20px; page-break-inside: avoid;}
        .header { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #eee; }
        .header h3 { margin: 0; padding: 0; font-size: 1.2em; }
        .header p { margin: 0; padding: 0; color: #666; font-size: 0.9em; }
        .header .status { float: right; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; color: white; }
        .status.draft { background-color: #28a745; }
        .status.completed { background-color: #6c757d; }

        /* Gaya untuk tabel */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-data th, .table-data td { padding: 8px; text-align: left; }
        .table-data .total-row { font-weight: bold; border-top: 1px solid #ddd; }
        .table-data .border-bottom td { border-bottom: 1px solid #eee; }

        /* Gaya teks */
        .text-info { color: #17a2b8; }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
        .text-wck { color: #C6993A; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .fw-medium { font-weight: 500; }

    </style>
</head>
<body>
    @php
        $total_payroll = 0;
    @endphp
    @foreach ($payrolls as $payroll)
        <div class="container">
            <div class="header">
                <h3 style="display:inline-block; margin-right: 15px;">{{ $payroll->contract->relation->name }}</h3>
                <span class="status completed">
                    {{ Carbon::parse($payroll->start_date)->isoFormat('DD MMMM YYYY') }} - {{ Carbon::parse($payroll->end_date)->isoFormat('DD MMMM YYYY') }}
                </span>
                <p>{{ $payroll->contract->title }}</p>
            </div>

            @if ($payroll->attendanceItems->count() != 0)
            <h5 style="margin-top: 0; margin-bottom: 10px;">Data Kehadiran</h5>
            <table class="table-data">
                <thead>
                    <tr style="font-weight: bold;">
                        <td style="width: 40%;">Tanggal</td>
                        <td style="width: 15%; text-align: right;">Rate</td>
                        <td style="width: 15%; text-align: right;">Lembur</td>
                        <td style="width: 15%; text-align: right;">Potongan</td>
                        <td style="width: 15%; text-align: right;">Jumlah</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payroll->attendanceItems as $attendance)
                    <tr>
                        <td>{{ Carbon::parse($attendance->relation->date)->isoFormat('DD MMMM YYYY') }}</td>
                        <td style="text-align: right;">{{ Number::currency($attendance->relation->contract->rates ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</td>
                        <td class="text-success" style="text-align: right;">{{ Number::currency($attendance->relation->overtime_rates ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</td>
                        <td class="text-warning" style="text-align: right;">{{ Number::currency($attendance->relation->docking_pay ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</td>
                        <td class="text-info" style="text-align: right;">{{ Number::currency($attendance->amount, in: 'IDR', locale: 'id', precision: 0) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td class="text-success" colspan="4">Total</td>
                        <td class="text-success" style="text-align: right;">{{ Number::currency($payroll->attendanceItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            @if ($payroll->repaymentItems->count() != 0)
            <h5 style="margin-top: 10px; margin-bottom: 10px;">Pembayaran Pinjaman</h5>
            <table class="table-data">
                <thead>
                    <tr style="font-weight: bold;">
                        <td>Nomor Pinjaman</td>
                        <td style="text-align: right;">Jumlah</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payroll->repaymentItems as $repayment)
                    <tr>
                        <td>{{ $repayment->relation->loan->loan_number }}</td>
                        <td class="text-danger" style="text-align: right;">{{ Number::currency($repayment->amount ?? 0, in: 'IDR', locale: 'id', precision: 0) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td class="text-danger">Total</td>
                        <td class="text-danger" style="text-align: right;">{{ Number::currency($payroll->repaymentItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            <table class="table-data" style="border-top: 3px solid #ddd; margin-top: 35px;">
                <tr style="font-weight: bold;">
                    <td class="text-wck">Gaji Bersih</td>
                    <td class="text-wck" style="text-align: right;">{{ Number::currency($payroll->attendanceItems()->sum('amount') - $payroll->repaymentItems()->sum('amount'), in: 'IDR', locale: 'id', precision: 0) }}</td>
                </tr>
            </table>
        </div>
        @php
            $total_payroll += $payroll->attendanceItems()->sum('amount') - $payroll->repaymentItems()->sum('amount');
        @endphp
    @endforeach

    <div class="container">
        <h4 style="margin-top: 20px;">
            Total Gaji: {{ Number::currency($total_payroll, in: 'IDR', locale: 'id', precision: 0) }}
        </h4>
    </div>
</body>
</html>
