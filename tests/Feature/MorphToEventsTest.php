<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Comment;
use Artificertech\RelationshipEvents\Tests\Stubs\Post;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class MorphToEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphToAssociating_and_morphToAssociated()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);

        Event::assertDispatched(
            'eloquent.postAssociating: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($comment) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.postAssociated: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($comment) && $callback[1]->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_morphToDissociating_and_morphToDissociated()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);
        $comment->post()->dissociate($post);

        Event::assertDispatched(
            'eloquent.postDissociating: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($comment) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.postDissociated: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($comment) && $callback[1]->is($post);
            }
        );
    }
}
