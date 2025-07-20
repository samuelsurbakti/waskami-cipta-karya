<?php

namespace App\Models\Sys;

use App\Models\Sys\App;
use App\Models\SLP\Permission;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Menu extends Model
{
    use HasUuids, LogsActivity, SoftDeletes;

    protected $table = 'sys_menus';
    protected $fillable = ['app_id', 'title', 'icon', 'url', 'source', 'order_number', 'parent', 'member_of'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('Menu')
            ->setDescriptionForEvent(fn(string $eventName) => ($eventName == 'created' ? 'Menambah' : ($eventName == 'updated' ? 'Mengubah' : 'Menghapus')))
            ->dontLogIfAttributesChangedOnly(['created_at', 'updated_at', 'deleted_at']);
    }

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id');
    }

    public function get_parent()
    {
        return $this->belongsTo(Menu::class, 'parent');
    }

    public function get_child()
    {
        return $this->hasMany(Menu::class, 'parent')->orderBy('order_number');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id')->where('type', 'Permission')->orderBy('number', 'asc');
    }
}
