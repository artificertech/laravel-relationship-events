
# Laravel Relationship Events

Missing relationship events for Laravel

This package was intitally forked from https://github.com/chelout/laravel-relationship-events which is not being actively developed. This package is a different take on the original idea that allows relationship event listeners to be created on a per-relationship basis

This package is still in development. Feel free to contribute by submitting a pull request

<p align="center">
 <a href="https://github.com/artificertech/laravel-relationship-events/actions"><img src="https://github.com/artificertech/laravel-relationship-events/workflows/tests/badge.svg" alt="Build Status"></a>
 <a href="https://packagist.org/packages/artificertech/laravel-relationship-events"><img src="https://poser.pugx.org/artificertech/laravel-relationship-events/d/total.svg" alt="Total Downloads"></a>
 <a href="https://packagist.org/packages/artificertech/laravel-relationship-events"><img src="https://poser.pugx.org/artificertech/laravel-relationship-events/v/stable.svg" alt="Latest Stable Version"></a>
 <a href="https://packagist.org/packages/artificertech/laravel-relationship-events"><img src="https://poser.pugx.org/artificertech/laravel-relationship-events/license.svg" alt="License"></a>
 </p>

## Install

1. Install package with composer

#### Latest Release:
Currently there are no releases for this project as it is still in development.

```
composer require artificertech/laravel-relationship-events
```

#### Development branch:
```
composer require artificertech/laravel-relationship-events:dev-master
```

2. Add the HasRelationshipEvents trait to your model


```php

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    public static function boot()
    {
        parent::boot();

        /**
         * hasOne
         */
        static::hasOneSaved('profile', function ($user, $profile) {
            dump('hasOneSaved', $user, $profile);
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)->withEvents();
    }

}
```

For all saving, attaching, creating, etc events that are fired before the operation takes place you may return false from the event listener to cancel the operation

```php

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    public static function boot()
    {
        parent::boot();

        /**
         * hasMany
         */
        static::hasManyCreating('posts', function ($user, $post) {
            if ($post->name == 'badName') return false;
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents();
    }

}
```

3. Dispatchable relationship events.
It is possible to fire event classes via $dispatchesEvents properties

```php

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    protected $dispatchesEvents = [
        'postsCreating' => UserPostsCreating::class,
        'postsCreated' => UserPostsCreated::class,
        'postsSaving' => UserPostsSaving::class,
        'postsSaved' => UserPostsSaved::class,
    ];

    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents();
    }

}
```


## Observers
Starting from v0.4 it is possible to use relationship events in [Laravel observers classes](https://laravel.com/docs/eloquent#observers) Usage is very simple. Define observer class:

```php
namespace App\Observer;

class UserObserver
{
    /**
     * Handle the User "postsCreating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Post $post
     *
     * @return void
     */
    public function postsCreating(User $user, Post $post)
    {
        Log::info("Creating post: {$post->name} for user {$user->name}.");
    }

    /**
     * Handle the User "postsCreated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Post $post
     *
     * @return void
     */
    public function postsCreated(User $user, Post $post)
    {
        Log::info("Post: {$post->name} for user: {$user->name} has been created.");
    }

    /**
     * Handle the User "postsCreating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Post $post
     *
     * @return void
     */
    public function postsSaving(User $user, Post $post)
    {
        Log::info("Saving post: {$post->name} for user {$user->name}.");
    }

    /**
     * Handle the User "postsCreated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Post $post
     *
     * @return void
     */
    public function postsSaved(User $user, Post $post)
    {
        Log::info("Post: {$post->name} for user: {$user->name} has been saved.");
    }
}
```

Don't forget to register an observer in the ```boot``` method of your ```AppServiceProvider```:
```php
namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
// ...
    public function boot()
    {
        User::observe(UserObserver::class);
    }
// ...
}
```

And now just create profile for user:
```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

// Create profile and assosiate it with user
// This will fire two events hasOneCreating, hasOneCreated
$user->post()->create([
    'name' => 'My first post!',
]);
// ...
```

## Customizing the event name
By default the relationship event name is equal to the relationship function name with the action taking place in camel case. For example if you have a HasOne relationship "profile" then the event names would be "profileCreating", "profileCreated", "profileSaving", "profileSaved".

You may customize the event name by passing the relationship name into the withEvents() function as a string. For example:
```php
class User extends Model
{
    use HasRelationshipEvents;

    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents('userPost');
    }

}
```

will fire "userPostCreating", "userPostCreated", "userPostSaving", "userPostSaved" events

## Relationship Specific info

Each relationship as slightly different events. For example the belongsTo relationship fires {relationship}Associating, {relationship}Associated, {relationship}Dissociating, and {relationship}Dissociated events

- [Belongs To](doc/belongs-to.md)
- [Has Many](doc/has-many.md)
- [Has One](doc/has-one.md)
- [Morph Many](doc/morph-many.md)
- [Morph One](doc/morph-one.md)
- [Morph To](doc/morph-to.md)

## Todo
 - Fix Automated Tests
 - Add documentation for ManyToMany type events (these events can be handled by the built in pivot models and do not need this package)
 - Non-Default event name tests
 - Observer Tests
 - Event Dispatcher Tests
 - Event Listener Exception Tests
 - HasOneThrough & HasManyThrough
 - New HasOneOfMany relationship?
