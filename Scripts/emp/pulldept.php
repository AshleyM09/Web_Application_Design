<?php
# add a require once function of empconnector.php
require_once 'empconnector.php';

# create query to pull department list that orders by department name alphabetically

$depts = $pdo->query('SELECT * FROM departments ORDER BY dept_name');
echo <<<BTABLE
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="index.css">
</head>
<header>
  <h1>Department Database<h1>
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
  <table>
    <tr>
      <th>Department Name</th>
    </tr>
    <tr>
      <td>
BTABLE;

#Create a loop to generate all rows
foreach ($depts as $oneRow) {
  print ("$oneRow[dept_name]");
  echo <<<DATA
  </td>
</tr>
<tr>
  <td>
DATA;
}
echo <<<ENDTABLE
    </td>
  </tr>
</table>
</div>
<div class="right">
  <p style="font-size:3vw;"></p>
</div>
</body>
</html>
ENDTABLE;
?>
