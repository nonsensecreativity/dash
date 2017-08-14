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

use function NSC\Dash\Universal\{isDecimal};
use function NSC\Dash\Numbers\{between};

/**
 * Get the character in index from string.
 * Return empty if index out of range
 * 
 * @param  string  $str
 * @param  int     $index  Default index is 0
 * @return string  Return empty if index is out of range.
 */
function charAt(string $str, int $index = 0): string {
	return !between( $index, 0, strlen($str) - 1 ) ? '' : $str{$index};
}

/**
 * Get the ASCII code for character in string.
 * Return empty if index out of range
 *
 * @param  string  $str
 * @param  int     $index
 * @return int|NAN Return integer representing ASCII code or NAN if out of range
 */
function charCodeAt(string $str, int $index = 0) {
	return !between( $index, 0, strlen($str) - 1 ) ? NAN : ord($str{$index});
}

/**
 * Return the indeox of first occurrence of the needle
 *
 * @param  string $haystack
 * @param  string $needle
 * @return int
 */
function indexOf(string $haystack, string $needle): int {
	$index = strpos($haystack, $needle);
	return $index !== FALSE ? $index : -1;
}

/**
 * Return the index of the last occurrence on the string
 *
 * @param string $haystack
 * @param string $needle
 * @return void
 */
function lastIndexOf(string $haystack, string $needle): int {
	$index = strrpos($haystack, $needle);
	return $index !== FALSE ? $index : -1;
}

/**
 * Return false when input string contains characters
 * not in the specified characters
 *
 * @param  string $str
 * @param  string ...$chars
 * @return bool
 */
function containsOnly(string $str, string ...$chars): bool {
	foreach ($chars as $c) {
		if ( strpos( $str, $c ) === FALSE ) {
			return FALSE;
		}
	}
	
	return TRUE;
}

/**
 * Return true if the string contains none of the characters specified
 *
 * @param  string $str
 * @param  string ...$chars
 * @return bool
 */
function containsNone(string $str, string ...$chars): bool {
	return !containsAny($str, ...$chars);
}

/**
 * Check whether a string contains any of the characters specified
 * Return true if string contains at least one of the specified characters
 * 
 * @example
 * <code>
 * use function Dash\Strings\{containsAny};
 * containsAny('mystring', 'a', 'b', 'c', 'd', 'y' );
 * </code>
 * 
 * @param  string $str
 * @param  string ...$chars
 * @return bool
 */
function containsAny(string $str, string ...$chars): bool {
	foreach ( $chars as $c ) {
		if ( strpos( $str, $c ) !== FALSE ) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Truncate a string by adding an ending mark
 * 
 * @param  string  $str
 * @param  int     $limit Max character, including whitespace. Default to 200
 * @param  string  $end   Default '...' Ellipsis
 * @return string
 */
function truncate(string $str, int $limit = 200, $end = '...'): string {
	$s = explode(' ', $str, $limit + 1);
	array_pop($s);
	return implode(' ', $s) . $end;
}

/**
 * Return length of string
 * 
 * @param  string $str
 * @return int
 */
function length(string $str): int {
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

/**
 * Turn string into list of ASCII codes
 *
 * @param  string $str
 * @return array
 */
function toASCII(string $str): array {
	$ascii = NULL;
	foreach ( str_split($str) as $c ) {
		$ascii[] = ord($c);
	}
	return $ascii;
}

/**
 * Convert string into pascal case
 * 
 * @example
 * \Dash\Strings\pascalCase('my_snake_case') => MySnakeCase
 * 
 * @param  string $str
 * @return string
 */
function pascalCase(string $str): string {
	return str_replace(" ", "", ucwords(strtr($str, "_-", "  ")));
}

/**
 * Convert string into camel case
 * 
 * basically it's a PascalCase with lower case first character
 * 
 * @example
 * \Dash\Strings\camelCase('my_snake_case') => mySnakeCase
 * 
 * @param  string $str
 * @return string
 */
function camelCase(string $str): string {
	return lcfirst(pascalCase($str));
}

/**
 * Convert string into snake case
 * 
 * @example
 * \Dash\Strings\snakeCase('MyPascalCase') => my_snake_case
 * 
 * @param  string $str
 * @return string
 */
function snakeCase(string $str): string {
	return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $str));
}

/**
 * Convert directory separator into url separator
 *
 * @param  string $str
 * @param  bool   $trail Add trailing separator. Default to FALSE
 * @return string
 */
function urlSep(string $str, bool $trail = false): string {
	$url = str_replace( ['\\', '/' ], '/', $str );
	return $trail ? $url . '/' : $url;
}

/**
 * Convert directory separator using DIRECTORY_SEPARATOR constant
 *
 * @param string  $str
 * @param bool    $trail Add trailing separator. Default to FALSE
 * @return string
 */
function dirSep(string $str, bool $trail = false): string {
	$dir = str_replace( ['\\', '/'], DIRECTORY_SEPARATOR, $str );
	return $trail ? $dir . DIRECTORY_SEPARATOR : $dir;
}
