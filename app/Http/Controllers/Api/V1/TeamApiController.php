<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamApiController extends Controller
{
    public function index($userId)
    {
        return Team::where(function ($query) use ($userId) {
            $query->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })->orWhere('user_id', $userId);
        })
            ->get();
    }
}
