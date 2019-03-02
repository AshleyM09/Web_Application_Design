<?php
session_start();
if(isset($_POST['course'])){
  $crseId = $_POST['course'];
  $_SESSION["Course_Id"]= $crseId;
}else{
  $crseId = $_SESSION["Course_Id"];
}
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="index.css">
</head>
<header>
  <h1>Assignments in Course<h1>
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
      <form action="updatestudentfeedback.php" name="pullAssgnForm" method="post">
        <h3>Select Assignment for Course <?php print("$crseId"); ?>:</h3>
<?php
# add feedconnector.php to connect to amains_feeback database
require_once 'feedconnector.php';
# pull assignments from Assignment table to generate a list to select from
$crsename = $pdo ->prepare("SELECT Assignment_Id, Assignment_Name FROM Assignment WHERE Course_Id = :crseId GROUP BY Assignment_Id, Assignment_Name");
$crsename ->execute(['crseId' => $crseId]);
$assignments =$crsename->fetchAll();

#Creates Assignment options
foreach($assignments as $oneRow){
?>
<input type="radio" name="assignment" value="<?php print ("$oneRow[Assignment_Id]"); ?>"><?php print ("$oneRow[Assignment_Name]"); ?><br>
<?php
}
#end webpage
?>
<input type="submit" value="Generate List of Students">
      </form>
    </div>
      <div class="form-right">
        <form action="pullcrs.php" name="Back-To-Courses" method="post">
          <input type="submit" value="Back To Courses">
        </form>
      </div>
  </body>
</html>
