<?php

namespace CityAds\Api;

use CityAds\Api\Entity\ClientCredentials;
use CityAds\Api\Entity\Token;
use GuzzleHttp\Client;
use Psr\Cache\CacheItemPoolInterface;
use Stash\Pool;

class TokenGenerator
{
	const ACCESS_TOKEN_PATH = '/oauth/access_token';

	/**
	 * @var Pool
	 */
	private $cachePool;

	/**
	 * @var Client
	 */
	private $httpClient;

	/**
	 * @param Client                 $httpClient
	 * @param CacheItemPoolInterface $cachePool
	 */
	public function __construct(Client $httpClient, CacheItemPoolInterface $cachePool)
	{
		$this->cachePool = $cachePool;
		$this->httpClient = $httpClient;
	}

	/**
	 * @param ClientCredentials $clientCredentials
	 *
	 * @return Token
	 */
	public function getToken(ClientCredentials $clientCredentials)
	{
		$cacheKey = $this->getCacheKey($clientCredentials);

		if ($this->cachePool->hasItem($cacheKey)) {
			/** @var Token $token */
			$token = $this->cachePool->getItem($cacheKey)->get();

			if (!$token->expired()) {
				return $token;
			}
		}

		$response = $this->httpClient->post(
			self::ACCESS_TOKEN_PATH,
			[
				'form_params' => [
					'grant_type' => 'client_credentials',
					'client_id' => $clientCredentials->getClientId(),
					'client_secret' => $clientCredentials->getClientSecret(),
				],
			]
		);

		$response = json_decode($response->getBody()->getContents(), true);

		return $this->saveToken(
			new Token($response),
			$cacheKey
		);
	}

	/**
	 * @param Token  $token
	 * @param string $cacheKey
	 *
	 * @return Token
	 */
	private function saveToken(Token $token, $cacheKey)
	{
		$cacheItem = $this->cachePool
			->getItem($cacheKey)
			->set($token)
			->expiresAt(new \DateTime('@' . $token->getExpiresAt()));

		$this->cachePool->save($cacheItem);

		return $token;
	}

	/**
	 * @param ClientCredentials $clientCredentials
	 *
	 * @return string
	 */
	private function getCacheKey(ClientCredentials $clientCredentials)
	{
		return hash('sha256', $clientCredentials->getClientSecret());
	}
}