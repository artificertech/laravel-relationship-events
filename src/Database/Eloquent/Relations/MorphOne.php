<?php

namespace Artificertech\RelationshipEvents\Database\Eloquent\Relations;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasEventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasOneOrManyEvents;
use Illuminate\Database\Eloquent\Relations\MorphOne as MorphOneBase;

/**
 * Class MorphOne.
 */
class MorphOne extends MorphOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyEvents;
}
