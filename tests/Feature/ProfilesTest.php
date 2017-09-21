<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test a user has a profile */
    function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }
    
    /** @test profiles display all threads created by the associated user */
    function profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->get('/profiles/' . auth()->user()->name)
            ->assertSee($thread->title);
    }
}
