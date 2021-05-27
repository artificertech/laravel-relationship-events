# Has One Relations:

```User``` model might be associated with one ```Address```

```php
namespace App\Models;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasRelationshipEvents;

    /**
     * Get the address associated with the user.
     */
    public function address()
    {
        return $this->morphOne(Address::class)->withEvents();
    }
}
```

Now we can use methods to assosiate ```User``` with ```Address``` and also update assosiated model.

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

$user->address()->create([
    'name' => 'home'
]);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::morphOneCreating('address', function ($user, $address) {
            Log::info("Creating address: {$address->name} for user {$user->name}.");
        });

        static::morphOneCreated('address', function ($user, $address) {
            Log::info("Address: {$address->name} for user: {$user->name} has been created.");
        });

        static::morphOneSaving('address', function ($user, $address) {
            Log::info("Saving address: {$address->name} for user {$user->name}.");
        });

        static::morphOneSaved('address', function ($user, $address) {
            Log::info("Address: {$address->name} for user: {$user->name} has been saved.");
        });
    }
// ...
```

Dispatch Events with the event dispatcher
```php
// ...
    protected $dispatchesEvents = [
        'addressCreating' => UserAddressCreating::class,
        'addressCreated' => UserAddressCreated::class,
        'addressSaving' => UserAddressSaveing::class,
        'addressSaving' => UserAddressSaving::class,
    ];
// ...
```

Or you may use an Observer. Be sure to define the observable events in your model class - See [Detecting Observable Events](../README.md#detecting-observable-events)
```php
namespace App\Observer;

class UserObserver
{
    /**
     * Handle the User "addressCreating" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Address $address
     *
     * @return void
     */
    public function addressCreating(User $user, Address $address)
    {
        Log::info("Creating address: {$address->name} for user {$user->name}.");
    }

    /**
     * Handle the User "addressCreated" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Address $address
     *
     * @return void
     */
    public function addressCreated(User $user, Address $address)
    {
        Log::info("Address: {$address->name} for user: {$user->name} has been created.");
    }

    /**
     * Handle the User "addressSaving" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Address $address
     *
     * @return void
     */
    public function addressSaving(User $user, Address $address)
    {
        Log::info("Saving address: {$address->name} for user {$user->name}.");
    }

    /**
     * Handle the User "addressSaved" event.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Address $address
     *
     * @return void
     */
    public function addressSaved(User $user, Address $address)
    {
        Log::info("Address: {$address->name} for user: {$user->name} has been saved.");
    }
}
```

### Available methods and events

#### MorphOne::create
- fires {relationship}Creating, {relationship}Created
- events have $parent and $related models

#### MorphOne::save
- fires {relationship}Saving, {relationship}Saved
- events have $parent and $related models