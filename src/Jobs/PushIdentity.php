<?php

namespace Balfour\LaravelKlaviyo\Jobs;

use Balfour\LaravelKlaviyo\IdentityInterface;
use Balfour\LaravelKlaviyo\Klaviyo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushIdentity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var IdentityInterface
     */
    public $identity;

    /**
     * Create a new job instance.
     *
     * @param IdentityInterface $identity
     */
    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    /**
     * Execute the job.
     *
     * @param Klaviyo $klaviyo
     * @throws \Exception
     */
    public function handle(Klaviyo $klaviyo): void
    {
        $klaviyo->pushIdentity($this->identity);
    }

    /**
     * @param IdentityInterface $identity
     */
    public static function enqueue(IdentityInterface $identity): void
    {
        static::dispatch($identity)->onQueue(config('klaviyo.queue'));
    }
}
