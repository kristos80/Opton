[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=kristos80_Opton&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=kristos80_Opton)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=kristos80_Opton&metric=security_rating)](https://sonarcloud.io/dashboard?id=kristos80_Opton)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=kristos80_Opton&metric=sqale_index)](https://sonarcloud.io/dashboard?id=kristos80_Opton)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=kristos80_Opton&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=kristos80_Opton)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=kristos80_Opton&metric=bugs)](https://sonarcloud.io/dashboard?id=kristos80_Opton)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3d8b12dc518945688a4d5a86f1797fc8)](https://www.codacy.com/manual/kristos80/Opton?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kristos80/Opton&amp;utm_campaign=Badge_Grade) 
[![Maintainability](https://api.codeclimate.com/v1/badges/b270ce7780ab822b411b/maintainability)](https://codeclimate.com/github/kristos80/Opton/maintainability) 
[![CodeScene Code Health](https://codescene.io/projects/6894/status-badges/code-health)](https://codescene.io/projects/6894) 
[![CodeScene System Mastery](https://codescene.io/projects/6894/status-badges/system-mastery)](https://codescene.io/projects/6894)
# Opton

Easily get a value from an array or object without all of those unnecessary `array_key_exists` or `isset` controls.

## Install (via composer)
```CLI
$ composer require kristos80/opton
```

## Source code 
```PHP
/**
 *
 * @param array|object|string $name
 *        	The name of the option/key to be found
 * @param array|object|NULL $pool
 *        	The pool of data to search within
 * @param mixed|NULL $default
 *        	Default value if nothing is found
 * @param array|object|NULL $acceptedValues
 *        	Array of accepted values. It affects even the `default` value
 * @return mixed|NULL
 */
static function get($name, $pool = array(), $default = NULL, array $acceptedValues = array()) {
```

## Examples
```PHP
<?php
require_once 'vendor/autoload.php';

use Kristos80\Opton\Opton;

$options = array(
	'optionName' => 'optionValue',
);

$acceptedValues = array(
	'optionValue',
);

// prints 'optionValue'
echo Opton::get('optionName', $options, NULL, $acceptedValues);
echo "\r\n";

// prints `NULL`
echo Opton::get('optionNameNonExistent', $options);
echo "\r\n";

// prints 'defaultValue'
echo Opton::get('optionNameNonExistent', $options, 'defaultValue');
echo "\r\n";

// prints `NULL`
echo Opton::get('optionNameNonExistent', $options, 'defaultValue', $acceptedValues);
echo "\r\n";

// `name` can be an array 
// prints 'optionValue'
echo Opton::get(array(
	'optionName',
	'optionNameNonExistent',
), $options);
echo "\r\n";

// Use a single configuration array with keys:
//	`name`
// 	`pool`
//	`default`
//	`acceptedValues`
//
// prints 'defaultValue'
echo Opton::get(array(
	'name' => 'optionNameNonExistent',
	'pool' => $options,
	'default' => 'defaultValue',
));
echo "\r\n";
```

## Run tests
```cli
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/OptonTest
```
