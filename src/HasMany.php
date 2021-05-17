<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasOneOrManyEvents;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyBase;

/**
 * Class HasMany.
 */
class HasMany extends HasManyBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyEvents;
}
