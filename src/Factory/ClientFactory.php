<?php

namespace CityAds\Api\Factory;

use CityAds\Api\Client;
use CityAds\Api\Entity\ClientCredentials;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ClientFactory
{
	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;

	public function __construct()
	{
		$this->buildContainer();
	}

	/**
	 * @param string $clientId
	 * @param string $clientSecret
	 *
	 * @return Client
	 */
	public function create($clientId, $clientSecret)
	{
		return clone $this->containerBuilder
			->get('api_client')
			->setClientCredentials(new ClientCredentials($clientId, $clientSecret));
	}

	private function buildContainer()
	{
		$this->containerBuilder = new ContainerBuilder();

		$loader = new XmlFileLoader($this->containerBuilder, new FileLocator(__DIR__));
		$loader->load('config.xml');

		$this->containerBuilder->compile();
	}
}
