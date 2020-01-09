Документация по получению параметров клиента и описание методов API: https://cityads.com/publisher/api

# Установка
```
composer require cityads/api-client
```
# Пример использования

```php
<?php
require_once 'vendor/autoload.php';

$cityadsApiClientFactory = new \CityAds\Api\Factory\ClientFactory;

$clientId = 'client_id';
$clientSecret = 'client_secret';

//создание экземпляра клиента
$cityadsApiClient = $cityadsApiClientFactory->create($clientId, $clientSecret);

//GET-запрос на получение списка сущностей
$getListResponse = $cityadsApiClient->get(
	'resourceName',
	[
		'sort' => 'id',
		'sort_direction' => 'asc',
	]
);

//GET-запрос на получение сущности по ее идентификатору
$getItemResponse = $cityadsApiClient->get(
	'resourceName/{id}',
	[
		'sort' => 'id',
		'sort_direction' => 'desc',
	]
);

//POST-запрос на создание новой сущности
$postResponse = $cityadsApiClient->post(
	'resourceName',
	[
		'entityName' => [
			'some_field' => 125.56,
			'another_field' => 'value',
		],
	]
);

//PATCH-запрос на редактирование сущности
$patchResponse = $cityadsApiClient->patch(
	'resourceName/{id}',
	[
		"entityName" => [
			"some_field" => 127,
		],
	]
);
```