<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];


    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $task = $this->tasks()->create(compact('body'));

    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

}
