<?php
session_start();
require_once 'feedconnector.php';
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
      <h3>Create an Assignment</h3>
      <form action="createassgn.php" name="Select-Course" onsubmit="return checkCourseSelect()" method="post">
          Select Course:<br>
        <?php
        # pull list of courses to select which course to add to
        $crse = $pdo->query('SELECT * FROM Course ORDER BY Course_Name');
        foreach ($crse as $oneRow){
        ?>
        <input type="radio" name="course" value="<?php print ("$oneRow[Course_Id]"); ?>"><?php print ("$oneRow[Course_Name]"); ?><br>
        <?php
        }
        ?>
        <input type="submit" value="Generate Current Assignment List">
        <?php
          if(isset($_POST['AssignName'], $_POST['AssignId'])){
            $crseId = $_SESSION['Course_Id'];
            $assgnId = $_POST['AssignId'];
            $assgnName = $_POST['AssignName'];
              if($crseId !="" && $assgnName!="" && $assgnId!=""){
                $insertstmt = $pdo -> prepare("INSERT INTO Assignment (Assignment_Id, Assignment_Name, Course_Id) VALUES (:assgnId, :assgnName, :crseId)");
                $insertstmt ->execute(['assgnId' => $assgnId, 'assgnName' => $assgnName, 'crseId' => $crseId]);

                #create feedback for each student and set them to Netural
                $feedbackstmt = $pdo -> prepare("INSERT INTO Feedback (Assignment_Id, Feedback, Course, Laker_Id) SELECT :assignId2,0,:crseId2,Enrollement.Laker_Id FROM Enrollement WHERE Course_Id = :crseId3");
                $feedbackstmt ->execute(['assignId2' => $assgnId,'crseId2'=> $crseId, 'crseId3'=>$crseId]);
                print("Assignment Sucessfully Added!");
              }
          }#end If
         ?>
      </form>

    </div>
      <div class="form-right">
        <h2>Assignment List</h2>
        <p>
          <?php
          #pull the Assignment list when a course is selected
          if(isset($_POST['course'])){
            $crseId = $_POST['course'];
            $_SESSION["Course_Id"]= $crseId;
          ?>
          <form action="createassgn.php" onsubmit="return checkAssignmentRequirements()" name="Create-New-Assignment" method="post">
            Assignment ID: <input type="text" name="AssignId" onkeypress="return isNumber(event)" placeholder="ex: 1"><br>
            Assignment Name: <input type="text" name="AssignName" placeholder="Ex: Database Assignment 1"><br>
            <input type="submit" value="Add Assignment">
          </form>
          <?php
          #Provide current list for assignments to see what has already been added
            if($crseId != ""){
              $stmt = $pdo->prepare("SELECT Assignment.Course_Id, Assignment_Id, Assignment_Name, Course.Course_Name FROM Assignment JOIN Course ON Assignment.Course_Id = Course.Course_Id WHERE Assignment.Course_Id = :crseId ORDER BY Assignment.Course_Id");
              $stmt ->execute(['crseId' => $crseId]);
              $assignmentList = $stmt -> fetchAll();
          ?>
          <h4>Current Assignments for <?php foreach($assignmentList as $singleRow){
            print("$singleRow[Course_Name]");
            break;
          } ?></h4>
          <table>
            <tr>
              <th>Assignment ID</th>
              <th>Assignment Name</th>
            </tr>
          <?php
              foreach($assignmentList as $oneRow){
          ?>
          <tr>
            <td>
          <?php
                print("$oneRow[Assignment_Id]");
          ?></td>
          <td>
            <?php
              print("$oneRow[Assignment_Name]");
            ?></td>
          <?php
              }#end foreach
            }#end inner if
          }#end if
          ?></table></p>
      </div>
      <script>
      //Test course is properly selected
      function checkCourseSelect(){
        var course = document.forms["Select-Course"]["course"].value;
        if(course == ""){
          alert("Please select a course.");
          return false;
        }
      }
      //Test to ensure all fields are filled out
      function checkAssignmentRequirements(){
        var id = document.forms["Create-New-Assignment"]["AssignId"].value;
        var name = document.forms["Create-New-Assignment"]["AssignName"].value;
        if(id == "" || name == ""){
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
      </script>
  </body>
</html>
