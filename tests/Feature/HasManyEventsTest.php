<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Post;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Post::setupTable();
    }

    /** @test */
    public function it_fires_hasManyCreating_and_hasManyCreated_when_belonged_model_with_many_created()
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->create([]);

        Event::assertDispatched(
            'eloquent.postsCreating: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.postsCreated: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_hasManySaving_and_hasManySaved_when_belonged_model_with_many_saved()
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->save(new Post);

        Event::assertDispatched(
            'eloquent.postsSaving: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.postsSaved: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
    }
}
