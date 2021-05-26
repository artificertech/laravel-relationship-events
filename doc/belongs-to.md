# Belongs To Relations:

```Profile``` model might belong to one ```User```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the user associated with the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withEvents();
    }
}
```

Now we can use methods to associate ```Profile``` with ```User``` and also update assosiated model.

```php
// ...
$profile = Profile::create([
    'phone' => '8-800-123-45-67',
    'email' => 'user@example.com',
    'address' => 'One Infinite Loop Cupertino, CA 95014',
]);

$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

$profile->user()->associate($user);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::belongsToAssociating('user', function ($profile, $user) {
            Log::info("Associating profile: {$profile->name} with user {$user->name}.");
        });

        static::belongsToAssociated('user', function ($parent, $user) {
            Log::info("Profile: {$profile->name} for user: {$user->name} has been associated.");
        });

        static::belongsToDissociating('user', function ($profile, $user) {
            Log::info("Dissociating profile: {$profile->name} with user {$user->name}.");
        });

        static::belongsToDissociated('user', function ($profile, $user) {
            Log::info("Profile: {$profile->name} for user: {$user->name} has been dissociated.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'userAssociating' => ProfileUserAssociating::class,
        'userAssociated' => ProfileUserAssociated::class,
        'userDissociating' => ProfileUserDissociating::class,
        'userDissociated' => ProfileUserDissociated::class,
    ];
// ...
```

BROKEN FUNCTIONALITY

Curently the observer functionality is broken. This is my current priority and the below documentation explains how the observers will eventually work. This functionality did has not transitioned from the original package yet

Or you may use an Observer
```php
namespace App\Observer;

class ProfileObserver
{
    /**
     * Handle the User "userAssociating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function userAssociating(Profile $profile, User $user)
    {
        Log::info("Associating profile: {$profile->name} with user {$user->name}.");
    }

    /**
     * Handle the User "userAssociated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function userAssociated(Profile $profile, User $user)
    {
        Log::info("Profile: {$profile->name} for user: {$user->name} has been associated.");
    }

    /**
     * Handle the User "userDissociating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function userDissociating(Profile $profile, User $user)
    {
        Log::info("Dissociating profile: {$profile->name} with user {$user->name}.");
    }

    /**
     * Handle the User "userDissociated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function userDissociated(Profile $profile, User $user)
    {
        Log::info("Profile: {$profile->name} for user: {$user->name} has been dissociated.");
    }
}
```

### Available methods and events

#### BelongsTo::associate
- fires {relationship}Associating, {relationship}Associated
- events have $parent and $related models

#### BelongsTo::dissociate
- fires {relationship}Dissociating, {relationship}Dissociated
- events have $parent and $related models