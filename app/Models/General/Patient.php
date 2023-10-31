<?php

namespace App\Models\General;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'team_id',
        'identification',
        'first_name',
        'last_name',
        'birth_day',
        'address',
        'phone',
        'email',
        'city'
    ];
    protected $casts = [
        'birth_day' => 'datetime:Y-m-d'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
