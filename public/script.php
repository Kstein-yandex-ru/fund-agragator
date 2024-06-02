<?php
if (isset($_POST['product_age']) or isset($_POST['product_weight']))
{


/* Возраст */
$_SESSION['product_age']=$_POST['product_age'];

$product_age = $_SESSION['product_age'];
	$N = count($product_age);
	$fil_01 = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil_01 = $fil_01."if (document.getElementById('01_".$product_age[$i]."')!= null)
	  document.getElementById('01_".$product_age[$i]."').setAttribute('checked', 'checked');
	  ";
    }


/* Масса продукта */
$_SESSION['product_weight']=$_POST['product_weight'];

$product_weight = $_SESSION['product_weight'];
	$N = count($product_weight);
	$fil_02 = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil_02 = $fil_02."if (document.getElementById('02_".$product_weight[$i]."')!= null)
	  document.getElementById('02_".$product_weight[$i]."').setAttribute('checked', 'checked');
	  ";
    }



$out="
<script>

".$fil_01."

".$fil_02."

</script>";


return $out;

}




if (isset($_SESSION['product_age']) && isset($_SESSION['product_weight']))
{

$product_age = $_SESSION['product_age'];
	$N = count($product_age);
	$fil_01 = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil_01 = $fil_01."if (document.getElementById('01_".$product_age[$i]."')!= null)
	  document.getElementById('01_".$product_age[$i]."').setAttribute('checked', 'checked');
	  ";
    }


$product_weight = $_SESSION['product_weight'];
	$N = count($product_weight);
	$fil_02 = "";
    for($i=0; $i < $N; $i++)
    {
	  $fil_02 = $fil_02."if (document.getElementById('02_".$product_weight[$i]."')!= null)
	  document.getElementById('02_".$product_weight[$i]."').setAttribute('checked', 'checked');
	  ";
    }


		$out="
<script>

".$fil_01."

".$fil_02."

</script>";


return $out;

}

