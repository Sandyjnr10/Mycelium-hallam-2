<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamContact extends Model
{
    use HasFactory;

    protected $fillable = ['team_id',
    'handle',
    'website'];

    public function team() {
        return $this->belongsTo('App\Models\Team');
    }
}
