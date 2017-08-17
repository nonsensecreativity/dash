<?php
declare(strict_types = 1);
/*
 * This file is part of the Dash package.
 *
 * (c) Hermanto Lim <nonsensecreativity@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NSC\Dash;

class Conditions {
	/**
	 * Check input value for decimal posibility
	 *
	 * @param  mixed $num
	 * @return bool
	 */
	public static function isDecimal($num): bool {
		return floor((float) $num) !== (float) $num;
	}
}

