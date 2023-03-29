<?php

namespace Artificertech\RelationshipEvents\Database\Eloquent\Relations;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasBelongsToEvents;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasEventDispatcher;
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
