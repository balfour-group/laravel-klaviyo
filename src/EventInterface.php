<?php

namespace Balfour\LaravelKlaviyo;

interface EventInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed[]
     */
    public function getProperties(): array;
}
