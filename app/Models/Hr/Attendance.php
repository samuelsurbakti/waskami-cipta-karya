<?php

namespace App\Models\Hr;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Attendance extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_attendances';
    protected $fillable = ['contract_id', 'date', 'start_time', 'start_photo', 'end_time', 'end_photo', 'overtime_rates', 'docking_pay'];
    protected $casts = [
        'date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Presensi')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
