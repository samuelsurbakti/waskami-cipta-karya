<?php

namespace App\Models\Build;

use App\Models\Ref\Village;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Project extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'build_projects';
    protected $fillable = ['name', 'type', 'cover', 'village_id', 'address', 'client_id', 'start_date', 'end_date', 'description'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Proyek')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
