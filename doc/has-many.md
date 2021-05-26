# Has Many Relations:

```User``` model might be associated with many ```Posts```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the posts associated with the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents();
    }
}
```

Now we can use methods to assosiate ```User``` with ```Post``` and also update associated model.

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

// Create posts and assosiate it with user
// This will fire two events hasManyCreating, hasManyCreated
$user->posts()->create([
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

        static::hasManyCreating('posts', function ($user, $post) {
            Log::info("Creating post: {$post->name} for user {$user->name}.");
        });

        static::hasManyCreated('posts', function ($user, $post) {
            Log::info("Post: {$post->name} for user: {$user->name} has been created.");
        });

        static::hasManySaving('posts', function ($user, $post) {
            Log::info("Saving post: {$post->name} for user {$user->name}.");
        });

        static::hasManySaved('posts', function ($user, $post) {
            Log::info("Post: {$post->name} for user: {$user->name} has been saved.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'postsCreating' => UserPostsCreating::class,
        'postsCreated' => UserPostsCreated::class,
        'postsSaving' => UserPostsSaveing::class,
        'postsSaving' => UserPostsSaving::class,
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
     * Handle the User "postsCreating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Posts $post
     *
     * @return void
     */
    public function postsCreating(User $user, Posts $post)
    {
        Log::info("Creating post: {$post->name} for user {$user->name}.");
    }

    /**
     * Handle the User "postsCreated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Posts $post
     *
     * @return void
     */
    public function postsCreated(User $user, Posts $post)
    {
        Log::info("Post: {$post->name} for user: {$user->name} has been created.");
    }

    /**
     * Handle the User "postsSaving" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Posts $post
     *
     * @return void
     */
    public function postsSaving(User $user, Posts $post)
    {
        Log::info("Saving post: {$post->name} for user {$user->name}.");
    }

    /**
     * Handle the User "postsSaved" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Posts $post
     *
     * @return void
     */
    public function postsSaved(User $user, Posts $post)
    {
        Log::info("Post: {$post->name} for user: {$user->name} has been saved.");
    }
}
```

### Available methods and events

#### HasMany::create
- fires {relationship}Creating, {relationship}Created
- events have $parent and $related models

#### HasMany::save
- fires {relationship}Saving, {relationship}Saved
- events have $parent and $related models