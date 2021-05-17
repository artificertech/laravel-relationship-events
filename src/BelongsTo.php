<?php

namespace Artificertech\RelationshipEvents;

use Artificertech\RelationshipEvents\Contracts\EventDispatcher;
use Artificertech\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToBase;

/**
 * Class BelongsTo.
 *
 *
 * @property-read \Artificertech\RelationshipEvents\Concerns\HasBelongsToEvents $parent
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
        // If the "assoicating" event returns false we'll bail out of the associate and return
        // false, indicating that the associate failed. This provides a chance for any
        // listeners to cancel associate operations if validations fail or whatever.
        if ($this->child->fireModelBelongsToEvent('associating', $this->relationName, $model) === false) {
            return false;
        }

        $result = parent::associate($model);

        if ($result) {
            $this->child->fireModelBelongsToEvent('associated', $this->relationName, $model);
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
        if ($this->child->fireModelBelongsToEvent('dissociating', $this->relationName, $parent) === false) {
            return false;
        }

        $result = parent::dissociate();

        if (!is_null($parent)) {
            $this->child->fireModelBelongsToEvent('dissociated', $this->relationName, $parent);
        }

        return $result;
    }
}
