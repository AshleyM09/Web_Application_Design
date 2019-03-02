<?php
#Pull variable from last page to use in query
session_start();
$crseId = $_SESSION["Course_Id"];

#need to check to see if pulling assignment from a form or from an updated student row
if(isset($_POST['assignment'])){
$assgnId = $_POST['assignment'];
$_SESSION["Assignment_Id"]= $assgnId;
}else{
$assgnId = $_SESSION["Assignment_Id"];
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
  <h1>Feedback Web App<h1>
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
      <h3>List of Students with Current Feedback Status on Assignment</h3>
      <table>
        <tr>
          <th>Laker ID</th>
          <th>Student Name</th>
          <th>Current Status</th>
          <th>Update</th>
          <th>Click Each to Update</th>
        </tr>
<?php
# add feedconnector.php to connect to amains_feeback database
require_once 'feedconnector.php';
#Update any possible feedback information. This is done in order to update any submitted feedback for
#individual students
if(isset($_POST['feedbackValue'])){
  $lakerId = $_POST['Laker_Id'];
  $feedback = $_POST['feedbackValue'];
  $updatestmt = $pdo -> prepare("UPDATE Feedback SET Feedback = :feedback WHERE Laker_Id = :lakerId AND Assignment_Id = :assgnId AND Course=:crseId");
  $updatestmt ->execute(['feedback' => $feedback, 'lakerId' => $lakerId, 'assgnId' => $assgnId, 'crseId' => $crseId]);
}

#Pull list of students from the selected course and the selected assignment.
$stmt = $pdo ->prepare("SELECT First_Name, Last_Name, Feedback, Student.Laker_Id FROM Student JOIN Feedback ON Student.Laker_Id = Feedback.Laker_Id WHERE Course=:crseId AND Assignment_Id=:assgnId");
$stmt ->execute(['crseId' => $crseId, 'assgnId' => $assgnId]);
$students = $stmt->fetchAll();

foreach($students as $oneRow){

  $feedbackStatus= "$oneRow[Feedback]";
  if ($feedbackStatus > 0){
    $feedbackStatus = "Positive";
  }elseif($feedbackStatus < 0){
    $feedbackStatus = "Negative";
  }else{
    $feedbackStatus= "Netural";
  }
?>
  <tr>
    <td>
      <form action="updatestudentfeedback.php" name="push-assignment-update" method="post">
        <input type="text" name="Laker_Id" value="<?php print ("$oneRow[Laker_Id]"); ?>" readonly="readonly" />
    <td><?php print("$oneRow[First_Name]"." $oneRow[Last_Name]");?></td>
    <td><?php print("$feedbackStatus");?></td>
    <td>
        <select name="feedbackValue">
          <option value="0">Netural</option>
          <option value="1">Positive</option>
          <option value="-1">Negative</option>
        </select>
    </td>
    <td>
      <input type="submit" value="Update Student Status">
          </form>
    </td>
  </tr>
  <tr>
    <td>
<?php
}
?>
    </td>
  </tr>
</table>
</div>
  <div class="form-right">
    <form action="" name="List-Course-Feedback" method="post">
      <input type="submit" formaction="listcoursefeedback.php" value="List Course feedback">
      <input type="submit" formaction="pullassgn.php" value="Back to Assignments">
    </form>
  </div>
</body>
</html>
