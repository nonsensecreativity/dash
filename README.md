# DASH
### Like LoDash but smaller

## Usage

**Import and call the function**

```php
use function NSC\Dash\Callables\Iterables\{flatMap};

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
$flatMap = flatMap([1, 2, 3, 4], function(int $v, int $k, iterable $itr) {
	return [$v + 1, $v];
});
```

**or use via static method**

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


- [ ] Add Test Case
- [ ] More Functionality
