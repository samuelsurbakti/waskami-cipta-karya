<?php

namespace App\Models\Hr;

use App\Models\Hr\Attendance;
use App\Models\Hr\Payroll\Item;
use App\Models\Hr\Loan\Repayment;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payroll extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_payrolls';
    protected $fillable = ['contract_id', 'start_date', 'end_date', 'status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Gaji')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'payroll_id');
    }

    public function attendanceItems()
    {
        return $this->hasMany(Item::class, 'payroll_id')->where('relation_type', Attendance::class);
    }

    public function repaymentItems()
    {
        return $this->hasMany(Item::class, 'payroll_id')->where('relation_type', Repayment::class);
    }
}
