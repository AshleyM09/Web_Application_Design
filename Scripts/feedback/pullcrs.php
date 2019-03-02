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
      <h3>List of Courses</h3>
      <form action="pullassgn.php" name="Pull-Assignment_Form" onsubmit="return checkCourse()" method="post">
        Select Course:<br>
<?php
# add feedconnector.php to connect to amains_feeback database
require_once 'feedconnector.php';

# pull list of courses from feedback
$crse = $pdo->query('SELECT * FROM Course ORDER BY Course_Name');
foreach ($crse as $oneRow){
?>

<input type="radio" name="course" value="<?php print ("$oneRow[Course_Id]"); ?>"><?php print ("$oneRow[Course_Name]"); ?><br>
<?php
}
#end webpage
?>
<input type="submit" value="Find Assignment">
<input type="submit" formaction="listcoursefeedback.php" formmethod="post" value="List Current Course Feedback">
      </form>
    </div>
      <div class="right">
        <p style="font-size:3vw;"></p>
      </div>
      <script>
      function checkCourse(){
        var course = document.forms["Pull-Assignment_Form"]["course"].value;
        if(course == ""){
          alert("No Course Selected.");
          return false;
        }
      }
      </script>
  </body>
</html>
