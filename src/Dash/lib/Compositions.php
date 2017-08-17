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

class Compositions {

	/**
	 * @param  callable ...$fns Variadic parameter
	 * @return callable
	 */
	public static function compose(callable ...$fns) {
		$prev = array_shift($fns);

		foreach ($fns as $fn) {
			$prev = function (...$args) use ($fn, $prev) {
				return $prev($fn(...$args));
			};
		}
		return $prev;
		//return self::pipe(...array_reverse($fns));
	}

	/**
	 * @param  callable ...$fns Variadic parameter
	 * @return callable
	 */
	public static function pipe(callable ...$fns) {
		/*$last = array_pop($fns);

		foreach ($fns as $fn) {
			$last = function(...$args) use ($fn, $last) {
				return $last($fn(...$args));
			};
		}

		return $last;*/
		return self::compose(...array_reverse($fns));
	}

	/**
	 * @param  callable $fn
	 * @param  array    $args
	 * @return callable
	 */
	public static function apply(callable $fn, array $args) {
		return $fn(...$args);
	}

	/**
	 * Reverse callable arity
	 * 
	 * @param  callable $fn
	 * @return callable
	 */
	public static function flip(callable $fn) {
		return function() use ($fn) {
			return $fn(...array_reverse(func_get_args()));
		}; 
	}

}
