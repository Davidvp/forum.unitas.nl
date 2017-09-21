<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 18-Sep-17
 * Time: 10:48
 */

namespace App;


trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        // This is just for testing, as we just create and not login while testing in some cases.
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model) {
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type =  strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}