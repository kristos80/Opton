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
	const CONF_NAME = self::CONF_NAME;
	const CONF_POOL = self::CONF_POOL;
	const CONF_DEFAULT = self::CONF_DEFAULT;
	const CONF_ACCEPTED_VALUES = self::CONF_ACCEPTED_VALUES;

	public function __invoke($name, $pool = array(), $default = NULL, array $acceptedValues = array()) {
		return $this->get($name, $pool, $default, $acceptedValues);
	}

	public function get($name, $pool = array(), $default = NULL, array $acceptedValues = array()) {
		$configuration = $this->getConfiguration($name, $pool, $default, $acceptedValues);
		$name = $configuration[self::CONF_NAME];
		$pool = $configuration[self::CONF_POOL];
		$default = $configuration[self::CONF_DEFAULT];
		$acceptedValues = $configuration[self::CONF_ACCEPTED_VALUES];

		$option = NULL;
		if (is_array($name)) {
			$option = $this->searchArrayName($name, $pool) ?: $option;
		}

		if (! $option) {
			$option = array_key_exists($name, $pool) ? $pool[$name] : $default;
		}

		if (count($acceptedValues)) {
			$option = $this->validateOption($option, $acceptedValues, $default);
		}

		return $option;
	}

	private function validateOption($option, $acceptedValues, $default) {
		if (! in_array($option, $acceptedValues)) {
			if (! in_array($default, $acceptedValues)) {
				$default = NULL;
			}

			$option = $default;
		}

		return $option;
	}

	private function getConfiguration($name, $pool, $default = NULL, array $acceptedValues = array()): array {
		if (is_array($name) || is_object($name)) {
			$configuration = (array) $name;
			if (isset($configuration[self::CONF_NAME]) && isset($configuration[self::CONF_POOL])) {
				$name = $configuration[self::CONF_NAME];
				$pool = $configuration[self::CONF_POOL];
				$default = isset($configuration[self::CONF_DEFAULT]) ? $configuration[self::CONF_DEFAULT] : $default;
				$acceptedValues = isset($configuration[self::CONF_ACCEPTED_VALUES]) ? $configuration[self::CONF_ACCEPTED_VALUES] : $acceptedValues;
			}
		}

		return array(
			self::CONF_NAME => $this->normalizeName($name),
			self::CONF_POOL => (array) $pool,
			self::CONF_DEFAULT => $default,
			self::CONF_ACCEPTED_VALUES => $this->normalizeAcceptedValues($acceptedValues),
		);
	}

	private function normalizeName($name) {
		if (! is_array($name) && ! is_object($name) && ! is_string($name) && ! is_numeric($name)) {
			$name = (string) serialize($name);
		}

		if (is_object($name)) {
			$name = array_values((array) $name);
		}

		return $name;
	}

	private function normalizeAcceptedValues($acceptedValues) {
		return (is_array($acceptedValues) || is_object($acceptedValues)) ? array_values((array) $acceptedValues) : array();
	}

	private function searchArrayName($name, $pool) {
		foreach ($name as $possibleName) {
			if (array_key_exists($possibleName, $pool)) {
				return $pool[$possibleName];
			}
		}

		return NULL;
	}
}