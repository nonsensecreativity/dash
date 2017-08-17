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

/**
 * Handling sequential method chain
 * 
 * @method array toArray(iterable $itr)
 */
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
	 * @param mixed $value
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
		$this->_queue[] = function($v) use ($key, $args) {
			return [ Dash::class, $key ]( $v, ...$args );
		};
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
				$this->_value = $queue($this->_value);
			}
		}
		return $this->_value;
	}
}
