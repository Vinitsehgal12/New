 <?php
echo"<h1>PHP array count</h1>";
$php = array("the" , "php" ,"operator");
$arrlength = count($php);


for($x =0; $x < $arrlength; $x++){
echo $php[$x];
echo"<br>";
}

?>
<br>
<?php

$php = array("the" , "logical" ,"operator");
$arrlength = count($php);


for($x =0; $x < $arrlength; $x++){
echo $php[$x];
echo"<br>";
}

?>
<br>
<?php
echo"<h1>PHP Multidimensional Arrays</h1>";
$a = array(
      array ("home", 22,14),
     array("php", 15 ,19),
	 array("html", 24 ,10), 
	 array("css", 23 , 11),
	 );
            
echo $a[0][0]. " : in value: ".$a[0][1].", solid : " .$a[0][2].".<br>";
echo $a[1][0]. " : in value: ".$a[1][1].", solid : " .$a[1][2].".<br>";
echo $a[2][0]. " : in value: ".$a[2][1].", solid : " .$a[2][2].".<br>";
echo $a[3][0]. " : in value: ".$a[3][1].", solid : " .$a[3][2].".<br>";
?>
<br>
<?php 


echo"PHP में Indexed Array और Associative Array में सबसे बड़ा"."<br>";
echo"difference यही की Indexed Array में हमें सिर्फ values को insert"."<br>";
echo" करना होता है जिससे value indexed number के साथ associate (bind) होती थी"."<br>"; 
echo" जबकि Associative Array में हम key के साथ value insert करते हैं जिससे value"."<br>";
echo"ी गयी key के साथ ही associate होती है";
