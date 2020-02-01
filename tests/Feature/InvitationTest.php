<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Project;
use Facades\Tests\Setup\ProjectFactory;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();


        $project->invite($newUser = factory(\App\User::class)->create());

        $this->signIn($newUser);

        $this->post($project->path() . '/tasks', $attr = ['body' => 'newUser task']);

        $this->assertDatabaseHas('tasks', $attr);
    }
}
