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

class Fluent implements \Countable, \Iterator {

	/**
	 * Generator
	 *
	 * @var \Generator $_items
	 */
	private $_items;

	public static function fromArray(array $array) {
		return new static((function ($arr) {
			foreach ( $arr as $k => $v ) {
				yield $k => $v;
			}
		})($array));
	}

	public function __construct(\Generator $items) {
		$this->_items = $items;
	}

	public function map(callable $fn) {
		return new static((function($fn, $items) {
			foreach ( $items as $k => $v ) {
				yield $k => $fn($v, $k);
			}
		})($fn, $this->_items));
	}

	public function filter(callable $fn) {
		return new static((function($fn, $items) {
			foreach ( $items as $k => $v ) {
				if ( $fn( $v, $k ) ) {
					yield $k => $v;
				}
			}
		})($fn, $this->_items));
	}

	public function append($item) {
		$this->_items->send($item);
		$this->_size++;
	}

	public function valid() {
		return $this->_items->valid();
	}

	public function results() {
		return iterator_to_array( $this->_items );
	}

	public function count() {
		return $this->_size;
	}

	public function next() {
		return $this->_items->next();
	}

	public function key() {
		return $this->_items->key();
	}

	public function reset() {
		$this->_items->rewind();
		return new static($this->_items);
	}

	public function rewind() {
		$this->_items->rewind();
	}

	public function current() {
		return $this->_items->current();
	}

	public function toArray() {
		return iterator_to_array($this->_items);
	}
}
