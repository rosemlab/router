# Router

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practices by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require rosemlab/router
```

## Usage

``` php
$skeleton = new Rosem\Router();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email iroman.via@gmail.com instead of using the issue tracker.

## Credits

- [Roman Shevchenko][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rosemlab/router.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rosemlab/router/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rosemlab/router.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rosemlab/router.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rosemlab/router.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rosemlab/router
[link-travis]: https://travis-ci.org/rosemlab/router
[link-scrutinizer]: https://scrutinizer-ci.com/g/rosemlab/router/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rosemlab/router
[link-downloads]: https://packagist.org/packages/rosemlab/router
[link-author]: https://github.com/roshecode
[link-contributors]: ../../contributors
