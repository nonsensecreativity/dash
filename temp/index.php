<?php
/*
function requireFiles(RecursiveDirectoryIterator $file) {
	$file->rewind();
	$required = [];
	while( $file->valid() ) {
		if ( $file->isDir() && $file->hasChildren() ) {
			requireFiles($file->getChildren());
		} else {
			if ( stripos( $file->getExtension(), 'php') !== FALSE ) {
				$required[$file->getBasename()] = $file->getBasename();
				require $file->getRealPath();
			}
		}
		$file->next();
	}
}
requireFiles(new RecursiveDirectoryIterator(realpath( __DIR__ . '/../src/Dash/'),
	RecursiveDirectoryIterator::FOLLOW_SYMLINKS | RecursiveDirectoryIterator::SKIP_DOTS
));*/

require 'Bench/Mark.php';
require '../vendor/autoload.php';

use Bench\Mark;
use NSC\Dash\Dash;
/*
$str = 'Lorem ipsum dolor set amet';

echo '<pre>', var_dump( Dash::charCodeAt($str, 0) ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal('1.2345') ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal('1') ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1) ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1.2345) ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1.2345) ), '</pre>';
echo '<pre>', var_dump( Dash::truncate('Lorem ipsum dolor set amet', 2 ) ), '</pre>';
echo '<pre>', var_dump( Dash::reverse([1,2,3,4,5]) ), '</pre>';
*/
$sample = [
	1, 2, 3, 4, 5,
	[
		6, 7, 8, 9, 10,
		[
			11, 12, 13, 14, 15
		],
		[
			16, 17, 18, 19, 20,
			[
				21, 22, 23, 24, 25,
				[
					[
						26, 27, 28, 29, 30
					],
					[
						31, 32, 33, 34, 35,
						[
							36, 37, 38, 39, 40
						],
						[
							41, 42, 43, 44, 45,
							[
								46, 47, 48, 49, 50
							],
							[
								51, 52, 53, 54, 55,
								[
									56, 57, 58, 59, 60,
									[
										[
											61, 62, 63, 64, 65
										],
										[
											66, 67, 68, 69, 70,
											[
												71, 72, 73, 74, 75,
												[
													76, 77, 78, 79, 80
												],
												[
													81, 82, 83, 84, 85,
													[
														86, 87, 88, 89, 90
													]
												]
											]
										]
									]
								]
							]
						]
					]
				]
			]
		]
	],
	
];

$sample2 = [
	'a' => 1,
	'b' => 2,
	'c' => 3,
	'd' => 4,
	'e' => [
		'f' => 5,
		'g' => 6,
		'h' => 7,
		'i' => 8,
		'j' => 9,
		'k' => [
			'l' => 10,
			'm' => 11,
			'n' => 12,
			'o' => 13,
			'p' => 14,
			'q' => [
				'r' => 15,
				's' => 16,
				't' => 17,
				'u' => 18,
				'v' => 19,
				'w' => [
					'x' => 20,
					'y' => 21,
					'z' => 22,
					'A' => 23,
					'B' => 24,
					'C' => [
						'D' => 15,
						'E' => 16,
						'F' => 17,
						'G' => 18,
						'H' => 19,
					]
				]
			]
		]
	]
	
];


$range = range(0, 100000);
$bench = new Mark();
$bench->iterations = 1;

$bench->add(function() use($sample, $sample2, $range) {
	return array_values(Dash::filter(Dash::map(Dash::map($range, function($v){
		return $v + 10;
	}), function($v){
		return $v * 100;
	}), function($v){
		return $v % 3 !== 0;
	}));
}, 'Dash Static');

$bench->add(function() use($sample, $sample2, $range) {
	return array_values(
		array_filter(array_map(function($v) {
			return $v * 100;
		}, array_map(function($v) {
			return $v + 10;
		}, $range)), function($v) {
			return $v % 3 !== 0;
		})
	);
}, 'Native');

$bench->add(function() use($sample, $sample2, $range) {
	return Dash::seq($range)
	->map(function($v){
		return $v + 10;
	})
	->map(function($v){
		return $v * 100;
	})
	->filter(function($v){
		return $v % 3 !== 0;
	})
	->values()
	->result();
}, 'Sequence');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Benchmark</title>
	<?php $bench->style(); ?>
</head>
<body>
	<div class="container">
		<?php echo $bench->results(); ?>
		<br /><br />
		<?php echo $bench->output(); ?>
	</div>
</body>
</html>
