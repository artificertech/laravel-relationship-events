<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Artificertech\RelationshipEvents\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comment extends Model
{
    use HasRelationshipEvents;

    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function post()
    {
        return $this->morphTo(Post::class)->withEvents();
    }
}
