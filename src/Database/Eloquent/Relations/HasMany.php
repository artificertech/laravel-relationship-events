<?php

namespace Artificertech\RelationshipEvents\Database\Eloquent\Relations;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasEventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasOneOrManyEvents;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyBase;

/**
 * Class HasMany.
 */
class HasMany extends HasManyBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyEvents;
}
