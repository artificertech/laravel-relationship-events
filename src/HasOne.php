<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasOneOrManyMethods;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneBase;

/**
 * Class HasOne.
 */
class HasOne extends HasOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
