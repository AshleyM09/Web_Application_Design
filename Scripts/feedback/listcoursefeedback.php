<?php
# add feedconnector.php to connect to amains_feeback database
require_once 'feedconnector.php';
#Need to pull course value to display full feedback status
session_start();
if(isset($_POST['course'])){
  $crseId = $_POST['course'];
  $_SESSION["Course_Id"] = $crseId;
}else{
  $crseId = $_SESSION["Course_Id"];
}


#List students current feedback and Create a table for this list
$stmt = $pdo ->prepare("SELECT Last_Name, First_Name, SUM(feedback) AS Total_Feedback, Course_Name FROM Feedback JOIN Student ON Feedback.Laker_Id = Student.Laker_Id JOIN Course ON Feedback.Course = Course.Course_Id WHERE Course = :crseId GROUP BY Last_Name, First_Name, Course_Name");
$stmt ->execute(['crseId'=> $crseId]);
$students = $stmt ->fetchAll();
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="index.css">
</head>
<header>
  <h1>Updated Student Feedback for Course<h1>
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
      <h2>Course Selected: <?php foreach($students as $singleRow){ print("$singleRow[Course_Name]"); break;}?></h2><br>
      <table>
        <tr>
          <th style="font-size:2">Student Name</th>
          <th style="font-size:2">Feedback Status</th>
          <th style="font-size:2">Feedback Count</th>
        </tr>
  <?php
  #Convert feedback status to text for easy readability
  foreach($students as $oneRow){
    if ("$oneRow[Total_Feedback]"> 0){
      $feedback ="Positive";
    }elseif("$oneRow[Total_Feedback]" < 0){
      $feedback = "Negative";
    }else{
      $feedback = "Netural";
    }
  ?>
      <tr>
        <td><?php print("$oneRow[First_Name]"." $oneRow[Last_Name]"); ?></td>
        <td><?php echo $feedback; ?></td>
        <td><?php echo "$oneRow[Total_Feedback]";?> </td>
      </tr>
  <?php
  }
  ?>
      </table>
    </div>
    <div class="form-right">
      <form action="pullcrs.php" name="Back-To-Courses" method="post">
        <input type="submit" value="Back To Courses">
      </form>
    </div>
</body>
</html>
