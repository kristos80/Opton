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

WIP (Work In Progress) **DO NOT USE IN PRODUCTION**

Example:
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

// prints `NULL`
echo Opton::get('optionNameNonExistent', $options);

// prints 'defaultValue'
echo Opton::get('optionNameNonExistent', $options, 'defaultValue');

// prints `NULL`
echo Opton::get('optionNameNonExistent', $options, 'defaultValue', $acceptedValues);
```

## Run test
```cli
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/OptonTest
```
