<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}
