<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasOneOrManyEvents;
use Illuminate\Database\Eloquent\Relations\MorphMany as MorphManyBase;

/**
 * Class MorphMany.
 */
class MorphMany extends MorphManyBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyEvents;
}
