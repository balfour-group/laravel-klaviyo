# laravel-klaviyo

A library for interacting with the Klaviyo API in Laravel.

*This library is in early release and is pending unit tests.*

## Installation

```bash
composer require balfour/laravel-klaviyo
```

## Configuration

The package makes use of the following env vars:

* `KLAVIYO_ENABLED` [default = `false`]
* `KLAVIYO_API_KEY` [default = `null`]
* `KLAVIYO_QUEUE` [default = `klaviyo`]

If you would like to publish the config, you can do so using:

`php artisan vendor:publish --provider="Balfour\LaravelKlaviyo\ServiceProvider"`

If you plan to process `events` and `identities` on a queue, you'll need to make sure
you have a queue worker running and handling the `KLAVIYO_QUEUE` jobs.

## Usage

```php
use App\Models\User;
use Balfour\LaravelKlaviyo\Event;
use Balfour\LaravelKlaviyo\Jobs\PushIdentity;
use Balfour\LaravelKlaviyo\Jobs\TrackEvent;
use Balfour\LaravelKlaviyo\Klaviyo;

$klaviyo = app(Klaviyo::class);

// pushing an identity
// in a real world, this may be a `user` model implementing the `IdentityInterface`
$user = User::find(1);
$klaviyo->pushIdentity($user);

// pushing an identity (using a queue)
$user = User::find(1);
PushIdentity::enqueue($user);

// tracking an event
$user = User::find(1);
$event = new Event(
    'Complete Checkout Step 2',
    [
        'product' => 'Chicken Soup',
        'price' => 'R100.00',
    ]
);
$event->fire($user);
// or
$klaviyo->trackEvent($user, $event);

// tracking an event (using a queue)
$event->enqueue($user);
// or
TrackEvent::enqueue($user, $event);

// in the case that you don't have an identity object, but just an email identifier
$event = new Event('Subscribed To Mailing List');
$event->fire('matthew@masterstart.com');

// create a mailing list
$klaviyo->createMailingList('My List Name');

// add email to mailing list
$klaviyo->addToMailingList('12345', 'matthew@masterstart.com');

// remove email from mailing list
$klaviyo->removeFromMailingList('12345', 'matthew@masterstart.com');
```
