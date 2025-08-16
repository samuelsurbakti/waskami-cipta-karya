<?php

namespace App\Helpers;

use App\Models\Hr\Attendance;
use App\Models\Hr\Contract;
use App\Models\Hr\Loan;
use Carbon\Carbon;

class PayrollCalculatorHelper
{
    public static function calculate($payCycle, $startDate = null, $endDate = null)
    {
        if (! $startDate || ! $endDate) {
            [$startDate, $endDate] = self::generateRange($payCycle);
        }

        $contracts = Contract::where('pay_cycle', $payCycle)->get();

        $result = [];

        foreach ($contracts as $contract) {
            $attendances = Attendance::where('contract_id', $contract->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $progresses = [];
            // $progresses = $contract->worker->progresses()
            //     ->whereBetween('date', [$startDate, $endDate])
            //     ->get();

            $salary = self::sumFromAttendancesAndProgresses($attendances, $progresses);

            $result[] = [
                'contract_id' => $contract->id,
                'worker_name' => $contract->relation->name,
                'total_salary' => $salary,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
        }

        return $result;
    }

    public static function generateRange($payCycle)
    {
        $today = now();

        return match ($payCycle) {
            'weekly' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()->subDay()],
            'monthly' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
        };
    }

    public static function get_contract($contract_id)
    {
        $contract = Contract::find($contract_id);
        return $contract;
    }

    public static function get_attendance_amount_by_contract_date($contract_id, $date)
    {
        $attendance = Attendance::where('contract_id', $contract_id)->where('date', $date)->with('contract')->first();

        return $attendance;
    }

    public static function get_loan($worker_id)
    {
        $loan = Loan::where('worker_id', $worker_id)->first();
        return $loan;
    }

    protected static function sumFromAttendancesAndProgresses($attendances, $progresses)
    {
        // Dummy logic:
        $payroll_amount = 0;

        foreach ($attendances as $attendance) {
            $payroll_amount += $attendance->contract->rates + $attendance->overtime_rates - $attendance->docking_pay;
        }

        return $payroll_amount;
    }
}
