<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Project;
use App\User;
use Facades\Tests\Setup\ProjectFactory;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();

        $user = factory(User::class)->create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($user)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email
        ])->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email
        ])->assertStatus(403);
    }

    public function test_only_owner_of_the_project_can_invite_others()
    {
        $project = ProjectFactory::create();

        $user = factory(User::class)->create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($user)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email
        ])->assertStatus(403);
    }

    public function test_a_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => $userToInvite->email
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }


    public function test_the_invited_email_address_must_be_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'notbirdboardaccout@mail.com'
            ])
            ->assertSessionHasErrors(['email' => 'The user you are inviting must have a Birdboard account.'], null, 'invitations');
    }

    public function test_invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();


        $project->invite($newUser = factory(\App\User::class)->create());

        $this->signIn($newUser);

        $this->post($project->path() . '/tasks', $attr = ['body' => 'newUser task']);

        $this->assertDatabaseHas('tasks', $attr);
    }
}

