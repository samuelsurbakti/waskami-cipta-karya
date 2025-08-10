<?php

namespace App\Models\Ref;

use App\Models\Ref\District;
use App\Models\Ref\Province;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Regency extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $table = 'ref_regencies';
    protected $fillable = ['province_id', 'type', 'name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Kota/Kabupaten')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
