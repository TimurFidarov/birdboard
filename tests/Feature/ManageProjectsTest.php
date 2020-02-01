<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ManageProjectsTest extends TestCase
{
   use RefreshDatabase, WithFaker;

/** @test */

    public function test_guest_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();
        $this->post('projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */

    public function test_a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'Genereal notes here'
        ];
        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    public function test_a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));

    }

    public function test_a_user_cant_delete_projects_of_others()
    {
        $project = ProjectFactory::create();

        $this->actingAs($this->signIn())
            ->delete($project->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('projects', $project->only('id'));

    }


    public function test_a_user_can_see_project_his_accessible_projects()
    {
        $projectOwner = factory('App\User')->create();

        $invitedUser = $this->signIn();

        $project = factory(Project::class)->create(['owner_id' => $projectOwner->id]);

        $project->invite($invitedUser);

        $this->get('/projects')->assertSee($project->title);
    }


    public function test_a_user_can_update_his_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attr = ['title' => 'Changed','description' =>'Changed' ,'notes' => 'Changed'])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attr);
    }

    public function test_a_user_can_update_his_general_notes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attr = ['notes' => 'Changed']);

        $this->assertDatabaseHas('projects', $attr);
    }

    public function test_an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->patch($project->path())
            ->assertStatus(403);
    }

    public function test_a_user_can_view_his_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(\Illuminate\Support\Str::limit($project->description,100));
    }

    /** @test */

    public function test_an_authenticated_user_cannot_view_the_projects_of_others()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }


    public function test_a_project_requires_a_title()
    {

        $this->signIn();

        $attributes = factory('App\Project')->raw(['title'=>'']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description'=>'']);
        $this->post('projects', $attributes)->assertSessionHasErrors('description');
    }

}
