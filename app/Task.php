<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Project;

class Task extends Model
{

    protected $guarded = [];
    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return '/projects/' . $this->project->id . '/tasks/' . $this->id;
    }
}
