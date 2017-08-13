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
namespace NSC\Dash\Callables\Strings;

use function NSC\Dash\Callables\Universal\{isDecimal};

/**
 * Convert string into pascal case
 * 
 * @example
 * \Dash\Callables\Strings\pascalCase('my_snake_case') => MySnakeCase
 * 
 * @param  string $str
 * @return string
 */
function pascalCase(string $str) {
	return str_replace(" ", "", ucwords(strtr($str, "_-", "  ")));
}

/**
 * Convert string into camel case
 * 
 * basically it's a PascalCase with lower case first character
 * 
 * @example
 * \Dash\Callables\Strings\camelCase('my_snake_case') => mySnakeCase
 * 
 * @param  string $str
 * @return string
 */
function camelCase(string $str) {
	return lcfirst(pascalCase($str));
}

/**
 * Convert string into snake case
 * 
 * @example
 * \Dash\Callables\Strings\snakeCase('MyPascalCase') => my_snake_case
 * 
 * @param  string $str
 * @return string
 */
function snakeCase(string $str) {
	return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $str));
}

/**
 * Find index of string
 * 
 * @param  string $str
 * @return string
 */
function indexOf(string $str) {

}

function lastIndexOf(string $str) {

}

function containsOnly(string $str, ...$chars) {

}

function containsNone(string $str, ...$chars) {

}

function containsAny(string $str, ...$chars) {

}

/**
 * Get the character in index from string
 * 
 * @param  string  $str
 * @param  int     $index  Default index is 0
 * @return string  Return empty if index is out of range.
 */
function charAt(string $str, int $index = 0) {
	if ( strlen($str) > $index ) {
		return '';
	}

	return substr($str, $index, 1);
}

/**
 * Truncate a string by adding an ending mark
 * 
 * @param  string  $str
 * @param  int     $max  Max character, including whitespace. Default to 200
 * @param  string  $mark Default '...' Ellipsis
 * @return string
 */
function truncate(string $str, int $max = 200, $mark = '...') {
	return substr($str, 0, $max) . $mark;
}

/**
 * Return length of string
 * 
 * @param  string $str
 * @return int
 */
function length(string $str) {
	return strlen($str);
}

/**
 * Make string to numeric type
 *
 * @param  string $str
 * @return int|float
 */
function toNumber(string $str) {
	return isDecimal($str) ? (float) $str : (int) $str;
}
