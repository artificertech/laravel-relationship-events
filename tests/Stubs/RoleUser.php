<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RoleUser extends Pivot
{
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    protected static function booting()
    {
        static::saving(function ($roleUser) {
            Log::debug([
                $roleUser
            ]);
            if ($roleUser->role->name == 'badName') return false;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
