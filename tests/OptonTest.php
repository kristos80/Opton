<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Kristos80\Opton\Opton;

final class OptonOutputTest extends TestCase {
	protected $pool = array(
		'name' => 'Chris',
		'age' => 40,
		'profession' => 'Web Developer',
	);

	public function testExpectChris(): void {
		$this->expectOutputString('Chris');

		echo (new Opton())->get('name', $this->pool);
	}
}