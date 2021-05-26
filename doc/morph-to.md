# Has One Relations:

```Post``` model might be associated with one ```Comment```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the post associated with the comment.
     */
    public function post()
    {
        return $this->morphTo(Post::class)->withEvents();
    }
}
```

Now we can use methods to associate ```Comment``` with ```Post``` and also update assosiated model.

```php
// ...
$comment = Comment::create([
    'name' => 'my comment'
]);

$post = factory(Post::class)->create([
    'name' => 'John Smith',
]);

$comment->post()->associate($post);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::morphToAssociating('post', function ($comment, $post) {
            Log::info("Associating comment: {$comment->name} with post {$post->name}.");
        });

        static::morphToAssociated('post', function ($parent, $post) {
            Log::info("Comment: {$comment->name} for post: {$post->name} has been associated.");
        });

        static::morphToDissociating('post', function ($comment, $post) {
            Log::info("Dissociating comment: {$comment->name} with post {$post->name}.");
        });

        static::morphToDissociated('post', function ($comment, $post) {
            Log::info("Comment: {$comment->name} for post: {$post->name} has been dissociated.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'postAssociating' => CommentPostAssociating::class,
        'postAssociated' => CommentPostAssociated::class,
        'postDissociating' => CommentPostDissociating::class,
        'postDissociated' => CommentPostDissociated::class,
    ];
// ...
```

Or you may use an Observer
```php
namespace App\Observer;

class CommentObserver
{
    /**
     * Handle the Post "postAssociating" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     *
     * @return void
     */
    public function postAssociating(Comment $comment, Post $post)
    {
        Log::info("Associating comment: {$comment->name} with post {$post->name}.");
    }

    /**
     * Handle the Post "postAssociated" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     *
     * @return void
     */
    public function postAssociated(Comment $comment, Post $post)
    {
        Log::info("Comment: {$comment->name} for post: {$post->name} has been associated.");
    }

    /**
     * Handle the Post "postDissociating" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     *
     * @return void
     */
    public function postDissociating(Comment $comment, Post $post)
    {
        Log::info("Dissociating comment: {$comment->name} with post {$post->name}.");
    }

    /**
     * Handle the Post "postDissociated" event.
     *
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     *
     * @return void
     */
    public function postDissociated(Comment $comment, Post $post)
    {
        Log::info("Comment: {$comment->name} for post: {$post->name} has been dissociated.");
    }
}
```

### Available methods and events

#### MorphTo::associate
- fires {relationship}Associating, {relationship}Associated
- events have $parent and $related models

#### MorphTo::dissociate
- fires {relationship}Dissociating, {relationship}Dissociated
- events have $parent and $related models