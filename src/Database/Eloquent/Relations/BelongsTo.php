<?php

namespace Artificertech\RelationshipEvents\Database\Eloquent\Relations;

use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasBelongsToEvents;
use Artificertech\RelationshipEvents\Database\Eloquent\Relations\Concerns\HasEventDispatcher;
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
