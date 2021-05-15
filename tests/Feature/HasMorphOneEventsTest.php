<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Address;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphOneEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Address::setupTable();
    }

    /** @test */
    public function it_fires_morphOneCreating_and_morphOneCreated_when_belonged_model_with_morph_one_created()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->create([]);

        Event::assertDispatched(
            'eloquent.morphOneCreating: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
        Event::assertDispatched(
            'eloquent.morphOneCreated: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
    }

    /** @test */
    public function it_fires_morphOneSaving_and_morphOneSaved_when_belonged_model_with_morph_one_saved()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->save(new Address);

        Event::assertDispatched(
            'eloquent.morphOneSaving: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
        Event::assertDispatched(
            'eloquent.morphOneSaved: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
    }

    /** @test */
    public function it_fires_morphOneUpdating_and_morphOneUpdated_when_belonged_model_with_morph_one_updated()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->save(new Address);
        $user->address()->update([]);

        Event::assertDispatched(
            'eloquent.morphOneUpdating: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
        Event::assertDispatched(
            'eloquent.morphOneUpdated: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
    }
}
