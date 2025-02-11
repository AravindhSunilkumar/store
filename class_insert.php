<?php
require_once "connection.php";

if(($_SERVER["REQUEST_METHOD"] == 'POST') and (isset($_POST['submit'])) ){
  $class_name = $conn->real_escape_string($_POST['class_name']);
  $to = $conn->real_escape_string($_POST['to']);
  $from = $conn->real_escape_string($_POST['from']);
  $sql = "insert into class_details (class,age_from,age_to) values ('$class_name','$from','$to')";
  $result = $conn->query($sql);
  if($result){
    echo "<script>alert('Class added successfully.');window.location.href = 'class_insert.php';</script>";
      $conn->close();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- nav -->
  <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="nav-link  btn btn-warning mt-2" aria-current="page" href="view.php">Home</a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link btn btn-warning mt-2 " href="form.php">Add Student</a>
        </li>
        
        <li class="nav-item ">
            <a class="nav-link btn btn-warning mt-2" href="class_insert.php">Add Class</a>
        </li>
    </ul>
    <!-- nav  end-->
  <div class="container col-12 col-md-4 shadow rounded mt-3">
  <form action="class_insert.php" method="post" onsubmit="return validate()">
      <div class="row ">
        <div class="col-12 text-center">
          <h2>Add Class</h2>
        </div>
        <div class="col-12 mt-2">
          <label for="class" class="form-label ">Class Name :</label>
           <input type="text" name="class_name" id="class_name" class="form-control" placeholder = "Enter class name">
           <span class="text-danger" id="class"></span>   
        </div>
        
      </div>
      <div class="col-12 mb-2">
          <label for="class" class="form-label">Age Range :</label>
           <div class="row">
            <div class="col-6">
                <input type="number" name="from" id="from" class="col-6 form-control" placeholder="From">  
                <span class="text-danger" id="fromspan"></span>   

            </div>
            <div class="col-6">
                <input type="number" name="to" id="to" class="col-6 form-control" placeholder="to"> 
                <span class="text-danger" id="tospan"></span>   

            </div>
           </div>
        </div>
        <div class="row justify-content-center col-12 mt-3">
          <input type="submit" class="col-4 btn btn-success mb-2" name="submit" value="Add Class">
        </div>
      </form>
  </div>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
 function validate(){
  var class_name = document.getElementById('class_name').value;
  var from = document.getElementById('from').value;
  var to = document.getElementById('to').value;
  if(!class_name){
    document.getElementById('class').innerHTML = "class name is required";
  }
  if(!to){
    document.getElementById('tospan').innerHTML = "field is required";
  }
  if(!from){
    document.getElementById('fromspan').innerHTML = "field is required";
  }
  if(!class_name || !to || !from){
    return false;
  }
  return true;
 }
  </script>
</body>
</html>
