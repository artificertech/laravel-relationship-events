<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasBelongsToEvents;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\MorphTo as MorphToBase;

/**
 * Class MorphTo.
 *
 *
 * @property-read \Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents $parent
 */
class MorphTo extends MorphToBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasBelongsToEvents;
}
