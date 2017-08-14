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

namespace NSC\Dash\Iterables;

use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use InvalidArgumentException;

/**
 * @internal Base traversal function
 * 
 * @param  iterable  $itr     The iterable data array|\Traversable
 * @param  callable  $fn      fn( mixed $value, string|int $key, iterable $iterable ): mixed
 * 						      The callback to be applied to each iterable value
 * @param  bool      $filter  Filter or transform
 * @param  int 		 $lvl 	  The depth level. -1 For infinite
 * @return array
 */
function _traverse(iterable $itr, callable $fn, bool $filter, int $lvl = -1): array {
	$o = NULL;

	foreach( $itr as $k => $v ) {
		if ( is_iterable($v) && $lvl !== 0 ) {
			$o[$k] = _traverse($v, $fn, $filter, $lvl - 1);
		} else {
			if ( $filter ) {
				if ( $fn($v, $k, $itr) === TRUE ) {
					$o[$k] = $v;
				}
			} else {
				$o[$k] = $fn( $v, $k, $itr );
			}
		}
	}

	return $o;
}


/**
 * Reversal iterations
 * 
 * @internal
 * @uses   \Dash\Iterable\toArray()
 * @param  iterable $itr
 * @param  int      $lvl
 * @return array
 */
function _reverse(iterable $itr, int $lvl): array {
	$arr = is_array($itr) ? $itr : toArray( $itr, $lvl !== 0 );
	
	if ( $lvl !== 0 ) {
		foreach ( $arr as $k => $v ) {
			if ( is_iterable($v) ) {
				$arr[$k] = _reverse($v, $lvl - 1);
			}
		}
	}

	return array_reverse($arr);
}

/**
 * Apply a transformation to each iterable value
 * 
 * @uses   \Dash\Iterable\_traverse()
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function map(iterable $itr, callable $fn): array {
	return _traverse($itr, $fn, false, 0);
}

/**
 * Apply a transformation to each iterable value
 * 
 * @uses   \Dash\Iterable\_traverse()
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function mapDeep(iterable $itr, callable $fn): array {
	return _traverse($itr, $fn, false, -1);
}

/**
 * Filters elements of an array using a callback function
 * 
 * @uses   \Dash\Iterable\_traverse()
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function filter(iterable $itr, callable $fn): array {
	return _traverse($itr, $fn, true, 0);
}

/**
 * Recursively Filters elements of an array using a callback function
 * 
 * @uses   \Dash\Iterable\_traverse()
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function filterDeep(iterable $itr, callable $fn): array {
	return _traverse($itr, $fn, true, -1);
}

/**
 * Array reducer
 * 
 * @param  iterable $itr
 * @param  callable $fn  The transformer callback
 * 						 fn(
 * 							mixed $accumulator,
 * 							mixed $value,
 * 							string|int $key,
 * 							iterable $iterable
 * 						 ): mixed
 * @param  mixed 	$acc The accumulutor
 * @return mixed 	$acc The accumulated value
 */
function reduce(iterable $itr, callable $fn, $acc) {
	foreach($itr as $k => $v) {
		$acc = $fn($acc, $v, $k, $itr);
	}
	return $acc;
}

/**
 * Array reduce recursive
 * 
 * @param  iterable $itr
 * @param  callable $fn  The transformer callback
 * 						 fn(
 * 							mixed $accumulator,
 * 							mixed $value,
 * 							string|int $key,
 * 							iterable $iterable
 * 						 ): mixed
 * 
 * @param  mixed 	$acc The accumulutor
 * @return mixed 	$acc The accumulated value
 */
function reduceDeep(iterable $itr, callable $fn, $acc) {
	foreach($itr as $k => $v) {
		if ( is_iterable($v) ) {
			$acc[$k] = reduceDeep($v, $fn, $acc[$k]);
		} else {
			$acc = $fn($acc, $v, $k, $itr);
		}
	}
	return $acc;
}

/**
 * Array reverse non recursive
 * 
 * @uses   \Dash\Iterables\_reverse()
 * @param  iterable $itr
 * @return array
 */
function reverse(iterable $itr): array {
	return _reverse($itr, 0);
}

/**
 * Array reverse resursive infinitely
 * 
 * @uses   \Dash\Iterables\_reverse()
 * @param  iterable $itr
 * @return array
 */
function reverseDeep(iterable $itr): array {
	return _reverse($itr, -1);
}

/**
 * Flatten nested array into single level array
 * 
 * @uses   \Dash\Iterable\toIteratorI()
 * @param  iterable $itr
 * @return array
 */
function flatten(iterable $itr): array {
	$o = NULL;
	foreach (toIteratorI($itr) as $v ) {
		$o[] = $v;
	}
	return $o;
}

/**
 * Flatten nested keyed array
 * into single level array.
 * 
 * All child key will be concatenated
 * with dot delimiter with the key of their parent
 * 
 * @param  iterable $itr
 * @return array
 */
function flattenAssoc(iterable $itr): array {
	$o = NULL;
	foreach ( $itr as $k0 => $v0 ) {
		if ( is_iterable( $v0 ) ) {
			foreach( flattenAssoc($v0) as $k1 => $v1 ) {
				$o["{$k0}.{$k1}"] = $v1;
			} 
		} else {
			$o[$k0] = $v0;
		}
	}
	return $o;
}

/**
 * Map an array by applying a transformation
 * callback from the provided arguments
 * then merge the emitted result
 * 
 * @param  iterable $itr
 * @param  callable $fn  Callback function
 * 						 fn( mixed $value, string|int $key, iterable $iterable ): mixed
 * @return array
 */
function flatMap(iterable $itr, callable $fn): array {
	$o = NULL;

	foreach ( $itr as $k => $v ) {
		$r = $fn($v, $k, $itr);
		if( is_iterable($r) ) {
			foreach ( $r as $rv ) {
				$o[] = $rv;
			}
		} else if ( $r !== NULL ) {
			$o[] = $r;
		}
	}

    return $o;
}

/**
 * Grouping iterable with callback to determine group key and depth
 * 
 * @param  iterable $itr
 * @param  callable $fn
 * @param  int      $depth
 * @return array
 */
function group(iterable $itr, callable $fn, int $depth = 0): array {
	$o = NULL;

	foreach( $itr as $k => $v ) {

		if ( is_iterable( $v ) && $depth > 0 ) {

			$o[$k] = group( $v, $fn, $depth - 1 );
			
		} else {

			$g = $fn($v, $k, $itr);
			$g = !empty($g) ? $g : (string) $g;
			
			if ( !isset( $o[$g] ) ) {
	            $o[$g] = [];
	        }

	        $o[$g][] = $v;
		}
	}

    return $o;
}

/**
 * Grouping nested Iterable into it's type
 * 
 * @uses   \Dash\Iterable\toIteratorI()
 * @param  iterable $itr
 * @return array
 */
function groupByDepth(iterable $itr): array {
	$rii = toIteratorI($itr);
	$o = NULL;
	foreach ( $rii as $k => $v ) {
		$o[$rii->getDepth()][] = $v;
	}
	return $o;
}

/**
 * Grouping iterable by parent depth
 * 
 * @uses   \Dash\Iterable\toIteratorI()
 * @param  iterable $itr
 * @param  string   $childPointer The array key holding the values of the children
 * @return array 
 */
function groupByParent(iterable $itr, string $childPointer = 'children' ): array {
	$rii = toIteratorI($itr, RecursiveIteratorIterator::SELF_FIRST );
	$o = NULL;
	foreach( $rii as $k => $v ) {
		if ( $k !== $childPointer ) {
			continue;
		}
		$p = $rii->getSubIterator($rii->getDepth() - 1);
		$o[$rii->getDepth() / 2][$p->key()] = array_keys($v);
	}
	return $o;
}

/**
 * Rotate a two-dimensional iterable
 * 
 * @param  iterable $itr
 * @return array
 */
function transpose(iterable $itr): array {
	$o = NULL;
	foreach ($itr as $k0 => $v0 ) {
		if (is_iterable($v0)) {
			foreach ( $v0 as $k1 => $v1 ) {
				$o[$k1][$k0] = $v1;
			}
		} else {
			$o[] = $v0;
		}
	}
	return $o;
}

/**
 * Check if iterable contain value from specified needle
 * 
 * @param  iterable $itr
 * @param  mixed    $needle
 * @return bool
 */
function hasValue(iterable $itr, $needle): bool {
	foreach ($itr as $v) {
		if ($needle === $v) {
			return TRUE;
		}
	}
	return FALSE;
}

/**
 * Check if iterable has key
 * 
 * @param  iterable $itr
 * @param  scalar   $key
 * @return bool
 */
function hasKey(iterable $itr, $key) {
	if ( !is_scalar($key)) {
		return FALSE;
	}

	return isset($itr[$key]);
}

/**
 * Check if iterable has keys by walk
 * the iterable with each of the key
 * 
 * @param  iterable $itr
 * @param  array    $keys
 * @return bool
 */
function hasKeys(iterable $itr, array $keys) {
	$current = $itr;

	foreach ($keys as $key) {
        if (!isset($current[$key])) {
            return FALSE;
        }

        $current = $current[$key];
    }

    return $current;
}

/**
 * Insertion
 * 
 * @param  array $arr
 * @param  int   $idx
 * @param  mixed $val
 * @return array
 */
function insert(array $arr, int $idx, $val): array {
  	return array_merge(array_splice($arr, 0, $idx), $val, $arr);
}

/**
 * Insert into first position
 * 
 * @param  array $arr
 * @param  mixed $val
 * @return array
 */
function prepend(array $arr, $val): array {
	return array_merge([$val], $arr);
}

/**
 * Insert into last position
 * 
 * @param  array $arr
 * @param  mixed $val
 * @return iterable
 */
function append(array $arr, $val): array {
	return array_merge($arr, [$val]);
} 

/**
 * Remove duplicate value from iterable
 * 
 * @param  array $arr
 * @return array
 */
function unique(array $arr): array {
	return array_keys(array_flip($arr));
}

/**
 * Mirror iterable value until a specified condition becomes FALSE
 * 
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function takeWhile(iterable $itr, callable $fn): array {
	$o = NULL;
	foreach ($itr as $k => $v) {
		if ( $fn($v, $k, $itr) !== TRUE ) {
			break;			
		}
		$o[$k] = $v;
    }
    return $o;
}


/**
 * reverse iterable and take value until a specified condition becomes FALSE
 * 
 * @param  iterable $itr
 * @param  callable $fn
 * @return array
 */
function takeLastWhile(iterable $itr, callable $fn): array {
	$o = NULL;
	for( end($itr); key($itr) !== NULL; prev($itr) ) {
		$v = method_exists( $itr, 'current' )
				? $itr->current()
				: current($v);

		if ( $fn($v, $k, $itr) !== TRUE ) {
			break;
		}
		$o[] = $v;
	}
	return $o;
}

/**
 * Take value start from index
 * 
 * @param  iterable $itr
 * @param  int      $idx
 * @return array
 */
function take(iterable $itr, int $idx): array {
	$arr = !is_array($itr) ? toArray($itr) : $itr;
	return array_slice($itr, 0, $idx);
}

/**
 * Take value start from index
 * 
 * @param  iterable $itr
 * @param  int      $idx
 * @return array
 */
function takeLast(iterable $itr, int $idx): array {
	$arr = !is_array($itr) ? toArray($itr) : $itr;
	return array_slice($itr, - $idx, $idx);
}


/**
 * Make iterable into \RecursiveIteratorIterator
 * 
 * @uses   \RecursiveIteratorIterator
 * @uses   \RecursiveArrayIterator
 * @param  iterable $itr
 * @return \RecursiveIteratorIterator
 */
function toIteratorI(iterable $itr, $mode = RecursiveIteratorIterator::LEAVES_ONLY, $flags = 0) {
	return new RecursiveIteratorIterator(
		!$itr instanceof RecursiveArrayIterator
			? new RecursiveArrayIterator(toArray($itr))
			: $itr,
		$mode,
		$flags
	);
}


/**
 * Convert an iterator to an array.
 * 
 * Converts an iterator to an array. The $recursive flag, on by default,
 * hints whether or not you want to do so recursively.
 *
 * @param  iterable  $itr        The array or Traversable object to convert
 * @param  bool      $recursive  Recursively check all nested structures
 * @return array
 */
function toArray(iterable $itr, $recursive = TRUE)
{
	if (is_array($itr)) {
		return $itr;
	}

	if (!$recursive) {
		return iterator_to_array($itr);
	}

	if (method_exists($itr, 'toArray')) {
		return $itr->toArray();
	}

	$o = NULL;
	
	foreach ($itr as $k => $v) {
		if (is_scalar($v)) {
			$o[$k] = $v;
			continue;
		}

		if (is_iterable($v)) {
			$o[$k] = toArray($v, $recursive);
			continue;
		}

		$o[$k] = $v;

	}

	return $o;
}


/**
 * Get value from specific array key
 * 
 * @example
 * // in native PHP
 * (isset($data['foo']['bar']['baz'])) ? $data['foo']['bar']['baz'] : NULL;
 * 
 * // The above equals
 * \Dash\Iterables\getIn($data, ['foo', 'bar', 'baz']);
 * 
 * @param iterable    $data
 * @param iterable    $keys
 * @param mixed|NULL  $default
 */
function getIn(iterable $data, iterable $keys, $default = NULL)
{
    if (count($keys) === 1 && isset($data[$keys[0]])) {
        return $data[$keys[0]];
    }

    return ($v = hasKeys($data, $keys)) ? $v : $default;
}

/**
 * Update value from specific array key
 * 
 * @example
 * 
 * // in native PHP
 * (isset($itr['foo']['bar']['baz'])) ? $itr['foo']['bar']['baz'] = $val
 * 
 * // The above equals
 * \Dash\Iterables\updateIn($itr, ['foo', 'bar', 'baz'], $val)
 * 
 * @param iterable  $itr
 * @param iterable  $keys
 * @param mixed     $val
 */
function update(iterable $itr, iterable $keys, $val) {
    $v = &$itr;

    foreach ($keys as $k) {
        if (!is_iterable($v) || !isset($v[$k])) {
            throw new InvalidArgumentException(
            	sprintf('Did not find path %s in structure %s',
            		json_encode($k),
            		json_encode($itr)
            	)
            );
        }

        $v = &$v[$k];
    }

    $v = is_callable($val) ? $val($v, $k, $itr) : $val;

    return $itr;
}

/**
 * Assign value from specific array key
 * 
 * @example
 * // in native PHP
 * (isset($itr['foo']['bar']['baz'])) ? $itr['foo']['bar']['baz'] = $itr['foo']['bar']['baz'] = $val;
 * 
 * // The above equals
 * \Dash\Iterables\assign($itr, ['foo', 'bar', 'baz'], $val);
 * 
 * @param iterable    $itr
 * @param iterable    $keys
 * @param mixed|NULL  $itr
 */
function assign(iterable $itr, iterable $keys, $val)
{
    $v = &$itr;

    foreach ($keys as $k) {
        if (!is_iterable($v)) {
            $v = [];
        }
        $v = &$v[$k];
    }

    $v = is_callable($val) ? $val($v, $k, $itr) : $val;

    return $itr;
}


/**
 * Insert a delimiter value between each element of a collection.
 * Indices are not preserved.
 *
 * @param  iterable $itr
 * @param  mixed    $delimiter
 * @return array
 */
function intersperse(iterable $itr, $delimiter)
{
	$o = flatMap($itr, function($v) use ($delimiter) {
		return [$v, $delimiter];
	});
    
    array_pop($o);
	return $o;
}

/**
 * Return first element of the iterable
 * 
 * @param  iterable $itr
 * @return mixed
 */
function first(iterable $itr) {
	if ( is_array($itr) ) {
		reset($itr);
		return current($itr);
	}

	if ( method_exists($itr, 'current') ) {
		$itr->rewind();
		return $itr->current();
	}
}

/**
 * Return last element of the iterable
 * 
 * @param  iterable $itr
 * @return mixed
 */
function last(iterable $itr) {
	if ( is_array($itr) ) {
		return end($itr);
	}

	if ( method_exists($itr, 'current') ) {
		$itr->rewind();
		return $itr->current();
	}
}

/**
 * Returns all but the first element of the iterable
 * 
 * @param  iterable $itr
 * @return mixed
 */
function tail(iterable $itr) {
	reset($itr);
	return array_slice(is_array($itr) ? $itr : toArray($itr), 1);
}

/**
 * Get average from iterable
 *
 * @param  iterable $itr
 * @return int|float
 */
function average(iterable $itr) {
	if ( !is_array( $itr ) ) {	
		$itr = iterator_to_array($itr);
	}

	return array_sum( $itr ) / count( $itr );
}
