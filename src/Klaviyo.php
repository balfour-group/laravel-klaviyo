<?php

namespace Balfour\LaravelKlaviyo;

use GuzzleHttp\Client;

class Klaviyo
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $uri = 'https://a.klaviyo.com/api';

    /**
     * @param Client $client
     * @param string $apiKey
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     * @return string
     */
    protected function getBaseUri(string $endpoint, array $params = []): string
    {
        $uri = $this->uri;
        $uri = rtrim($uri, '/');
        $uri .= '/' . ltrim($endpoint, '/');

        if (!empty($params)) {
            $uri .= '?' . http_build_query($params);
        }

        return $uri;
    }

    /**
     * @return mixed[]
     */
    protected function getDefaultRequestOptions(): array
    {
        return [
            'connect_timeout' => 2000,
            'timeout' => 6000,
        ];
    }

    /**
     * @param mixed[] $properties
     * @throws \Exception
     */
    public function identify(array $properties): void
    {
        $params = [
            'token' => $this->apiKey,
            'properties' => $properties,
        ];

        $this->sendLegacyRequest('identify', $params);
    }

    /**
     * @param IdentityInterface $identity
     * @throws \Exception
     */
    public function pushIdentity(IdentityInterface $identity): void
    {
        $number = $identity->getPhoneNumber();

        $properties = [
            '$email' => $identity->getEmail(),
            '$first_name' => $identity->getFirstName(),
            '$last_name' => $identity->getLastName(),
            '$phone_number' => $number ? $number->formatE164() : null,
            '$zip' => $identity->getZipCode(),
            '$city' => $identity->getCity(),
            '$region' => $identity->getRegion(),
        ];

        $consent = $identity->getConsent();
        if ($consent) {
            $properties['$consent'] = $consent;
        }


        $id = $identity->getPrimaryKey();

        if ($id) {
            $properties['$id'] = $id;
        }

        $properties = array_merge($properties, $identity->getCustomKlaviyoProperties());

        $this->identify($properties);
    }

    /**
     * @param IdentityInterface|string $identity
     * @param EventInterface $event
     * @throws \Exception
     */
    public function trackEvent($identity, EventInterface $event): void
    {
        $customProperties = [];
        if ($identity instanceof IdentityInterface) {
            $id = $identity->getPrimaryKey();
            if ($id) {
                $customProperties['$id'] = $identity->getPrimaryKey();
            } else {
                $customProperties['$email'] = $identity->getEmail();
            }
        } else {
            $customProperties['$email'] = (string) $identity;
        }

        $params = [
            'token' => $this->apiKey,
            'event' => $event->getName(),
            'properties' => $event->getProperties(),
            'customer_properties' => $customProperties,
        ];

        $this->sendLegacyRequest('track', $params);
    }

    /**
     * @param string $name
     * @return mixed[]
     */
    public function createMailingList(string $name): array
    {
        $params = [
            'name' => $name,
            'list_type' => 'standard',
        ];
        return $this->post('v1/lists', $params);
    }

    /**
     * @param string $listId
     * @param string $email
     * @return mixed[]
     */
    public function addToMailingList(string $listId, string $email): array
    {
        $params = [
            'email' => $email,
            'confirm_optin' => 'false',
        ];
        return $this->post(sprintf('v1/list/%s/members', $listId), $params);
    }

    /**
     * @param string $listId
     * @param string $email
     * @return mixed[]
     */
    public function removeFromMailingList(string $listId, string $email): array
    {
        $params = [
            'batch' => json_encode([
                [
                    'email' => $email,
                ],
            ]),
        ];
        return $this->delete(sprintf('v1/list/%s/members/batch', $listId), $params);
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     * @return mixed
     */
    protected function post(string $endpoint, array $params)
    {
        $uri = $this->getBaseUri($endpoint);
        $params['api_key'] = $this->apiKey;
        $options = array_merge($this->getDefaultRequestOptions(), [
            'form_params' => $params,
        ]);
        $response = $this->client->post($uri, $options);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     * @return mixed
     */
    protected function delete(string $endpoint, array $params = [])
    {
        $uri = $this->getBaseUri($endpoint);
        $params['api_key'] = $this->apiKey;
        $options = array_merge($this->getDefaultRequestOptions(), [
            'form_params' => $params,
        ]);
        $response = $this->client->delete($uri, $options);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     * @throws \Exception
     */
    protected function sendLegacyRequest(string $endpoint, array $params)
    {
        $data = base64_encode(json_encode($params));
        $uri = $this->getBaseUri($endpoint, ['data' => $data]);
        $response = $this->client->get($uri, $this->getDefaultRequestOptions());
        $body = (string) $response->getBody();

        if ($body !== '1') {
            throw new \Exception(sprintf('The Klaviyo legacy API call to "%s" returned a non 1 response body.', $uri));
        }
    }
}
