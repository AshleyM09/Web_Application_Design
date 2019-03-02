<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="index.css">
</head>
<header>
  <h1>Calculator<h1>
</header>
<body>
  <div style="overflow:auto">
    <div class="menu">
  <ul>
    <li class="PortMenu">Portfolio Menu:</li>
    <li><a background-color="#111" color="#e5e5e5" href="http://kahuna.clayton.edu/itfn2214-class/amains/ERD/">ER Diagram</a></li>
    <li><a background-color="#111" color="#e5e5e5" href="http://kahuna.clayton.edu/itfn2214-class/amains/feedback/index.html">Feedback Web App</a></li>
    <li><a background-color="#111" color="#e5e5e5" href="http://kahuna.clayton.edu/itfn2214-class/amains/calc/">Calculator Web App</a></li>
    <li><a background-color="#111" color="#e5e5e5" href="http://kahuna.clayton.edu/itfn2214-class/amains/office/">Office Supplies ER Diagram</a></li>
    <li><a background-color="#111" color="#e5e5e5" href="http://kahuna.clayton.edu/itfn2214-class/amains/emp/">Employee Database</a></li>
  </ul>
    </div>
<div class="main">
<?php
  $a = $_POST["aname"];
  $op = $_POST["operand"];
  $b = $_POST["bname"];
  $ans = 0.00;

  if($op == '+'){
    $ans = $a + $b;
  }elseif($op =='-'){
    $ans = $a - $b;
  }elseif($op =='*'){
    $ans = $a * $b;
  }elseif($op =='/'){
    $ans= $a / $b;
  }elseif($op=='%'){
    $ans= $a % $b;
  }else{
    echo "No operand selected.";
  }

  echo "The answer is $ans."
?>
</div>
<div class="right">
  <p ></p>
</div>
</body>
</html>
