<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Comment;
use Artificertech\RelationshipEvents\Tests\Stubs\Post;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class MorphManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphManyCreating_and_morphManyCreated_when_belonged_model_with_morph_many_created()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->create([]);

        Event::assertDispatched(
            'eloquent.commentsCreating: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
        Event::assertDispatched(
            'eloquent.commentsCreated: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManyCreating_and_morphManyCreated_when_createMany_called_on_morphMany_relation()
    {
        Event::fake();

        $post = Post::create();
        $post->comments()->createMany([[], []]);

        Event::assertDispatched(
            'eloquent.commentsCreating: ' . Post::class,
            2
        );
        Event::assertDispatched(
            'eloquent.commentsCreated: ' . Post::class,
            2
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_create_is_canceled()
    {
        $post = Post::create();
        $post->comments()->create(['name' => 'badName']);

        $this->assertEquals(0, $post->comments->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_createMany_is_canceled_on_that_model_only()
    {
        $post = Post::create();
        $post->comments()->createMany([[], ['name' => 'badName']]);

        $this->assertEquals(1, $post->comments->count());
    }

    /** @test */
    public function it_fires_morphManySaving_and_morphManySaved_when_belonged_model_with_morph_many_saved()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->save(new Comment);

        Event::assertDispatched(
            'eloquent.commentsSaving: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
        Event::assertDispatched(
            'eloquent.commentsSaved: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManySaving_and_morphManySaved_when_saveMany_called_on_morphMany_relation()
    {
        Event::fake();

        $post = Post::create();
        $post->comments()->saveMany([new Comment, new Comment]);

        Event::assertDispatched(
            'eloquent.commentsSaving: ' . Post::class,
            2
        );
        Event::assertDispatched(
            'eloquent.commentsSaved: ' . Post::class,
            2
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_save_is_canceled()
    {
        $post = Post::create();
        $post->comments()->save(new Comment(['name' => 'badName']));

        $this->assertEquals(0, $post->comments->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_saveMany_is_canceled_on_that_model_only()
    {
        $post = Post::create();
        $post->comments()->saveMany([new Comment, new Comment(['name' => 'badName'])]);

        $this->assertEquals(1, $post->comments->count());
    }
}
