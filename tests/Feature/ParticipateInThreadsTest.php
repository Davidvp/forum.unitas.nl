<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Make and be an Authenticated user
        $this->signIn();

        // Make new Thread to Post a reply to
        $thread = create('App\Thread');

        $reply = create('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    function an_unauthenticated_user_may_not_participate_in_forum_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('login');

    }

    /** @test a reply requires a body */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test unauthorized users cannot delete replies */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);

    }
    
    /** @test authorized users can delete replies */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }
    
    /** @test authorized users can update replies */
    function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'You have been changed!';
        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test unauthorized users cannot update replies */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);

    }
}
