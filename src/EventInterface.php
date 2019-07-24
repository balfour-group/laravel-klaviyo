<?php

namespace Balfour\LaravelKlaviyo;

interface EventInterface
{
    /**
     * @return IdentityInterface|string
     */
    public function getIdentity();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getProperties();
}
