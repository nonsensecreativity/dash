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

class Numbers {

	/**
	 * Regular expression for capturing roman number string
	 */
	const ROMAN_MATCHER = '/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

	/**
	 * Max number in roman
	 */
	const ROMAN_MAX_NUM = 3999;

	/**
	 * Roman Numbering map
	 */
	const ROMAN_MAPS = [
		'M'  => 1000,
		'CM' => 900,
		'D'  => 500,
		'CD' => 400,
		'C'  => 100,
		'XC' => 90,
		'L'  => 50,
		'XL' => 40,
		'X'  => 10,
		'IX' => 9,
		'V'  => 5,
		'IV' => 4,
		'I'  => 1,
	];

	/**
	 * Turn number into ROMAN string
	 *
	 * @param  int|float $n
	 * @return string
	 */
	public static function romanize(float $n) {
		if ( $n < 1 || $n > ROMAN_MAX_NUM ) {
			throw new \InvalidArgumentException();
		}

		$n   = intval($n);
		$str = '';

		while ($n > 0) {
			foreach (ROMAN_MAPS as $k => $v) {
				if ($n >= $v) {
					$str .= $k;
					$n -= $v;
					break;
				}
			}
		}

		return $str;
	}

	/**
	 * Math add
	 *
	 * @param int|float $n1
	 * @param int|float $n2
	 * @return int|float
	 */
	public static function add(float $n1, float $n2) {
		$v = $n1 + $n2;
		return self::areInt($n1, $n2) ? (int) $v : $v;
	}

	/**
	 * Math divide
	 *
	 * @param int|float $n1
	 * @param int|float $n2
	 * @return int|float
	 */
	public static function divide(float $n1, float $n2) {
		$v = $n1 / $n2;
		return self::areInt($n1, $n2) ? (int) $v : $v;
	}

	/**
	 * Math substract
	 *
	 * @param int|float $n1
	 * @param int|float $n2
	 * @return int|float
	 */
	public static function substract(float $n1, float $n2) {
		$v = $n1 - $n2;
		return self::areInt($n1, $n2) ? (int) $v : $v;
	}

	/**
	 * Summarize numbers
	 *
	 * @param int|float ...$ns
	 * @return int|float
	 */
	public static function sum(...$ns) {
		return array_sum($ns);
	}

	/**
	 * Get average
	 *
	 * @param int|float ...$ns
	 * @return int|float
	 */
	public static function average(...$ns) {
		return array_sum($ns) / count($ns);
	}

	/**
	 * Alias of average
	 *
	 * @param int|float ...$ns
	 * @return int|float
	 */
	public static function mean(...$ns) {
		return self::average(...$ns);
	}

	/**
	 * Checking numeric type for two input
	 * return true if both integer
	 * 
	 * @param  mixed $nums
	 * @return bool
	 */
	public static function areInt(...$nums) {
		foreach ( $nums as $num ) {
			if ( floor( $num ) > $num ) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Get median value from variadic input
	 *
	 * @param  int|float ...$ns
	 * @return int|float
	 */
	public static function median(...$ns) {
		sort($ns);
		$len = count($ns);
		$odd = $len % 2 !== 0;
		return $odd ? ( $ns[ $len / 2 ] )
					: ( $ns[ ($len - 1 ) / 2 ] + $ns[ $len / 2 ] ) / 2;
	}

	/**
	 * Check number is between range
	 *
	 * @param  int|float $n     The number
	 * @param  int|float $lower The lower boundary
	 * @param  int|float $upper The upper boundary
	 * @return bool
	 */
	public static function between(float $n, float $lower, float $upper): bool {
		return ( $n >= $lower ) && ( $n <= $upper );
	}

}
