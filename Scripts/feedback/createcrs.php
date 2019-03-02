<?php
session_start();
require_once 'feedconnector.php';
if(isset($_POST['courseId'], $_POST['courseName'], $_POST['courseNo'])){
  $crseId = $_POST['courseId'];
  $_SESSION["Course_Id"]= $crseId;
  $crseName = $_POST['courseName'];
  $_SESSION["Course_Name"]= $crseName;
  $crseNo = $_POST['courseNo'];
  $_SESSION["Course_No"]= $crseNo;
  if($crseId !="" && $crseName!="" && $crseNo!=""){
    $insertstmt = $pdo -> prepare("INSERT INTO Course VALUES (:crseId, :crseName, :crseNo)");
    $insertstmt ->execute(['crseId' => $crseId, 'crseName' => $crseName, 'crseNo' => $crseNo]);
  }
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
    <div class="form-main">
      <h3>Create a Course</h3>
      <form action="createcrs.php" name="Create-New-Course" onsubmit="return checkCourseRequirements()" method="post">
        Course ID: <input type="text" name="courseId" onkeypress="return isNumber(event)" placeholder="ex: 5151"><br>
        Course Name: <input type="text" name="courseName" placeholder="Enter Name of Course"><br>
        Course Number: <input type="text" name="courseNo" onkeypress="return noSpace(event)" placeholder="ex: ITFN1101"><br>
        <input type="submit" value="Add Course">
      </form>
      <form action="" name="Submit-For-Create-Assignment" method="post">
        <input type="submit" formaction="createassgn.php" value="Create an Assignment">
      </form>
      <script>
      //Test to ensure all fields are filled out
      function checkCourseRequirements(){
        var id = document.forms["Create-New-Course"]["courseId"].value;
        var name = document.forms["Create-New-Course"]["courseName"].value;
        var  no = document.forms["Create-New-Course"]["courseNo"].value;
        if(id == "" || name == "" || no == ""){
          alert("Not all elements are filled out.");
          return false;
        }
      }
      //Restricts CourseId text field from entering a letter
      function isNumber(id){
        id = (id) ? id : window.event;
        var charCode = (id.which) ? id.which : id.KeyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57)){
          return false;
        } else{
          return true;}
      }
      //Restricts spaces from being intered in Course Number
      function noSpace(courseNo){
        courseNo = (courseNo) ? courseNo : window.event;
        var spaceCode = (courseNo.which) ? courseNo.which : courseNo.KeyCode;
        if(spaceCode == 32){
          return false;
        } else{
          return true;}
      }
      </script>
    </div>
    <div class="form-right">
      <h2>Current Course List</h2>
      <p>
        <?php
        #pull the course list in order to see the changes to the course table
        $stmt = $pdo->query("SELECT * From Course ORDER BY Course_No");
        foreach($stmt as $oneRow){
          print("$oneRow[Course_No]". " $oneRow[Course_Name]");
        ?><br>
        <?php
      }#end foreach
        ?></p>
    </div>
    </body>
    </html>
