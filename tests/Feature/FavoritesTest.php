<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test guests can not favorite anything */
    function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test an authenticated user can favorite any reply */
    function an_authenticated_user_can_favorite_any_reply()
    {

        $this->signIn();

        // The model factory will automatically create a thread for this reply.
        $reply = create('App\Reply');

        // post to this URI to favorite $reply for authenticated user.
        $this->post('replies/' . $reply->id . '/favorites');

        // Make sure the created reply has one favorite as relation.
        $this->assertCount(1, $reply->favorites);
    }

    /** @test an authenticated user can unfavorite any reply */
    function an_authenticated_user_can_unfavorite_a_reply()
    {

        $this->signIn();

        $reply = create('App\Reply');

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);
    }

    /** @test an authenticated user may only favorite a reply once */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
