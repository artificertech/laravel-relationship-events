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
    public function it_fires_hasManyCreating_and_hasManyCrated_when_createMany_called_on_hasMany_relation()
    {
        Event::fake();

        $user = User::create();
        $posts = $user->posts()->createMany([[], []]);

        Event::assertDispatched(
            'eloquent.postsCreating: ' . User::class,
            2
        );
        Event::assertDispatched(
            'eloquent.postsCreated: ' . User::class,
            2
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_create_is_canceled()
    {
        $user = User::create();
        $post = $user->posts()->create(['name' => 'badName']);

        $this->assertEquals(0, $user->posts->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_createMany_is_canceled_on_that_model_only()
    {
        $user = User::create();
        $post = $user->posts()->createMany([[], ['name' => 'badName']]);

        $this->assertEquals(1, $user->posts->count());
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

    /** @test */
    public function it_fires_hasManySaving_and_hasManySaved_when_saveMany_called_on_hasMany_relation()
    {
        Event::fake();

        $user = User::create();
        $posts = $user->posts()->saveMany([new Post, new Post()]);

        Event::assertDispatched(
            'eloquent.postsSaving: ' . User::class,
            2
        );
        Event::assertDispatched(
            'eloquent.postsSaved: ' . User::class,
            2
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_save_is_canceled()
    {
        $user = User::create();
        $post = $user->posts()->save(new Post(['name' => 'badName']));

        $this->assertEquals(0, $user->posts->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_saveMany_is_canceled_on_that_model_only()
    {
        $user = User::create();
        $post = $user->posts()->saveMany([new Post, new Post(['name' => 'badName'])]);

        $this->assertEquals(1, $user->posts->count());
    }
}
