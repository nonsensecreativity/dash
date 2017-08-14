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
namespace NSC\Dash\Compositions;

/**
 * @param  callable ...$fns Variadic parameter
 * @return callable
 */
function compose(callable ...$fns) {
    $prev = array_shift($fns);

    foreach ($fns as $fn) {
        $prev = function (...$args) use ($fn, $prev) {
            return $prev($fn(...$args));
        };
    }

    return $prev;
}

/**
 * @param  callable ...$fns Variadic parameter
 * @return callable
 */
function pipe(callable ...$fns) {
    return compose(...array_reverse($fns));
}

/**
 * @param  callable $fn
 * @param  array    $args
 * @return callable
 */
function apply(callable $fn, array $args) {
    return $fn(...$args);
}

/**
 * Reverse callable arity
 * 
 * @param  callable $fn
 * @return callable
 */
function flip(callable $fn) {
	return function() use ($fn) {
        return $fn(...array_reverse(func_get_args()));
    }; 
}
