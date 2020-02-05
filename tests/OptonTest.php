<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Kristos80\Opton\Opton;

final class OptonOutputTest extends TestCase {
	const DEFAULT_NAME = 'Athanasios';
	const VERSION_INDEX = 'version';
	const PHP_VER_SEVEN = '7';
	const JS_VER_EIGHT = '8';
	protected $poolOne = array(
		'name' => 'Chris',
		'age' => '40',
		'profession' => 'Web Developer',
		'languages' => array(
			'PHP' => array(
				self::VERSION_INDEX => self::PHP_VER_SEVEN,
			),
			'Javascript' => array(
				self::VERSION_INDEX => self::JS_VER_EIGHT,
			),
		),
	);
	protected $acceptedNames = array(
		'Iole',
		'Maria',
		self::DEFAULT_NAME,
	);

	public function testExpectChris(): void {
		$this->expectOutputString('Chris');

		echo Opton::get('name', $this->poolOne);
	}

	public function testExpectForty(): void {
		$this->expectOutputString('40');

		echo Opton::get(array(
			'age',
			'yearsOld',
		), (object) $this->poolOne);
	}

	public function testExpectDefaultNameInvoke(): void {
		$this->expectOutputString(self::DEFAULT_NAME);

		echo Opton::get('name', $this->poolOne, self::DEFAULT_NAME, $this->acceptedNames);
	}

	public function testExpectSeven(): void {
		$opton = new Opton();

		$this->expectOutputString(self::PHP_VER_SEVEN);

		echo Opton::get(self::VERSION_INDEX, $opton->get('PHP', $opton->get('languages', $this->poolOne)));
	}
}