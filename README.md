# Serato SWS PHP SDK

A PHP SDK for interacting with SWS web services.

Note: currently limited to applications that use the [Server Side Application Authorisation Workflow](http://confluence.akld.serato.net:8090/display/DEV/Authentication+Basics)

All PHP code within the Serato Checkout library exists within the `Serato\SwsSdk` namespace.

## Style guide

Please ensure code adheres to the [PHP-FIG PSR-2 Coding Style Guide](http://www.php-fig.org/psr/psr-2/)

Use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/wiki) to validate your code against coding standards:

	$ ./vendor/bin/phpcs

## Unit tests

Configuration for PHPUnit is defined within [phpunit.xml](../../phpunit.xml).

To run tests:

	$ php vendor/bin/phpunit

## Generate PHP documentation

The [Sami PHP API documentation generator](https://github.com/FriendsOfPHP/sami)
can be used to generate PHP API documentation.

To generate documentation:

	$ vendor/bin/sami.php update lib/sws_php_sdk/phpdoc.php

Documentation is generated into the `docs\php` directory (which is not under source control).

Configuration for Sami is contained within [phpdoc.php](phpdoc.php).

## Usage

### Key Concepts

* The `Serato\SwsSdk\Sdk` class is used to provided configuration data into the SDK.
Once configured, an `Sdk` instance can be used to create clients.
* A client interacts with a specific SWS web service. A client extends `Serato\SwsSdk\Client`
which in turn extends `GuzzleHttp\Client`.
* Clients execute commands. A command encapsulates the HTTP method, URI path and parameters
used for a request to a specific HTTP endpoint of an SWS web service. Commands extend `Serato\SwsSdk\Command`.
* SWS web service endpoints use one of two means of HTTP based authentication: HTTP `Basic`, or `Bearer token` (via JWT). Commands
that interact with endpoints using `Bearer token` authentication require the bearer token value to be provided to them at execution time.
* When a client executes a command it returns a `Serato\SwsSdk\Result` object or throws an exception.
* `Serato\SwsSdk\Result` objects wrap the HTTP response from an SWS web service and provide native PHP array
access to it's data. They also provided access to the underlying `Psr\Http\Message\ResponseInterface`
response object.

### Configuring the SDK

The `Serato\SwsSdk\Sdk` constructor is the means by which all required configuration data is passed
into the SDK. `Serato\SwsSdk\Sdk::__construct` takes an `$args` array, a client application ID, client application
password.

`$args` is used to specify the endpoints of the various SWS web services, as well as providing additional configuration to the underlying `GuzzleHttp\Client`.

The endpoints of the various SWS web services can be specified by setting the `$args` `'env'` key with a value of either `Sdk::ENV_PRODUCTION` (ie. *production*)
or `Sdk::ENV_STAGING` (ie. *staging*).

Alternatively, custom endpoints can be specified by setting the `$args` `Sdk::BASE_URI` key with an array whose keys are
`Sdk::BASE_URI_ID` (ie *id*), `Sdk::BASE_URI_LICENSE` (ie. *license*), `Sdk::BASE_URI_PROFILE` (ie. *profile*) and
`Sdk::BASE_URI_ECOM` (ie. *ecom*), with values corresponding to the full base URIs
(including protocol) for the respective SWS web services.

One of `'env'` or `Sdk::BASE_URI` must be specified.

`$args` can take a `'timeout'` key which sets the request timeout for all requests. Timeout values are floats
representing the number of seconds before the request times out.

`$args` can also take a `'handler'` key that defines a custom HTTP handler for use by the underlying
GuzzleHttp library. The primary use for this is for mocking various HTTP responses (see *Testing clients* below for more).

```php
use Serato\SwsSdk\Sdk;

/* Configure the SDK to use the production SWS endpoints */
$args = [
	'env' => Sdk::ENV_PRODUCTION,
	'timeout' => 2.5
];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');

/* Configure the SDK to use custom SWS endpoints */
$args = [
	Sdk::BASE_URI => [
		Sdk::BASE_URI_ID => 'http://id.server.com',
		Sdk::BASE_URI_LICENSE => 'https://license.server.com',
		Sdk::BASE_URI_PROFILE => 'https://profile.server.com',
		Sdk::BASE_URI_ECOM    => 'https://ecom.server.com'
	]
];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');

/* Configure the SDK to use the staging SWS endpoints and a Guzzle MockHandler */
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

// Create a mock handler
$mock = new MockHandler([
    new Response(200, ['X-Foo' => 'Bar'])
]);
$handler = HandlerStack::create($mock);
$args = [
	'env' => Sdk::ENV_STAGING,
	'handler' => $handler
];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');
```

### Creating clients

An instance of `Serato\SwsSdk\Sdk` can be used to create service clients.

A clients interacts with a specific SWS service using the configuration parameters
passed to a `Serato\SwsSdk\Sdk` instance. 

There are currently two clients available:

* `Serato\SwsSdk\Identity\IdentityClient` - A client for interacting with SWS Identity Service.
* `Serato\SwsSdk\License\LicenseClient` - A client for interacting with SWS License Service.

```php
use Serato\SwsSdk\Sdk;

/* Create clients from an Sdk instance */
$args = ['env' => Sdk::ENV_PRODUCTION];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');

$identityClient = $sdk->createIdentityClient();
$licenseClient = $sdk->createLicenseClient();
```

### Executing commands

Commands correspond to a specific endpoint on an SWS service. Commands require
arguments that map directly to the request parameters allowed by the corresponding endpoint.

The PHP API documention lists the allowed arguments for each command class.

Commands can be explictly created then passed to the `Serato\SwsSdk\Client::executeCommand`
method. But it is simpler to use the magic methods of a client that map a command
to a client method name.

The PHP API documention provides the magic method name for each command class.

```php
use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\License\Command\ProductGet;

$args = ['env' => Sdk::ENV_PRODUCTION];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');
$identityClient = $sdk->createIdentityClient(); 
$licenseClient = $sdk->createLicenseClient();

/* Command that uses HTTP `Basic` auth */

// Explicitly create a `ProductGet` command and execute it
$commandArgs = ['product_id' => 'SDJ-1234-1234'];
$command = new ProductGet('my_app', 'my_pass', 'https://sws.endpoint.url', $commandArgs);
$result = $licenseClient->executeCommand($command);

// Simpler: Use the `LicenseClient::getProduct` magic method to execute the `ProductGet` command
$commandArgs = ['product_id' => 'SDJ-1234-1234'];
$result = $licenseClient->getProduct($commandArgs);

/* Command that uses `Bearer token` auth */

// Explicitly create a `UserGet` command and execute it
$bearerToken = 'my_bearer_token_value';
$command = new UserGet('my_app', 'my_pass', 'https://sws.endpoint.url');
$result = $identityClient->executeCommand($command, $bearerToken);

// Simpler: Use the `IdentityClient::getUser` magic method to execute the `UserGet` command
$result = $identityClient->getProduct($bearerToken);
```

### Working with Results

A successfully executed command returns a `Serato\SwsSdk\Result` instance.

A `Result` object takes a response from a SWS web service and parses the message body
so that the contents of the response body can be accessed using native PHP array accessor
syntax.

The `Result` object also has a `Result::getResponse` method that returns the underlying
`Psr\Http\Message\ResponseInterface` response object.

```php
use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\License\Command\ProductGet;

$args = ['env' => Sdk::ENV_PRODUCTION];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');
$licenseClient = $sdk->createLicenseClient();
$commandArgs = ['product_id' => 'SDJ-1234-1234'];

$result = $licenseClient->getProduct($commandArgs);

// Echo the product ID
echo $result['id'];
// Echo the number of licenses in the product
echo count($result['licenses']);
// Access the `Psr\Http\Message\ResponseInterface` response object
$response = $result->getResponse();
```

### Exceptions

When a command execution results in a non-200 HTTP response from a SWS web service
an exeception is thrown.

All exception classes in the SDK extend `Serato\SwsSdk\Exception\ResponseException`.
`ResponseException` extends the base PHP `RuntimeException` class.

`ResponseException` classes have a `ResponseException::getResult` method which returns a
`Result` object, providing access the underlying response from the web service.

Specific exception classes handle each type of expected non-200 HTTP response. The `code`
property of the exception class is used in one of two different ways in each child
exception class

* `Serato\SwsSdk\Exception\AccessDeniedException`: `code` is the error code as specified in the response message body.
* `Serato\SwsSdk\Exception\BadRequestException`: `code` is the error code as specified in the response message body.
* `Serato\SwsSdk\Exception\ResourceNotFoundException`: `code` is the HTTP response code. ie. 404.
* `Serato\SwsSdk\Exception\ServerApplicationErrorException`: `code` is the HTTP response code. ie. 500.
* `Serato\SwsSdk\Exception\ServiceUnavailableException`: `code` is the HTTP response code. ie. 503.

Requests resulting in connection errors (timeouts, failed DNS lookups etc) do not throw SDK-specific
exceptions and instead throw a `GuzzleHttp\Exception\ConnectException` provided by Guzzle.

### Testing clients

Because clients in the SDK extend `GuzzleHttp\Client` requests to SWS services can be mocked using Guzzle's
`GuzzleHttp\Handler\MockHandler`.

The [Guzzle documentation](http://guzzle.readthedocs.io/en/latest/testing.html) provides a good overview of how to
create mock responses and provide them to a `GuzzleHttp\Handler\MockHandler` instance.

The mock handler can then be provided to SwsSdk clients via the `Serato\SwsSdk\Sdk` class.

```php
use Serato\SwsSdk\Sdk;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

// Create a mock handler and queue two responses.
$mock = new MockHandler([
    new Response(200, [/* Headers */], '{"id":"SDJ-1111-11111"}'),
    new Response(200, [/* Headers */], '{"id":"SDJ-2222-22222"}')
]);

$handler = HandlerStack::create($mock);
$args = [
	'env' => Sdk::ENV_STAGING,
	'handler' => $handler
];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');

// Client is created with the `handler` option as specified in the `Sdk` constructor
$licenseClient = $sdk->createLicenseClient();

// Execute the first command. The mock handler returns the first response from the stack.
$result = $licenseClient->getProduct(['product_id' => 'SDJ-1234-1234']);
// Will echo 'SDJ-1111-11111'
echo $result['id'];
// Will echo the raw response body '{"id":"SDJ-1111-11111"}'
echo $result->getResponse()->getBody();

// Execute the first second. The mock handler returns the second response from the stack.
$result = $licenseClient->getProduct(['product_id' => 'SDJ-1234-1234']);
// Will echo 'SDJ-2222-22222'
echo $result['id'];
// Will echo the raw response body '{"id":"SDJ-2222-22222"}'
echo $result->getResponse()->getBody();
```

The mock handler stack can also include exceptions:

```php
use Serato\SwsSdk\Sdk;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Exception;

// Create a mock handler and queue two responses.
$mock = new MockHandler([
    new Response(200, [/* Headers */], '{"id":"SDJ-1111-11111"}'),
    new Exception
]);

$handler = HandlerStack::create($mock);
$args = [
	'env' => Sdk::ENV_STAGING,
	'handler' => $handler
];
$sdk = new Sdk($args, 'my_app_id', 'my_app_secrety_pass');

// Client is created with the `handler` option as specified in the `Sdk` constructor
$licenseClient = $sdk->createLicenseClient();

// Execute the first command. The mock handler returns the first response from the stack.
$result = $licenseClient->getProduct(['product_id' => 'SDJ-1234-1234']);
// Will echo 'SDJ-1111-11111'
echo $result['id'];

// Throws Exception
$result = $licenseClient->getProduct(['product_id' => 'SDJ-5678-4321']);
```