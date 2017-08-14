<?php
declare(strict_types = 1);

namespace NSC\Dash\Tests;

use NSC\Dash\Dash;
use NSC\Dash\Strings;
use PHPUnit\Framework\TestCase;

final class StringsTest extends TestCase {

	public function testCharAt() {
		$str = 'Lorem ipsum dolor set amet';
		$this->assertEmpty(Dash::charAt($str, 99) );
		$this->assertEmpty(Dash::charAt($str, -1));
		$this->assertEquals('', Dash::charAt($str, -1));
		$this->assertEquals('', Dash::charAt($str, 99));
		$this->assertEquals('o', Dash::charAt($str, 1));
		$this->assertEmpty(Strings\charAt($str, 99) );
		$this->assertEmpty(Strings\charAt($str, -1));
		$this->assertEquals('', Strings\charAt($str, -1));
		$this->assertEquals('', Strings\charAt($str, 99));
		$this->assertEquals('o', Strings\charAt($str, 1));
		
	}

	public function testCharCodeAt() {
		$str = 'Lorem ipsum dolor set amet';
		$this->assertNan(Dash::charCodeAt($str, -1));
		$this->assertNan(Dash::charCodeAt($str, 99));
		$this->assertEquals(76, Dash::charCodeAt($str, 0));
		$this->assertNan(Strings\charCodeAt($str, -1));
		$this->assertNan(Strings\charCodeAt($str, 99));
		$this->assertEquals(111, Strings\charCodeAt($str, 1));
	}

	public function testIndexOf() {
		$str = 'Lorem ipsum dolor set amet';
		$this->assertLessThan(0, Dash::indexOf($str, 'xyz'));
		$this->assertEquals(12, Dash::indexOf($str, 'dolor'));
		$this->assertEquals(12, Dash::indexOf($str, 'd'));
		$this->assertEquals(12, Dash::indexOf($str, 'dol'));
	}
}
