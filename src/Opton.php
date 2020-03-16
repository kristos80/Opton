<?php
declare(strict_types = 1);

/*
 * Copyright 2020 Christos Athanasiadis <christos.k.athanasiadis@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
namespace Kristos80\Opton;

/**
 *
 * @author Christos Athanasiadis <christos.k.athanasiadis@gmail.com>
 * @license https://www.opensource.org/licenses/mit-license.php
 */
final class Opton {
	/**
	 *
	 * @var string CONF_NAME
	 */
	const CONF_NAME = 'name';

	/**
	 *
	 * @var string CONF_POOL
	 */
	const CONF_POOL = 'pool';

	/**
	 *
	 * @var string CONF_DEFAULT
	 */
	const CONF_DEFAULT = 'default';

	/**
	 *
	 * @var string CONF_ACCEPTED_VALUES
	 */
	const CONF_ACCEPTED_VALUES = 'acceptedValues';

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
		$configuration = self::getConfiguration($name, $pool, $default, $acceptedValues);
		$name = $configuration[self::CONF_NAME];
		$pool = $configuration[self::CONF_POOL];
		$default = $configuration[self::CONF_DEFAULT];
		$acceptedValues = $configuration[self::CONF_ACCEPTED_VALUES];

		if (count($notationParts = explode('.', $name)) > 1) {
			return self::getByNotation($notationParts, $pool, $default, $acceptedValues);
		}

		$option = NULL;
		if (is_array($name)) {
			$option = self::searchArrayName($name, $pool) ?: $option;
		}

		if (! $option && ! is_array($name)) {
			$option = array_key_exists($name, $pool) ? $pool[$name] : $default;
		}

		if (count($acceptedValues)) {
			$option = self::validateOption($option, $acceptedValues, $default);
		}

		return $option;
	}

	/**
	 *
	 * @param array $notationParts
	 * @param array|object $pool
	 * @param mixed|NULL $default
	 * @param array|object|NULL $acceptedValues
	 * @return mixed|NULL
	 */
	private static function getByNotation(array $notationParts, $pool, $default = NULL, array $acceptedValues = array()) {
		$notationPartsCounter = 1;
		$pool_ = self::get($notationParts[0], $pool);
		while ($notationPartsCounter < count($notationParts) - 1 && $pool_) {
			$pool_ = self::get($notationParts[$notationPartsCounter], $pool_);

			$notationPartsCounter ++;
		}

		return self::get($notationParts[$notationPartsCounter], $pool_, $default, $acceptedValues);
	}

	/**
	 *
	 * @param mixed $option
	 * @param array $acceptedValues
	 * @param mixed $default
	 * @return mixed|NULL
	 */
	private static function validateOption($option, array $acceptedValues, $default) {
		if (! in_array($option, $acceptedValues)) {
			if (! in_array($default, $acceptedValues)) {
				$default = NULL;
			}

			$option = $default;
		}

		return $option;
	}

	/**
	 *
	 * @param array|object|string $name
	 * @param array|object|NULL $pool
	 * @param mixed|NULL $default
	 * @param array|object|NULL $acceptedValues
	 * @return array
	 */
	private static function getConfiguration($name, $pool, $default = NULL, array $acceptedValues = array()): array {
		if (is_array($name) || is_object($name)) {
			$configuration = (array) $name;
			if ($name_ = self::get(self::CONF_NAME, $configuration) && $pool = self::get(self::CONF_POOL, $configuration)) {
				$name = $name_;
				$default = self::get(self::CONF_DEFAULT, $configuration, $default);
				$acceptedValues = self::get(self::CONF_ACCEPTED_VALUES, $configuration, $acceptedValues);
			}
		}

		return array(
			self::CONF_NAME => self::normalizeName($name),
			self::CONF_POOL => (array) $pool,
			self::CONF_DEFAULT => $default,
			self::CONF_ACCEPTED_VALUES => self::normalizeAcceptedValues($acceptedValues),
		);
	}

	/**
	 *
	 * @param array|object|string $name
	 * @return string|array
	 */
	private static function normalizeName($name) {
		if (! is_array($name) && ! is_object($name) && ! is_string($name) && ! is_numeric($name)) {
			$name = (string) serialize($name);
		}

		if (is_object($name)) {
			$name = array_values((array) $name);
		}

		return $name;
	}

	/**
	 *
	 * @param array|object|NULL $acceptedValues
	 * @return array
	 */
	private static function normalizeAcceptedValues($acceptedValues) {
		return (is_array($acceptedValues) || is_object($acceptedValues)) ? array_values((array) $acceptedValues) : array();
	}

	/**
	 *
	 * @param array $name
	 * @param array $pool
	 * @return mixed|NULL
	 */
	private static function searchArrayName($name, $pool) {
		foreach ($name as $possibleName) {
			if (array_key_exists($possibleName, $pool)) {
				return $pool[$possibleName];
			}
		}

		return NULL;
	}
}