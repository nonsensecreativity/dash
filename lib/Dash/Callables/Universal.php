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
namespace NSC\Dash\Universal;

/**
 * Check value is decimal
 *
 * @param string|int|float $n
 * @return boolean
 */
function isDecimal($n) {
	if ( is_string($n) ) {
		return (stripos( $n, '.' ) !== FALSE);
	}

	return floor($n) !== $n;
}
