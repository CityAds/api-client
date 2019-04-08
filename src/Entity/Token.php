<?php

namespace CityAds\Api\Entity;

use InvalidArgumentException;

class Token
{
	/**
	 * @var string
	 */
	private $accessToken;

	/**
	 * @var string
	 */
	private $tokenType = 'Bearer';

	/**
	 * @var int
	 */
	private $expiresAt;

	/**
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		foreach (['access_token', 'expires_in'] as $requiredField) {
			if (!isset($options[$requiredField])) {
				throw new InvalidArgumentException("Required option not passed: '{$requiredField}'");
			}
		}

		$this->accessToken = $options['access_token'];
		$this->expiresAt = time() + $options['expires_in'];
	}

	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}

	/**
	 * @return string
	 */
	public function getTokenType()
	{
		return $this->tokenType;
	}

	/**
	 * @return int
	 */
	public function getExpiresAt()
	{
		return $this->expiresAt;
	}

	/**
	 * @return bool
	 */
	public function expired()
	{
		return $this->expiresAt <= time();
	}
}