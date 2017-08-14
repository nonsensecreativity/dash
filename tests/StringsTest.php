<?php
declare(strict_types = 1);

namespace NSC\Dash\Tests;

use NSC\Dash\Dash;
use NSC\Dash\Strings;
use PHPUnit\Framework\TestCase;

final class StringsTest extends TestCase {

	public function testCharAt() {
		$this->assertEquals('', Dash::charAt('Lorem ipsum dolor', -1));
		$this->assertEquals('o', Dash::charAt('foo', 1));
		$this->assertEmpty(Dash::charAt('foo', 99) );
		$this->assertEmpty(Dash::charAt('foo', -1));
		$this->assertEquals('', Strings\charAt('Lorem ipsum dolor', -1));
		$this->assertEquals('o', Strings\charAt('foo', 1));
		$this->assertEmpty(Strings\charAt('foo', 99) );
		$this->assertEmpty(Strings\charAt('foo', -1));
	}
}
