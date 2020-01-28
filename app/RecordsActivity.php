<?php

namespace App;

trait RecordsActivity
{
    public $old = [];


    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) == 'Project' ? $this->id : $this->project->id,
        ]);
    }

    protected function activityChanges()
    {
        if($this->wasChanged())
        {
            return [
                'before' => array_except(array_diff($this->old, $this->getAttributes()), ['updated_at']),
                'after' => array_except($this->getChanges(), ['updated_at']),
            ];
        }
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
