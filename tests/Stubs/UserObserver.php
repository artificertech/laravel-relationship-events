<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

class UserObserver
{
    /**
     * Handle the User "observerProfileCreating" event.
     *
     * @param Artificertech\RelationshipEvents\Tests\Stubs\User $user
     * @param Artificertech\RelationshipEvents\Tests\Stubs\ObserverProfile $observerProfile
     *
     * @return void
     */
    public function observerProfileCreating($user, $observerProfile)
    {
        if ($observerProfile->name == 'badName') return false;
    }

    /**
     * Handle the User "observerProfileSaving" event.
     *
     * @param Artificertech\RelationshipEvents\Tests\Stubs\User $user
     * @param Artificertech\RelationshipEvents\Tests\Stubs\ObserverProfile $observerProfile
     *
     * @return void
     */
    public function observerProfileSaving($user, $observerProfile)
    {
        if ($observerProfile->name == 'badName') return false;
    }
}
