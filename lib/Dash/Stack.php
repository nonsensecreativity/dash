<?php
/*
 * This file is part of the Dash package.
 *
 * (c) Hermanto Lim <nonsensecreativity@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NSC\Dash;

use ArrayIterator;
use ArrayObject;

/**
 * The Stack Class
 */
class Stack extends ArrayObject
{
    /**
     * Retrieve iterator
     *
     * Retrieve an array copy of the object, reverse its order, and return an
     * ArrayIterator with that reversed array.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(
			array_reverse($array)
		);
    }
}
