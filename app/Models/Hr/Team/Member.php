<?php

namespace App\Models\Hr\Team;

use App\Models\Hr\Team;
use App\Models\Hr\Worker;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Member extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'hr_team_members';
    protected $fillable = ['team_id', 'worker_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Anggota Tim')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
