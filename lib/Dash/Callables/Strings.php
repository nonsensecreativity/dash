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
namespace NSC\Dash\Strings;

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
 * Return the indeox of first occurrence of the needle
 *
 * @param  string $haystack
 * @param  string $needle
 * @return int
 */
function indexOf(string $haystack, string $needle) {
	return strpos($haystack, $needle);
}

/**
 * Return the index of the last occurrence on the string
 *
 * @param string $haystack
 * @param string $needle
 * @return void
 */
function lastIndexOf(string $haystack, string $needle) {
	return strrpos($haystack, $needle);
}

/**
 * Return false when input string contains characters
 * not in the specified characters
 *
 * @param  string $str
 * @param  string ...$chars
 * @return boolean
 */
function containsOnly(string $str, string ...$chars) {
	$arr = str_split( $str );
	return empty( array_diff( $arr, $chars ) );
}

/**
 * Return true if the string contains none of the characters specified
 *
 * @param  string $str
 * @param  string ...$chars
 * @return boolean
 */
function containsNone(string $str, string ...$chars) {
	return !containsAny($str, ...$chars);
}

/**
 * Check whether a string contains any of the characters specified
 * Return true if string contains at least one of the specified characters
 * 
 * @example
 * <code>
 * use function Dash\Callables\Strings\{containsAny};
 * containsAny('mystring', 'a', 'b', 'c', 'd', 'y' );
 * </code>
 * 
 * @param  string $str
 * @param  string ...$chars
 * @return boolean
 */
function containsAny(string $str, string ...$chars) {
	foreach ( $chars as $c ) {
		if ( stripos( $str, $c ) !== FALSE ) {
			return TRUE;
		}
	}
	return FALSE;
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
