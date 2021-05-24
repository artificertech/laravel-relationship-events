<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    use HasRelationshipEvents;

    protected $guarded = [];

    protected static function booting()
    {
        static::morphManyCreating('comments', function ($post, $comment) {
            if ($comment->name == 'badName') return false;
        });

        static::morphManySaving('comments', function ($post, $comment) {
            if ($comment->name == 'badName') return false;
        });
    }

    public static function setupTable()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->withEvents();
    }
}
