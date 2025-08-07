<?php

namespace App\Models\Hr;

use App\Models\Hr\Contract;
use App\Models\Hr\Team\Member;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Team extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_teams';
    protected $fillable = ['name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Tim')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'team_id');
    }

    public function contracts()
    {
        return $this->morphMany(Contract::class, 'relation');
    }
}
