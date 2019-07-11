<?php

namespace CityAds\Api;

use CityAds\Api\Entity\ClientCredentials;
use GuzzleHttp\ClientInterface;

/**
 * @method Response get(string $resource, array $params = [])
 * @method Response post(string $resource, array $params = [])
 * @method Response patch(string $resource, array $params = [])
 * @method Response delete(string $resource)
 */
class Client
{
	const ALLOWED_METHODS = ['GET', 'POST', 'PATCH', 'DELETE',];

	/**
	 * @var \GuzzleHttp\Client
	 */
	private $httpClient;

	/**
	 * @var TokenGenerator
	 */
	private $tokenGenerator;

	/**
	 * @var ClientCredentials
	 */
	private $clientCredentials;

	/**
	 * @param ClientInterface $httpClient
	 * @param TokenGenerator  $tokenGenerator
	 */
	public function __construct(ClientInterface $httpClient, TokenGenerator $tokenGenerator)
	{
		$this->httpClient = $httpClient;
		$this->tokenGenerator = $tokenGenerator;
	}

	/**
	 * @param ClientCredentials $clientCredentials
	 *
	 * @return Client
	 */
	public function setClientCredentials(ClientCredentials $clientCredentials)
	{
		$this->clientCredentials = $clientCredentials;

		return $this;
	}

	/**
	 * @param string $method
	 * @param array  $arguments
	 *
	 * @return Response
	 */
	public function __call($method, array $arguments)
	{
		if (!in_array(strtoupper($method), self::ALLOWED_METHODS, true)) {
			throw new \BadMethodCallException(sprintf("Call to undefined method '%s'", $method));
		}

		list($resource, $params) = array_pad($arguments, 2, []);

		if (!$resource || !is_string($resource)) {
			throw new \BadMethodCallException("Incorrect resource name");
		}

		$options = $this->buildOptions($method, $params);

		$response = $this->httpClient->request(
			$method,
			ltrim($resource, "/"),
			$options
		);

		return Response::create($response);
	}

	/**
	 * @param string $method
	 * @param array  $params
	 *
	 * @return array
	 */
	private function buildOptions($method, array $params)
	{
		$options = [
			'headers' => [
				'X-Access-Token' => $this->tokenGenerator->getToken($this->clientCredentials)->getAccessToken()
			]
		];

		$parametersKeyName = strtoupper($method) === 'GET' ? 'query' : 'form_params';

		$options[$parametersKeyName] = $params;

		return $options;
	}
}