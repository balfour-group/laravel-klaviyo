<?php

namespace Balfour\LaravelKlaviyo;

use Propaganistas\LaravelPhone\PhoneNumber;

interface IdentityInterface
{
    /**
     * @return mixed
     */
    public function getPrimaryKey();

    /**
     * @return string|null
     */
    public function getEmail() :?string;

    /**
     * @return string|null
     */
    public function getFirstName() :?string;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber;

    /**
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @return string|null
     */
    public function getRegion(): ?string;

    /**
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * @return mixed[]
     */
    public function getCustomKlaviyoProperties(): array;
}
