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
}
