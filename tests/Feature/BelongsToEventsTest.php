<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Profile;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class BelongsToEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_belongsToAssociating_and_belongsToAssociated_when_a_model_associated()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());

        Event::assertDispatched(
            'eloquent.userAssociating: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($profile) && $callback[1]->is($user);
            }
        );
        Event::assertDispatched(
            'eloquent.userAssociated: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($profile) && $callback[1]->is($user);
            }
        );
    }

    /** @test */
    public function it_fires_belongsToDissociating_and_belongsToDissociated_when_a_model_dissociated()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());
        $profile->user()->dissociate();

        Event::assertDispatched(
            'eloquent.userDissociating: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($profile) && $callback[1]->is($user);
            }
        );
        Event::assertDispatched(
            'eloquent.userDissociated: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($profile) && $callback[1]->is($user);
            }
        );
    }
}
