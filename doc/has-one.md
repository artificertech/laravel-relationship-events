# Has One Relations:

```User``` model might be associated with one ```Profile```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class)->withEvents();
    }
}
```

Now we can use methods to assosiate ```User``` with ```Profile``` and also update associated model.

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

$user->profile()->create([
    'phone' => '8-800-123-45-67',
    'email' => 'user@example.com',
    'address' => 'One Infinite Loop Cupertino, CA 95014',
]);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::hasOneCreating('profile', function ($user, $profile) {
            Log::info("Creating profile: {$profile->name} for user {$user->name}.");
        });

        static::hasOneCreated('profile', function ($user, $profile) {
            Log::info("Profile: {$profile->name} for user: {$user->name} has been created.");
        });

        static::hasOneSaving('profile', function ($user, $profile) {
            Log::info("Saving profile: {$profile->name} for user {$user->name}.");
        });

        static::hasOneSaved('profile', function ($user, $profile) {
            Log::info("Profile: {$profile->name} for user: {$user->name} has been saved.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'profileCreating' => UserProfileCreating::class,
        'profileCreated' => UserProfileCreated::class,
        'profileSaving' => UserProfileSaveing::class,
        'profileSaving' => UserProfileSaving::class,
    ];
// ...
```

BROKEN FUNCTIONALITY

Curently the observer functionality is broken. This is my current priority and the below documentation explains how the observers will eventually work. This functionality did has not transitioned from the original package yet

Or you may use an Observer
```php
namespace App\Observer;

class UserObserver
{
    /**
     * Handle the User "profileCreating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function profileCreating(User $user, Profile $profile)
    {
        Log::info("Creating profile: {$profile->name} for user {$user->name}.");
    }

    /**
     * Handle the User "profileCreated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function profileCreated(User $user, Profile $profile)
    {
        Log::info("Profile: {$profile->name} for user: {$user->name} has been created.");
    }

    /**
     * Handle the User "profileSaving" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function profileSaving(User $user, Profile $profile)
    {
        Log::info("Saving profile: {$profile->name} for user {$user->name}.");
    }

    /**
     * Handle the User "profileSaved" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Profile $profile
     *
     * @return void
     */
    public function profileSaved(User $user, Profile $profile)
    {
        Log::info("Profile: {$profile->name} for user: {$user->name} has been saved.");
    }
}
```

### Available methods and events

#### HasOne::create
- fires {relationship}Creating, {relationship}Created
- events have $parent and $related models

#### HasOne::save
- fires {relationship}Saving, {relationship}Saved
- events have $parent and $related models