<?php
$out = '&tvFilters=`';

/* Возраст */
if(empty($_POST['product_age']) && empty($_SESSION['product_age']) )
{

$out .= "";

}
elseif($_POST['product_age'])
{

	$product_age = $_POST['product_age'];
	$N = count($product_age);
	$fil = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil= $fil.$product_age[$i] . "||product_age==" ;
    }

	$fil = mb_substr($fil, 0, -15);
	$out .= "product_age==".$fil.",";


}

elseif(empty($_POST['product_age']) && $_SESSION['product_age'])
{

	$product_age = $_SESSION['product_age'];
	$N = count($product_age);
	$fil = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil= $fil.$product_age[$i] . "||product_age==" ;
    }

	$fil = mb_substr($fil, 0, -15);
	$out .= "product_age==".$fil.",";
}



/* Масса продукта */
if(empty($_POST['product_weight']) && empty ($_SESSION['product_weight']) )
{
$out .= "";
}
elseif($_POST['product_weight'])
{

	$product_weight = $_POST['product_weight'];
	$N = count($product_weight);
	$fil = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil= $fil.$product_weight[$i] . "||product_weight==" ;
    }

	$fil = mb_substr($fil, 0, -18);
	$out .= "product_weight==".$fil.",";


}

elseif(empty($_POST['product_weight']) && $_SESSION['product_weight'])
{

	$product_weight = $_SESSION['product_weight'];
	$N = count($product_weight);
	$fil = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil= $fil.$product_weight[$i] . "||product_weight==" ;
    }

	$fil = mb_substr($fil, 0, -18);
	$out .= "product_weight==".$fil.",";
}




$cntChars=strlen($out);
if($out[$cntChars-1]==",")
$exp=substr($out,0,$cntChars-1);
else $exp=$out;
$exp .="`";
return $exp;