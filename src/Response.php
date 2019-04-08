<?php

namespace CityAds\Api;

use Psr\Http\Message\ResponseInterface;

class Response
{
	/**
	 * @var int
	 */
	private $code;

	/**
	 * @var string
	 */
	private $data;

	/**
	 * @param ResponseInterface $response
	 */
	public function __construct(ResponseInterface $response)
	{
		$this->code = $response->getStatusCode();
		$this->data = $response->getBody()->getContents();
	}

	/**
	 * @param ResponseInterface $response
	 *
	 * @return Response
	 */
	public static function create(ResponseInterface $response)
	{
		return new static($response);
	}

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}

	public function toArray()
	{
		return json_decode($this->data, true);
	}
}