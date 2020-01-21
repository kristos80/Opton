<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Kristos80\Opton\Opton;

final class OptonOutputTest extends TestCase {
	const DEFAULT_NAME = 'Athanasios';
	protected $poolOne = array(
		'name' => 'Chris',
		'age' => 40,
		'profession' => 'Web Developer',
	);
	protected $acceptedNames = array(
		'Iole',
		'Maria',
		self::DEFAULT_NAME,
	);

	public function testExpectChris(): void {
		$this->expectOutputString('Chris');

		echo (new Opton())->get('name', $this->poolOne);
	}

	public function testExpectForty(): void {
		$this->expectOutputString('40');

		echo (new Opton())->get(array(
			'age',
			'yearsOld',
		), (object) $this->poolOne);
	}

	public function testExpectInvalidName(): void {
		$this->expectOutputString(self::DEFAULT_NAME);

		echo (new Opton())->get('name', $this->poolOne, self::DEFAULT_NAME, $this->acceptedNames);
	}
}