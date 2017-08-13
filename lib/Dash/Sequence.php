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

use NSC\Dash\Dash;
use BadMethodCallException;

class Sequence {

	/**
	 * The active data value
	 * 
	 * @var mixed $_value
	 */
	protected $_value;

	/**
	 * List of queues
	 * 
	 * @var array $_queue
	 */
	protected $_queue;

	/**
	 * Class main constructor
	 * 
	 * @param array $value
	 */
	public function __construct( $value ) {
		$this->_value = $value;
	}

	/**
	 * Method overloading
	 * 
	 * Every call from class instance will be 
	 * appended to the queue
	 * 
	 * @param string $key
	 * @param array  $args
	 * @return $this
	 */
	public function __call(string $key, array $args) {
		if ( stripos( $key, 'sort' ) !== FALSE ) {
			return $this;
		}
		
		$this->_queue[] = [ $key, $args ];
		return $this;
	}

	/**
	 * Run\Execute queue
	 * 
	 * @return mixed
	 */
	public function result() {
		if ( !empty($this->_queue) ) {
			foreach ( $this->_queue as $queue ) {
				$this->_value = [
					Dash::class,
					$queue[0]
				]( $this->_value, ...$queue[1] );
			}
		}

		return $this->_value;
	}

	/**
	 * Create a new sequence instance for immediate chaining
	 * because calling new Sequence($v) aren't chainable
	 *
	 * @param  mixed $value
	 * @return \Dash\Sequence
	 */
	public static function from($value) {
		return new static($value);
	}

}
