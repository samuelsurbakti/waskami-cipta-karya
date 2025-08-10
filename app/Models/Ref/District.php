<?php

namespace App\Models\Ref;

use App\Models\Ref\Regency;
use App\Models\Ref\Village;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class District extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $table = 'ref_districts';
    protected $fillable = ['regency_id', 'name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Kecamatan')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }
}
