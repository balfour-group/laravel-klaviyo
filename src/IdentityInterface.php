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
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @return PhoneNumber
     */
    public function getPhoneNumber();

    /**
     * @return string
     */
    public function getZipCode();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getCountry();

    /**
     * @return array
     */
    public function getCustomKlaviyoProperties();
}
