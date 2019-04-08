You can get API access credentials and API methods description here: https://cityads.com/publisher/api

# Installation
```
composer require cityads/api-client
```
# Usage

```php
<?php
require_once 'vendor/autoload.php';

$cityadsApiClientFactory = new \CityAds\Api\Factory\ClientFactory;

$clientId = 'client_id';
$clientSecret = 'client_secret';

//creating client instance
$cityadsApiClient = $cityadsApiClientFactory->create($clientId, $clientSecret);

//GET request to get an entity list
$getListResponse = $cityadsApiClient->get(
	'resourceName',
	[
		'sort' => 'id',
		'sort_direction' => 'asc',
	]
);

//GET request to get an entity by ID
$getItemResponse = $cityadsApiClient->get(
	'resourceName/{id}',
	[
		'sort' => 'id',
		'sort_direction' => 'desc',
	]
);

//POST request to create a new entity
$postResponse = $cityadsApiClient->post(
	'resourceName',
	[
		'entityName' => [
			'some_field' => 125.56,
			'another_field' => 'value',
		],
	]
);

//PATCH request to edit an entity
$patchResponse = $cityadsApiClient->patch(
	'resourceName/{id}',
	[
		"entityName" => [
			"some_field" => 127,
		],
	]
);
```