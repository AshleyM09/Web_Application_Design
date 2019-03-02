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
      <h3>Add and Enroll a New Student</h3>
      <form action="createstudent.php" name="Select-Course" onsubmit="return checkCourseSelect()" method="post">
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
        <input type="submit" value="Generate Current Student List">
        <?php
          if(isset($_POST['LakerId'], $_POST['LastName'], $_POST['FirstName'], $_POST['email'])){
            $crseId = $_SESSION['Course_Id'];
            $lakerId = $_POST['LakerId'];
            $lastName = $_POST['LastName'];
            $firstName = $_POST['FirstName'];
            $email = $_POST['email'];
            if($crseId !="" && $lakerId !="" && $lastName!="" && $firstName!="" && $email!=""){
                $insertstmt1 = $pdo -> prepare("INSERT INTO Student (Laker_Id,Last_Name,First_Name,Email) VALUES (:lakerId, :lastName, :firstName, :email)");
                $insertstmt1 ->execute(['lakerId' => $lakerId, 'lastName' => $lastName, 'firstName' => $firstName, 'email' => $email]);

                $insertstmt2 = $pdo -> prepare("INSERT INTO Enrollement (Course_Id,Laker_Id) VALUES (:crseId2,:lakerId2)");
                $insertstmt2->execute(['crseId2' => $crseId,'lakerId2'=> $lakerId]);

                $insertstmt3 = $pdo -> prepare("INSERT into Feedback (Assignment_Id, Feedback, Course, Laker_Id) SELECT Assignment.Assignment_Id,0,:crseId3,:lakerId3 FROM Assignment WHERE Course_Id = :crseId4");
                $insertstmt3 ->execute(['crseId3'=>$crseId,'lakerId3'=>$lakerId,'crseId4'=>$crseId]);
                ?>
                <h3>
                <?php
                print("Student Sucessfully Added and Enrolled!");
            }#end inner if
          }#end If
         ?></h3>
      </form>

    </div>
      <div class="form-right">
        <h2>Students Enrolled List</h2>
        <p>
          <?php
          #pull the Assignment list when a course is selected
          if(isset($_POST['course'])){
            $crseId = $_POST['course'];
            $_SESSION["Course_Id"]= $crseId;
          ?>
          <form action="createstudent.php" onsubmit="return checkStudentRequirements()" name="Create-New-Student" method="post">
            Laker ID: <input type="text" name="LakerId" onkeypress="return isNumber(event)" maxlength="9" placeholder="ex: 900123456"><br>
            Student Last Name: <input type="text" name="LastName" placeholder="Enter Last Name"><br>
            Student First Name: <input type="text" name="FirstName" placeholder="Enter First Name"><br>
            Student email: <input type="text" name="email" placeholder="firstname.lastname@student.clayton.edu"><br>
            <input type="submit" value="Add and Enroll Student">
          </form>
          <?php
            if($crseId != ""){
              $stmt = $pdo->prepare("SELECT First_Name, Last_Name, email, Student.Laker_Id, Course_Name, Course.Course_No FROM Student JOIN Enrollement ON Student.Laker_Id = Enrollement.Laker_Id JOIN Course ON Enrollement.Course_Id = Course.Course_Id WHERE Course.Course_Id = :crseId ORDER BY Enrollement.Course_Id");
              $stmt ->execute(['crseId' => $crseId]);
              $StudentList = $stmt -> fetchAll();
          ?><br></p><h2>
          <?php
              foreach($StudentList as $singleRow){
                print("$singleRow[Course_No]"." $singleRow[Course_Name]");
                break;
              }
          ?></h2>
          <table>
            <tr>
              <th>Laker ID</th>
              <th>Student Name</th>
              <th>Student Email</th>
          <?php
              foreach($StudentList as $oneRow){
          ?>
              <tr>
                <td><?php print("$oneRow[Laker_Id]");?></td>
                <td><?php print("$oneRow[First_Name]"." $oneRow[Last_Name]");?></td>
                <td><?php print("$oneRow[email]");?></td>
              </tr>
          <?php
              }#end foreach
            }#end inner if
          }#end if
          ?></p>
      </div>
      <script>
      //Test to see if course is selected
      function checkCourseSelect(){
        var course = document.forms["Select-Course"]["course"].value;
        if(course == ""){
          alert("Please select a course.");
          return false;
        }
      }
      //Test to ensure all fields are filled out
      function checkStudentRequirements(){
        var id = document.forms["Create-New-Student"]["LakerId"].value;
        var lastName = document.forms["Create-New-Student"]["LastName"].value;
        var firstName = document.forms["Create-New-Student"]["FirstName"].value;
        var email = document.forms["Create-New-Student"]["email"].value;
        if(id == "" || lastName == "" || firstName== "" || email == ""){
          alert("Not all elements are filled out.");
          return false;
        }
      }
      //Restricts CourseId text field from entering a letter
      function isNumber(id){
        var id = (id) ? id : window.event;
        var charCode = (id.which) ? id.which : id.KeyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57)){
          return false;
        } else{
          return true;}
      }
      </script>
  </body>
</html>
