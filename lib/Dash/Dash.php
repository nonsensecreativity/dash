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

use function NSC\Dash\Strings\{snakeCase};

abstract class Dash {

	/**
	 * These native array functions have a reverse arity
	 * If called, will use the default one provided by the library
	 * 
	 * @var array REVERSE_ARRAY_ALIASES;
	 */
	const REVERSE_ARRAY_ALIASES = [
		'array_key_exists' => 'hasKey',
		'in_array'         => 'hasValue',
		'keyExists'        => 'hasKey',
		'inArray'   	   => 'hasValue'
	];

	/**
	 * These native functions have something todo with
	 * loop or data mutation, the library provided
	 * some compat for it. Empty value means not allowed
	 * 
	 * @var array BANNED_ARRAY_ALIASES;
	 */
	const BANNED_ARRAY_ALIASES = [
		'array_walk' => 'map',
		'walk' => 'map',
		'array_walk_recursive' => 'mapDeep',
		'walkRecursive' => 'mapDeep',
		'reset' => '',
		'prev' => '',
		'next' => '',
		'list' => '',
		'compact' => '',
		'extract' => '',
		'end' => ''
	];

	/**
	 * Calling functions through static binding
	 *
	 * @param  string $fn
	 * @param  array $args
	 * @return mixed
	 */
	public static function __callStatic(string $fn, array $args) {
		if ( empty( $data = $args[0] ) ) {
			throw new \LogicException();
		}
		
		$callable = self::findCallable($fn, gettype( $data ));
		return $callable(...$args);
	}


	/**
	 * Get callable from data type as hint
	 * 
	 * @param  string $key
	 * @param  string $type
	 * @return array|false
	 */
	public static function getFromType(string $key, string $type) {

		if ( is_callable("{$type}_{$key}") ) {
			return "{$type}_{$key}";
		}

		if ( is_callable("{$type}{$key}") ) {
			return "{$type}{$key}";
		}

		return false;
	}

	/**
	 * Map native type to callable type
	 *
	 * @param  string $type
	 * @return string
	 */
	public static function mapNativeType(string $type) {
		switch ($type) {
			case 'array':
			case 'object':
				return 'Iterables';
			case 'integer':
			case 'float':
			case 'double':
				return 'Numbers';
			default:
				return 'Strings';
		}
	}

	/**
	 * Find a callable method chain
	 * 
	 * @param string $key  Part of the maybe callable function
	 * @param string $type The native type as hint for the data type
	 */
	public static function findCallable(string $key, string $type = null) {
		$fn = __NAMESPACE__ . '\\' . self::mapNativeType($type) . '\\' . $key;

		if ( function_exists($fn) ) {
			return $fn;
		}

		// all PHP built-in functions use snake_case naming
		$key = snakeCase($key);
		$fn = false;

		if ( isset(self::REVERSE_ARRAY_ALIASES[$key] ) ) {
			$fn = self::REVERSE_ARRAY_ALIASES[$key];
		} else if ( !is_null($type) ) {
			$fn = self::getFromType($key, $type);
		}

		if ( !$fn ) {
			if ( function_exists( $key ) ) {
				$fn = $key;
			} else {
				foreach( ['str', 'array'] as $type ) {
					if ( $fn = self::getFromType($key, $type) ) {
						return $fn;
					}
				}
			}
		}

		return $fn;
	}

}
