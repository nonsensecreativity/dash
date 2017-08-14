<?php
require '../vendor/autoload.php';

use NSC\Dash\Dash;

$str = 'Lorem ipsum dolor set amet';

echo '<pre>', print_r( Dash::charCodeAt($str, 0), true ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal('1.2345') ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal('1') ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1) ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1.2345) ), '</pre>';
echo '<pre>', var_dump( Dash::isDecimal(1.2345) ), '</pre>';
echo '<pre>', var_dump( Dash::truncate('Lorem ipsum dolor set amet', 2 ) ), '</pre>';
echo '<pre>', var_dump( Dash::reverse([1,2,3,4,5]), true ), '</pre>';
