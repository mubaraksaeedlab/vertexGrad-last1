<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'permission_name',
        'status',
    ];

    // كل صلاحية مرتبطة بمدير واحد
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
