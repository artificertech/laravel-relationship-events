<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Profile;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasOneEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_hasOneCreating_and_hasOneCreated_when_a_belonged_model_created()
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->create([]);

        Event::assertDispatched(
            'eloquent.profileCreating: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
        Event::assertDispatched(
            'eloquent.profileCreated: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_create_is_canceled()
    {
        $user = User::create();
        $user->profile()->create(['name' => 'badName']);

        $this->assertEquals(null, $user->profile);
    }

    /** @test */
    public function it_fires_hasOneSaving_and_hasOneSaved_when_a_belonged_model_saved()
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->save(new Profile);

        Event::assertDispatched(
            'eloquent.profileSaving: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
        Event::assertDispatched(
            'eloquent.profileSaved: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_save_is_canceled()
    {
        $user = User::create();
        $user->profile()->save(new Profile(['name' => 'badName']));

        $this->assertEquals(null, $user->profile);
    }
}
