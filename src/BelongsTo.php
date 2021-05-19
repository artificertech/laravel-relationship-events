<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToBase;
use Illuminate\Support\Facades\Log;

/**
 * Class BelongsTo.
 *
 *
 * @property-read \Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents $parent
 */
class BelongsTo extends BelongsToBase implements EventDispatcher
{
    use HasEventDispatcher;

    /**
     * Associate the model instance to the given parent.
     *
     * @param \Illuminate\Database\Eloquent\Model|int|string $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associate($model)
    {
        // If the "associating" event returns false we'll bail out of the associate and return
        // false, indicating that the associate failed. This provides a chance for any
        // listeners to cancel associate operations if validations fail or whatever.
        if ($this->willDispatchEvents() && $this->child->fireModelBelongsToEvent('associating', $this->eventRelationship, $model, true) === false) {
            return false;
        }

        $result = parent::associate($model);

        if ($result && $this->willDispatchEvents()) {
            $this->child->fireModelBelongsToEvent('associated', $this->eventRelationship, $model, false);
        }

        return $result;
    }

    /**
     * Dissociate previously associated model from the given parent.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function dissociate()
    {
        $parent = $this->getResults();

        // If the "dissociating" event returns false we'll bail out of the dissociate and return
        // false, indicating that the dissociate failed. This provides a chance for any
        // listeners to cancel dissociate operations if validations fail or whatever.
        if ($this->willDispatchEvents() && $this->child->fireModelBelongsToEvent('dissociating', $this->eventRelationship, $parent, true) === false) {
            return false;
        }

        $result = parent::dissociate();

        if (!is_null($parent) && $this->willDispatchEvents()) {
            $this->child->fireModelBelongsToEvent('dissociated', $this->eventRelationship, $parent, false);
        }

        return $result;
    }
}
