<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Profile extends Model
{
    use HasRelationshipEvents;

    protected $guarded = [];

    protected static function booting()
    {
        static::belongsToAssociating('user', function ($profile, $user) {
            if ($user->name == 'badName') return false;
        });

        static::belongsToDissociating('user', function ($profile, $user) {
            if ($user->name == 'badDissociateName') return false;
        });
    }

    public static function setupTable()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withEvents('user');
    }
}
