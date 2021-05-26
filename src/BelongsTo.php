<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasBelongsToEvents;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToBase;

/**
 * Class BelongsTo.
 *
 *
 * @property-read \Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents $parent
 */
class BelongsTo extends BelongsToBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasBelongsToEvents;
}
