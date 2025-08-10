<?php

namespace App\Models\Ref;

use App\Models\Ref\Regency;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Province extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $table = 'ref_provinces';
    protected $fillable = ['name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Provinsi')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function regencies()
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
