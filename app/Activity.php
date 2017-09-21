<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    /**
     *  It results the morph relation as an object of it's own type
     *
     */
    public function subject()
    {
       return $this->morphTo();
    }

    public static function feed($user, $limit = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->with('subject')
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
