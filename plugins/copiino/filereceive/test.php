<pre>
<?php

$arrayX = array( "A", "X" );
$arrayY = array( "0012"=> "A", "0001"=>"b" );


print_r( $arrayX );

print_r( $arrayY);

echo "array X ". $arrayX[1];
echo "array Y".$arrayY["0012"]; 
echo "array Y ".$arrayY[12];       // should return "A"


$value = 12; // integer
$str = sprintf("%04d",$value);  // now $str = "0012"
echo "array Y ".$arrayY[ $str ];       // and returns "A"

?>
</pre>