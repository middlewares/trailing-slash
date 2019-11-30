# middlewares/trailing-slash

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]

Middleware to normalize the trailing slash of the uri path. By default removes the slash so, for example, `/post/23/` is converted to `/post/23`. Useful if you have problems with the router.

## Requirements

* PHP >= 7.2
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

## Usage

By default, this middleware removes the trailing slash of the uri path. Set `true` to the constructor's first argument to add instead remove:

```php
//Removes the slash, so /post/23/ is converted to /post/23
$slash = new Middlewares\TrailinSlash();

//Force the slash, so /post/23 is converted to /post/23/
$slash = new Middlewares\TrailinSlash(true);
```

Of course, if the path contains an extension, the slash is **NOT** added. For example, `images/image.png` remains the same, instead be converted to `images/image.png/`.

### redirect

If the path must be converted, this option returns a `301` response redirecting to the new path. Optionally, you can provide a `Psr\Http\Message\ResponseFactoryInterface` that will be used to create the redirect response. If it's not defined, [Middleware\Utils\Factory](https://github.com/middlewares/utils#factory) will be used to detect it automatically.

```php
$responseFactory = new MyOwnResponseFactory();

//Simply removes the slash
$slash = new Middlewares\TrailinSlash();

//Returns a redirect response to the new path
$slash = (new Middlewares\TrailinSlash())->redirect();

//Returns a redirect response to the new path using a specific response factory
$slash = (new Middlewares\TrailinSlash())->redirect($responseFactory);
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/trailing-slash.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/trailing-slash/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/trailing-slash.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/trailing-slash.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/trailing-slash
[link-travis]: https://travis-ci.org/middlewares/trailing-slash
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/trailing-slash
[link-downloads]: https://packagist.org/packages/middlewares/trailing-slash
