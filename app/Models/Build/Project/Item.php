<?php

namespace App\Models\Build\Project;

use App\Models\Build\Project;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Item extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'build_project_items';
    protected $fillable = ['project_id', 'type', 'name', 'status', 'client_id', 'land_asset_id', 'building_area', 'land_area', 'number_of_floor', 'front_view_photo'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Komponen Proyek')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
