<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\ObserverProfile;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\Stubs\UserObserver;
use Artificertech\RelationshipEvents\Tests\TestCase;

class RelationshipEventObserverTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::observe(UserObserver::class);
        User::setupTable();
        ObserverProfile::setupTable();
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_observer_then_the_create_is_canceled()
    {
        $user = User::create();
        $user->observerProfile()->create(['name' => 'badName']);

        $this->assertEquals(null, $user->observerProfile);
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_observer_then_the_save_is_canceled()
    {
        $user = User::create();
        $user->observerProfile()->save(new ObserverProfile(['name' => 'badName']));

        $this->assertEquals(null, $user->observerProfile);
    }
}
