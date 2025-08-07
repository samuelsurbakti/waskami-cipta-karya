<?php

namespace App\Models\Hr;

use App\Models\Hr\Contract;
use App\Models\Hr\Worker\Type;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Worker extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_workers';
    protected $fillable = ['type_id', 'name', 'phone', 'whatsapp', 'address'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Pekerja')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function contracts()
    {
        return $this->morphMany(Contract::class, 'relation');
    }
}
