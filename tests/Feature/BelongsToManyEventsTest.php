<?php

namespace Artificertech\RelationshipEvents\Tests\Feature;

use Artificertech\RelationshipEvents\Tests\Stubs\Role;
use Artificertech\RelationshipEvents\Tests\Stubs\User;
use Artificertech\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class BelongsToManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Role::setupTable();
    }

    /** @test */
    public function it_fires_belongsToManySaving_and_belongsToManySaved_when_a_related_model_is_saved()
    {
        Event::fake();

        $user = User::create();
        $role = new Role(['name' => 'admin']);
        $user->roles()->save($role);

        Event::assertDispatched(
            'eloquent.rolesSaving: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0]->is($user) && $callback[1]->is($role) && is_array($callback[2]);
            }
        );
        Event::assertDispatched(
            'eloquent.rolesSaved: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0]->is($user) && $callback[1]->is($role) && is_array($callback[2]);
            }
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_save_is_canceled()
    {
        $user = User::create();
        $user->roles()->save(new Role(['name' => 'goodName']), ['note' => 'goodNote']);
        $user->roles()->save(new Role(['name' => 'goodName']), ['note' => 'badNote']);
        $user->roles()->save(new Role(['name' => 'badName']), ['note' => 'goodNote']);
        $user->roles()->save(new Role(['name' => 'badName']), ['note' => 'badNote']);

        $this->assertEquals(1, $user->roles->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_saving_event_then_the_saveMany_is_canceled_on_that_model_only()
    {
        $user = User::create();
        $user->roles()->saveMany(
            [
                new Role(['name' => 'admin']),
                new Role(['name' => 'admin']),
                new Role(['name' => 'badName']),
                new Role(['name' => 'badName']),
            ],
            [
                ['note' => 'goodNote'],
                ['note' => 'badNote'],
                ['note' => 'goodNote'],
                ['note' => 'badNote'],
            ]
        );

        $this->assertEquals(1, $user->roles->count());
    }

    /** @test */
    public function it_fires_belongsToManyCreatng_and_belongsToManyCreated_when_a_related_model_is_created()
    {
        Event::fake();

        $user = User::create();
        $role = $user->roles()->create(['name' => 'admin']);

        Event::assertDispatched(
            'eloquent.rolesCreating: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0]->is($user) && $callback[1]->is($role) && is_array($callback[2]);
            }
        );
        Event::assertDispatched(
            'eloquent.rolesCreated: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0]->is($user) && $callback[1]->is($role) && is_array($callback[2]);
            }
        );
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_create_is_canceled()
    {
        $user = User::create();
        $user->roles()->create(['name' => 'goodName'], ['note' => 'goodNote']);
        $user->roles()->create(['name' => 'goodName'], ['note' => 'badNote']);
        $user->roles()->create(['name' => 'badName'], ['note' => 'goodNote']);
        $user->roles()->create(['name' => 'badName'], ['note' => 'badNote']);

        $this->assertEquals(1, $user->roles->count());
    }

    /** @test */
    public function if_false_is_returned_from_the_creating_event_then_the_createMany_is_canceled_on_that_model_only()
    {
        $user = User::create();
        $user->roles()->createMany(
            [
                ['name' => 'admin'],
                ['name' => 'admin'],
                ['name' => 'badName'],
                ['name' => 'badName'],
            ],
            [
                ['note' => 'goodNote'],
                ['note' => 'badNote'],
                ['note' => 'goodNote'],
                ['note' => 'badNote'],
            ]
        );

        $this->assertEquals(1, $user->roles->count());
    }

    // /** @test */
    // public function it_fires_belongsToManyDetaching_and_belongsToManyDetached_when_a_model_detached()
    // {
    //     Event::fake();

    //     $user = User::create();
    //     $role = Role::create(['name' => 'admin']);
    //     $user->roles()->attach($role);
    //     $user->roles()->detach($role);

    //     Event::assertDispatched(
    //         'eloquent.belongsToManyDetaching: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    //     Event::assertDispatched(
    //         'eloquent.belongsToManyDetached: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    // }

    // /** @test */
    // public function it_fires_belongsToManySyncing_and_belongsToManySynced_when_a_model_synced()
    // {
    //     Event::fake();

    //     $user = User::create();
    //     $role = Role::create(['name' => 'admin']);
    //     $user->roles()->sync($role);

    //     Event::assertDispatched(
    //         'eloquent.belongsToManySyncing: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    //     Event::assertDispatched(
    //         'eloquent.belongsToManySynced: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    // }

    // /** @test */
    // public function it_fires_belongsToManyToggling_and_belongsToManyToggled_when_a_model_toggled()
    // {
    //     Event::fake();

    //     $user = User::create();
    //     $role = Role::create(['name' => 'admin']);
    //     $user->roles()->toggle($role);

    //     Event::assertDispatched(
    //         'eloquent.belongsToManyToggling: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    //     Event::assertDispatched(
    //         'eloquent.belongsToManyToggled: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    // }

    // /** @test */
    // public function it_fires_belongsToManyUpdatingExistingPivot_and_belongsToManyUpdatedExistingPivot_when_updaing_pivot_table()
    // {
    //     Event::fake();

    //     $user = User::create();
    //     $role = Role::create(['name' => 'admin']);
    //     $user->roles()->attach($role);
    //     $user->roles()->updateExistingPivot(1, ['note' => 'bla bla']);

    //     Event::assertDispatched(
    //         'eloquent.belongsToManyUpdatingExistingPivot: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    //     Event::assertDispatched(
    //         'eloquent.belongsToManyUpdatedExistingPivot: ' . User::class,
    //         function ($event, $callback) use ($user, $role) {
    //             return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
    //         }
    //     );
    // }
}
