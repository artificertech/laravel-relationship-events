# Has One Relations:

```Post``` model might be associated with one ```Comments```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the comments associated with the post.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->withEvents();
    }
}
```

Now we can use methods to assosiate ```Post``` with ```Comment``` and also update associated model.

```php
// ...
$post = factory(Post::class)->create([
    'name' => 'John Smith',
]);

$post->comments()->create([
    'name' => 'my comment text',
]);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::hasManyCreating('comments', function ($post, $comment) {
            Log::info("Creating comment: {$comment->name} for post {$post->name}.");
        });

        static::hasManyCreated('comments', function ($post, $comment) {
            Log::info("Comment: {$comment->name} for post: {$post->name} has been created.");
        });

        static::hasManySaving('comments', function ($post, $comment) {
            Log::info("Saving comment: {$comment->name} for post {$post->name}.");
        });

        static::hasManySaved('comments', function ($post, $comment) {
            Log::info("Comment: {$comment->name} for post: {$post->name} has been saved.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'commentsCreating' => PostCommentsCreating::class,
        'commentsCreated' => PostCommentsCreated::class,
        'commentsSaving' => PostCommentsSaveing::class,
        'commentsSaving' => PostCommentsSaving::class,
    ];
// ...
```

Or you may use an Observer. Be sure to define the observable events in your model class - See [Detecting Observable Events](../README.md#detecting-observable-events)
```php
namespace App\Observer;

class PostObserver
{
    /**
     * Handle the Post "commentsCreating" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comments $comment
     *
     * @return void
     */
    public function commentsCreating(Post $post, Comments $comment)
    {
        Log::info("Creating comment: {$comment->name} for post {$post->name}.");
    }

    /**
     * Handle the Post "commentsCreated" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comments $comment
     *
     * @return void
     */
    public function commentsCreated(Post $post, Comments $comment)
    {
        Log::info("Comment: {$comment->name} for post: {$post->name} has been created.");
    }

    /**
     * Handle the Post "commentsSaving" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comments $comment
     *
     * @return void
     */
    public function commentsSaving(Post $post, Comments $comment)
    {
        Log::info("Saving comment: {$comment->name} for post {$post->name}.");
    }

    /**
     * Handle the Post "commentsSaved" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comments $comment
     *
     * @return void
     */
    public function commentsSaved(Post $post, Comments $comment)
    {
        Log::info("Comment: {$comment->name} for post: {$post->name} has been saved.");
    }
}
```

### Available methods and events

#### MorphMany::create
- fires {relationship}Creating, {relationship}Created
- events have $parent and $related models

#### MorphMany::save
- fires {relationship}Saving, {relationship}Saved
- events have $parent and $related models