<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasOneOrManyEvents;
use Illuminate\Database\Eloquent\Relations\MorphOne as MorphOneBase;

/**
 * Class MorphOne.
 */
class MorphOne extends MorphOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyEvents;
}
