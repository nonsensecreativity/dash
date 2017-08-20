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

use function NSC\Dash\Strings;
use FilesystemIterator as Fsi;
use Generator;
use BadMethodCallException;

abstract class Dash {

	/**
	 * Some aliases of native functions
	 * 
	 * @var array NATIVE_ALIASES;
	 */
	const NATIVE_ALIASES = [
		'array_key_exists'     => 'hasKey',
		'in_array'             => 'hasValue',
		'keyExists'            => 'hasKey',
		'inArray'              => 'hasValue',
		'array_walk'           => 'map',
		'walk'                 => 'map',
		'array_walk_recursive' => 'mapDeep',
		'walkRecursive'        => 'mapDeep'
	];

	/**
	 * These native functions have something todo with
	 * loop or data mutation, the library provided
	 * some compat for it. Empty value means not allowed
	 * 
	 * @var array BANNED_NATIVE_FUNCTION;
	 */
	const BANNED_NATIVE_FUNCTION = [
		'reset'   => '',
		'prev'    => '',
		'next'    => '',
		'list'    => '',
		'compact' => '',
		'extract' => '',
		'end'     => ''
	];

	/**
	 * Calling functions through static binding
	 *
	 * @param  string $key
	 * @param  array $args
	 * @return mixed
	 */
	public static function __callStatic(string $key, array $args) {
		$fn = static::findCallable(
			$key,
			count( $args ) > 0
				? static::getType($args[0])
				: NULL
		);

		if ( !is_callable( $fn ) ) {
			throw new BadMethodCallException();
		}

		return $fn(...$args);
	}

	/**
	 * Get the specific type from a value
	 *
	 * @param  mixed  $v
	 * @return string
	 */
	public static function getType($v) {
		if ( is_iterable($v) ) {
			return 'Iterables';
		}

		switch ( gettype($v) ) {
			case 'integer':
			case 'float':
			case 'double':
				return 'Numbers';
			case 'string':
				return 'Strings';
			default:
				return NULL;
		}
	}

	/**
	 * Find a callable method chain
	 * 
	 * @param  string $key Part of the maybe callable function
	 * @param  string $lib The library type
	 * @return false|callable
	 */
	protected static function findCallable(string $key, string $lib = NULL) {
		return (
			self::_checkFunction($lib, $key) ?:
			self::_checkDashFunction($key)   ?:
			self::_checkNativeFunction($key) ?:
			FALSE
		);
	}

	/**
	 * Maybe a dash function
	 *
	 * @param  string $key
	 * @return false|callable
	 */
	private static function _checkDashFunction(string $key) {
		foreach ( static::getLibs() as $lib ) {
			if ( is_callable( $fn = [ $lib, $key ] ) ) {
				return $fn;
			}
		}
		return FALSE;
	}

	/**
	 * Maybe a native function
	 *
	 * @param  string $key
	 * @return false|callable
	 */
	private static function _checkNativeFunction(string $key) {
		$key = Strings::snakeCase($key);
		foreach( ['str_', 'array_'] as $lib ) {
			if ( is_callable( $fn = "{$lib}{$key}" ) ) {
				return $fn;
			}
		}
		return FALSE;
	}

	/**
	 * Check function by specifying lib namespace and key
	 *
	 * @param  string $lib
	 * @param  string $key
	 * @return false|callable
	 */
	private static function _checkFunction(string $lib, string $key) {
		$ns = __NAMESPACE__;
		return is_callable( $fn = [ "{$ns}\\{$lib}", $key ] ) ? $fn : FALSE;
	}

	/**
	 * Get list of libraries
	 *
	 * @return \Generator
	 */
	public static function getLibs(): Generator {
		$files = new Fsi(
			realpath( __DIR__ . DIRECTORY_SEPARATOR . 'lib' ),
			Fsi::SKIP_DOTS | Fsi::FOLLOW_SYMLINKS
		);

		foreach ( $files as $file ) {
			$basename = $file->getBasename('.php');
			yield $basename => __NAMESPACE__ . "\\{$basename}";
		}
	}

	/**
	 * Create a new sequence instance for immediate chaining
	 * because calling new Sequence($v) aren't chainable
	 *
	 * @param  mixed $value
	 * @return \Dash\Sequence
	 */
	public static function seq($value) {
		return new Sequence($value);
	}

	/**
	 * Get a method
	 *
	 * @param  array|string $v
	 * @return callable
	 */
	public static function get($v) {
		if ( is_callable( [self::class, $v] ) ) {
			return [self::class, $v];
		}
		
		if ( is_callable($v) ) {
			return $v;
		}
	}
}
