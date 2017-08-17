# DASH
**Kind of Like LoDash but smaller**

**NOTE: Require at least PHP VERSION 7.1**

## Usage

`composer require nonsensecreativity\dash`


**Import and call the lib static method**

```php
use function NSC\Dash\Iterables;

/**
 * All iterable hook always provide
 * - The current value
 * - The current key
 * - The iterable set
 *
 * @param  int $v
 * @param  int $k
 * @param  iterable $itr
 * @return iterable
 */
$flatMap = Iterables::flatMap([1, 2, 3, 4], function(int $v, int $k, iterable $itr) {
	return [$v + 1, $v];
});
```

**or use via dash static method which will automatically redirecting the called method to proper class**

```php
use NSC\Dash\Dash;

Dash::flatMapDeep(
	['a', [ 'b', [ 'c', [ 'd' ] ] ] ],
	function(string $v, int $k, iterable $itr) {
		return [ $v, $v . ' FLATMAPDEEP' ];
	}
);
```

**or use sequence**

```php
$seq = Sequence::from([1, [2, [3, [4, 5]]]]);
$seq->flatten()
	->reverse()
	->flatMap(function($v, $k, $iterable) {
		return [$v, $v];
	})
	->result();
```

**TODO**


- [ ] Add Test Case (some sample already added)
- [ ] More Functionality
