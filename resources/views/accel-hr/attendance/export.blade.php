<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran</title>
    <style>
        /* ... CSS di sini tetap sama seperti sebelumnya ... */
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .text-center {
            text-align: center;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #C6993A;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #C6993A;
            color: #FFF;
            font-weight: bold;
            white-space: nowrap;
        }

        .text-info { color: #17a2b8; }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
        .text-wck { color: #C6993A; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Laporan Kehadiran</h2>
        <h4 class="text-center">Periode: {{ $start_date->isoFormat('DD MMMM YYYY') }} - {{ $end_date->isoFormat('DD MMMM YYYY') }}</h4>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 150px;"></th>
                        @foreach (\Carbon\CarbonPeriod::create($start_date, $end_date) as $date)
                            <th class="text-center">{{ $date->isoFormat('dddd, D MMMM YYYY') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($workers as $worker_name)
                        @php
                            $rates_row = [];
                            $overtime_row = [];
                            $docking_row = [];
                            $date_period = \Carbon\CarbonPeriod::create($start_date, $end_date);
                        @endphp

                        <tr>
                            <td colspan="{{ $date_period->count() + 1 }}" style="font-weight: bold; background-color: #f6eede; color: #C6993A;">
                                {{ $worker_name }}
                            </td>
                        </tr>

                        @foreach ($date_period as $date)
                            @php
                                // Mengakses data langsung dari koleksi yang sudah di-keyBy
                                $key = $worker_name . '|' . $date->format('Y-m-d');
                                $attendance = $attendances[$key] ?? null;

                                $rates = $attendance && $attendance->contract ? number_format($attendance->contract->rates, 0, ',', '.') : '-';
                                $overtime = $attendance ? number_format($attendance->overtime_rates, 0, ',', '.') : '-';
                                $docking = $attendance ? number_format($attendance->docking_pay, 0, ',', '.') : '-';

                                $rates_row[] = $rates;
                                $overtime_row[] = $overtime;
                                $docking_row[] = $docking;
                            @endphp
                        @endforeach

                        <tr>
                            <td class="text-info">Nilai Kontrak</td>
                            @foreach($rates_row as $rate)
                                <td class="text-center text-info">{{ $rate }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="text-success">Upah Lembur</td>
                            @foreach($overtime_row as $overtime)
                                <td class="text-center text-success">{{ $overtime }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="text-danger">Potongan Upah</td>
                            @foreach($docking_row as $docking)
                                <td class="text-center text-danger">{{ $docking }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
