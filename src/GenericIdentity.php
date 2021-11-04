<?php

namespace Balfour\LaravelKlaviyo;

use Propaganistas\LaravelPhone\PhoneNumber;

class GenericIdentity implements IdentityInterface
{
    /**
     * @var mixed|null
     */
    protected $primaryKey;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var PhoneNumber|null
     */
    protected $phoneNumber;

    /**
     * @var string|null
     */
    protected $zipCode;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @var string|null
     */
    protected $region;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var mixed[]
     */
    protected $properties = [];

    /**
     * @param mixed $primaryKey
     * @return $this
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $lastName
     * @return $this
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param PhoneNumber|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $zipCode
     * @return $this
     */
    public function setZipCode(?string $zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $region
     * @return $this
     */
    public function setRegion(?string $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $country
     * @return $this
     */
    public function setCountry(?string $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param mixed[] $properties
     * @return $this
     */
    public function setCustomKlaviyoProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getCustomKlaviyoProperties(): array
    {
        return $this->properties;
    }
}
