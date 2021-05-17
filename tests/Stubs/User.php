<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Model
{
    use HasRelationshipEvents;

    public static function setupTable()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)->withEvents();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents();
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
