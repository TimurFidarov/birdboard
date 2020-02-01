<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Project;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_have_projects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    public function test_it_can_access_projects_it_has_been_invited_to()
    {
        $projectOwner = factory(User::class)->create();

        $john = factory(User::class)->create();

        $kate = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $projectOwner->id]);

        $project->invite($kate);

        $this->assertCount(0, $john->accessibleProjects());

        $this->assertCount(1, $kate->accessibleProjects());
    }
}
