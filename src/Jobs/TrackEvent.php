<?php

namespace Balfour\LaravelKlaviyo\Jobs;

use Balfour\LaravelKlaviyo\EventInterface;
use Balfour\LaravelKlaviyo\IdentityInterface;
use Balfour\LaravelKlaviyo\Klaviyo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var IdentityInterface|string
     */
    public $identity;

    /**
     * @var EventInterface
     */
    public $event;

    /**
     * Create a new job instance.
     *
     * @param IdentityInterface|string $identity
     * @param EventInterface $event
     */
    public function __construct($identity, EventInterface $event)
    {
        $this->identity = $identity;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @param Klaviyo $klaviyo
     * @throws \Exception
     */
    public function handle(Klaviyo $klaviyo): void
    {
        $klaviyo->trackEvent($this->identity, $this->event);
    }

    /**
     * @param IdentityInterface|string $identity
     * @param EventInterface $event
     * @param string|null $queue
     */
    public static function enqueue($identity, EventInterface $event, ?string $queue = null): void
    {
        if ($queue === null) {
            $queue = config('klaviyo.queue');
        }

        static::dispatch($identity, $event)->onQueue($queue);
    }
}
