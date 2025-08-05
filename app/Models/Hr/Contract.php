<?php

namespace App\Models\Hr;

use App\Models\Hr\Contract\Type;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contract extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_contracts';
    protected $fillable = ['title', 'relation_type', 'relation_id', 'project_id', 'type_id', 'rates', 'start_date', 'end_date'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Pekerja')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function relation()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
