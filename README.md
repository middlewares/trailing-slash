# middlewares/trailing-slash

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Middleware to normalize the trailing slash of the uri path. By default removes the slash so, for example, `/post/23/` is converted to `/post/23`. Useful if you have problems with the router.

## Requirements

* PHP >= 7.0
* A [PSR-7 http library](https://github.com/middlewares/awesome-psr15-middlewares#psr-7-implementations)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [middlewares/trailing-slash](https://packagist.org/packages/middlewares/trailing-slash).

```sh
composer require middlewares/trailing-slash
```

## Example

```php
$dispatcher = new Dispatcher([
	(new Middlewares\TrailingSlash(true))
		->redirect()
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Options

#### `__construct(booll $trailingSlash = false)`

Set `true` to add the slash instead remove so, for example, `post/23` is converted to `/post/23/`. Note that if the path contains an extension, the slash is **NOT** added. For example, `images/image.png` remains the same, instead be converted to `images/image.png/`.

#### `redirect(true)`

Set this option to return a `301` response redirecting to the new path

#### `responseFactory(Psr\Http\Message\ResponseFactoryInterface $responseFactory)`

A PSR-17 factory to create `301` responses.
---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/trailing-slash.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/trailing-slash/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/trailing-slash.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/trailing-slash.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/82362f27-d8e9-4808-aed0-ce2cb5e339d4.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/trailing-slash
[link-travis]: https://travis-ci.org/middlewares/trailing-slash
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/trailing-slash
[link-downloads]: https://packagist.org/packages/middlewares/trailing-slash
[link-sensiolabs]: https://insight.sensiolabs.com/projects/82362f27-d8e9-4808-aed0-ce2cb5e339d4
