# r2d2 [![Build Status](https://travis-ci.org/lzakrzewski/r2d2.svg?branch=master)](https://travis-ci.org/lzakrzewski/r2d2)
## R2D2 - DeathStar API consumer

#### Demo
![example-output](resources/demo.gif)

#### Live API
[https://death-star-api.herokuapp.com/](https://death-star-api.herokuapp.com/)

#### Requirements
- `docker`     
or
- `composer` && `"php": ">=7.2", "ext-json": "*"`   

#### Installation & usage
1. Clone repo
2. Run `composer install` or `make build`
3. Run `bin/console r2d2` or `make r2d2`

#### Implementation

##### ApiConsumer
[DeathStarApiConsumer](src/R2D2/DeathStarApi/DeathStarApiConsumer.php)

##### Contract testing
*Contract* - The set of pre-defined requests and expected responses.
In order to ensure that our integration has been implemented correctly we might need to perform an end to end test between an *API provider* and an *API consumer*.
This test can assert that the *API provider* returns expected responses for a set of pre-defined requests by the *API consumer*.
Unfortunately, "end to end" tests with *API provider* and  *API consumer* involved are expensive in terms of time and resources.
In case of any change in our *API provider* we might need to perform e2e testing again.
I think it is much better to [test against shared contract](tests/contract/R2D2/DeathStarApi/DeathStarApiConsumerTest.ph) rather than perform e2e test every time, 
so I decided to introduce *contract* test-suite instead of *end to end* test-suite.     

[death-star-contracts - implementation](https://github.com/lzakrzewski/death-star-contracts)
[Contract test - implementation](tests/contract/R2D2/DeathStarApi/DeathStarApiConsumerTest.php)
[Consumer-Driven Contracts](https://martinfowler.com/articles/consumerDrivenContracts.html#Consumer-drivenContracts)

##### Ports & Adapters Pattern
In order to satisfy goal of [Contract testing](#Contract_testing) and follow the Single responsibility principle, I decided to extract `DeathStarApiAdapter` interface and use multiple implementations of this interface:

```php
interface DeathStarApiAdapter
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @throws DeathStarApiException
     *
     * @return array
     */
    public function request(string $method, string $uri, array $options): array;
}
```

Implementations:
- [HttpDeathStarApiAdapter](src/R2D2/DeathStarApi/Adapter/HttpDeathStarApiAdapter.php) - It uses [guzzle](https://github.com/guzzle/guzzle) and it performs real HTTP calls. It isn't in use during testing,
- [LoggableDeathStarApiAdapter](src/R2D2/DeathStarApi/Adapter/LoggableDeathStarApiAdapter.php) - It logs requests and responses,
- [TranslatesBinarySpeakApiAdapter](src/R2D2/DeathStarApi/Adapter/TranslatesBinarySpeakApiAdapter.php) - It translates [droid-speak](https://github.com/lzakrzewski/droid-speak),
- [UsesContractsDeathStarApiAdapterStub](src/R2D2/DeathStarApi/Adapter/UsesContractsDeathStarApiAdapterStub.php) - [*TEST ENV ONLY*] - It checks if [DeathStarApiConsumer](src/R2D2/DeathStarApi/DeathStarApiConsumer.php) is matching expected [contract].
