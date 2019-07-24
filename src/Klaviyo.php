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
    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    protected function getBaseUri($endpoint, array $params = [])
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
     * @return array
     */
    protected function getDefaultRequestOptions()
    {
        return [
            'connect_timeout' => 2000,
            'timeout' => 6000,
        ];
    }

    /**
     * @param array $properties
     * @throws \Exception
     */
    public function identify(array $properties)
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
    public function pushIdentity(IdentityInterface $identity)
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

        $id = $identity->getPrimaryKey();

        if ($id) {
            $properties['$id'] = $id;
        }

        $properties = array_merge($properties, $identity->getCustomKlaviyoProperties());

        $this->identify($properties);
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    public function trackEvent(Event $event)
    {
        $identity = $event->getIdentity();

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
     * @return array
     */
    public function createMailingList($name)
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
     * @return array
     */
    public function addToMailingList($listId, $email)
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
     * @return array
     */
    public function removeFromMailingList($listId, $email)
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
     * @param array $params
     * @return array
     */
    protected function post($endpoint, array $params)
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
     * @param array $params
     * @return array
     */
    protected function delete($endpoint, array $params = [])
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
     * @param array $params
     * @throws \Exception
     */
    protected function sendLegacyRequest($endpoint, array $params)
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
