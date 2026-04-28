<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorNote extends Model
{
    protected $fillable = ['investor_id','user_id','note'];

    public function investor() { return $this->belongsTo(Investor::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
