<?php
require_once 'connection.php';



if(isset($_GET['image_id']) ) {
  $image_id = (int)$_GET['image_id'];
  
  $sql = "DELETE FROM student_gallery WHERE gallery_id = '$image_id'";
  if ($conn->query($sql) === TRUE) {
    echo "<script>
      alert('Record deleted successfully');
      window.location.href = 'view.php';
    </script>";
    } else {
    echo "Error deleting record: " . $conn->error;
    }
    
    
}

if (isset($_FILES['files']) && isset($_POST['id'])) {
  $files = $_FILES['files'];
  $id = (int)$_POST['id'];
  $uploadDirectory = 'students_images/';
  for ($i = 0; $i < count($files['name']); $i++) {
    $random_number = rand(1000, 9999);
    $fileName = $random_number . '_' . basename($files['name'][$i]);
    $targetFilePath = $uploadDirectory . $fileName;
    if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
      $sql = "INSERT INTO student_gallery (student_id, gallery_photo) VALUES (?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("is", $id, $targetFilePath);
      $stmt->execute();
      echo "File " . $targetFilePath . " uploaded successfully.<br>";
      $stmt->close();
    } else {
      echo "Error uploading file <script>console.log( " . $targetFilePath . ");</script>.<br>";
    }
  }
}


$name = $age = $class = $gender ='';
if(($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['supdate']))){
 

  $name = $age = $class = $gender = $id ='';
  $name = $_POST['name'];
  $age = $_POST['age'];
  $class = $_POST['class_id'];
  $gender = $_POST['gender'];
  $date = $_POST['date'];
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
  }
  
  



        //file uploading 
        if(!empty($_FILES["file"]["name"])){
              $target_dir = "students_images/";
              $random_number = rand(1000, 9999);
              $target_file = $target_dir . $random_number . '_' . basename($_FILES["file"]["name"]);
              
              $uploadOk = 1;
              $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

              // Check if image file is a actual image or fake image
              if (isset($_POST["submit"])) {
                  $check = getimagesize($_FILES["file"]["tmp_name"]);
                  if ($check !== false) {
                  // echo "File is an image - " . $check["mime"] . "";

                  $uploadOk = 1;
                  } else {
                  echo "<script>alert('File is not an image.');</script>";
                  $uploadOk = 0;
                  }
              }

              // Check if file already exists
              if (file_exists($target_file)) {
                  echo "<script>alert('Sorry, file already exists.');</script>";
                  $uploadOk = 0;
              }

              // Check file size
              if ($_FILES["file"]["size"] > 100000000) { // 100000000 bytes is approximately 100 MB
                  echo "<script>alert('Sorry, your file is too large.');</script>";
                  $uploadOk = 0;
              }

              // Allow certain file formats
              if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                  && $imageFileType != "gif") {
                  echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                  $uploadOk = 0;
              }

              // Check if $uploadOk is set to 0 by an error
              if ($uploadOk == 0) {
                  echo "<script>alert('Sorry, your file was not uploaded.');</script>";
              // if everything is ok, try to upload file
              } else {
                  if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                  // echo "<script>alert('The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.');</script>";
                  } else {
                  echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                  }
              }
        }

        




        if(!empty($_FILES["file"]["name"])){
            $sql = "UPDATE student_details SET name='$name',age='$age',class_id='$class',gender = '$gender',photo = '$target_file',dob='$date' WHERE id = '$id'";
        }else{
          $sql = "UPDATE student_details SET name='$name',age='$age',class_id='$class',gender = '$gender',dob = '$date' WHERE id = '$id'";

        }
  if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Record updated successfully');
            window.location.href = 'view.php';
          </script>";
          $name = $age = $class = $gender ='';
    mysqli_free_result($result);
    } else {
    echo "Error updating record: " . $conn->error;
    }

    $name = $age = $class = $gender ='';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display</title>
  <style>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  h2 {
    color: #333;
  }

  input[type="number"] {
    padding: 5px;
    margin-right: 10px;
  }
  input[type="submit"] {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  input[type="submit"]:hover {
    background-color: #45a049;
  }
  .result {
    margin-top: 20px;
  }
  .result p {
    margin: 5px 0;
  }

body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Full-width input fields */
/* input[type=text], input[type=password] {
width: 100%;
padding: 15px;
margin: 5px 0 22px 0;
display: inline-block;
border: none;
background: #f1f1f1;
} */

/* Add a background color when the inputs get focus */
/* input[type=text]:focus, input[type=password]:focus {
background-color: #ddd;
outline: none;
} */

/* Set a style for all buttons */
button {
background-color: #04AA6D;
color: white;
padding: 14px 20px;
margin: 8px 0;
border: none;
cursor: pointer;
width: 100%;
opacity: 0.9;
}

button:hover {
opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
padding: 14px 20px;
background-color: #f44336;
}

/* Float cancel and signup buttons and add an equal width */
.cancelbtn, .signupbtn {
float: left;
width: 50%;
}

/* Add padding to container elements */
.container {
padding: 16px;
}

/* The Modal (background) */
.modal {
display: none; /* Hidden by default */
position: fixed; /* Stay in place */
z-index: 1; /* Sit on top */
width: 50%;
left: 25%;
top: 0;
width: 100%; /* Full width */
height: 100%; /* Full height */
overflow: auto; /* Enable scroll if needed */
background-color:rgb(255, 255, 255);
padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
background-color: #fefefe;
margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
border: 1px solid #888;
width: 80%; /* Could be more or less, depending on screen size */
}

/* Style the horizontal ruler */
hr {
border: 1px solid #f1f1f1;
margin-bottom: 25px;
}

/* The Close Button (x) */
.close {
position: absolute;
right: 35px;
top: 15px;
font-size: 40px;
font-weight: bold;
color: #f1f1f1;
}

.close:hover,
.close:focus {
color: #f44336;
cursor: pointer;
}

/* Clear floats */
.clearfix::after {
content: "";
clear: both;
display: table;
}

/* Change styles for cancel button and signup button on extra small screens */
@media screen and (max-width: 300px) {
.cancelbtn, .signupbtn {
   width: 100%;
}
}

#myModal{
  display: none;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
 
  /* z-index: 99; */
}


.input-switch{
	display: none;
}

.label-switch{
	display: inline-block;
	position: relative;
}

.label-switch::before, .label-switch::after{
	content: "";
	display: inline-block;
	cursor: pointer;
	transition: all 0.5s;
}

.label-switch::before {
    width: 3em;
    height: 1em;
    border: 1px solid #757575;
    border-radius: 4em;
    background: #888888;
}

.label-switch::after {
    position: absolute;
    left: 0;
    top: -20%;
    width: 1.5em;
    height: 1.5em;
    border: 1px solid #757575;
    border-radius: 4em;
    background: #ffffff;
}

.input-switch:checked ~ .label-switch::before {
    background: #00a900;
    border-color: #008e00;
}

.input-switch:checked ~ .label-switch::after {
    left: unset;
    right: 0;
    background: #00ce00;
    border-color: #009a00;
}

.info-text {
	display: block;
}

.info-text::before{
	content: "Inactive";
}

.input-switch:checked ~ .info-text::before{
	content: "Active";
}
    </style>
</style>
  <link href="css/modal.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <!-- nav -->
  <ul class="nav justify-content-center">
    <li class="nav-item mt-2">
        <a class="nav-link active btn btn-warning" aria-current="page"  href="view.php">Main Page</a>
    </li>
    
    <li class="nav-item ml-2 mt-2">
        <a class="nav-link btn btn-warning ml-2" href="form.php">Add Student</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " aria-disabled="true"></a>
    </li>
    </ul>
    <!-- nav  end-->

  <div class="container col-12  justify-content-center mt-4 ">
  <div class="col-12 d-flex justify-content-center">
    
    <!-- <div class="col-6">
      <a href="form.php" class="btn btn-primary">Add Student</a>
    </div> -->
  
  </div>

  <!-- loader -->
  <div id="loader" style="display:none" class="spinner-grow text-info" role="status">
      <span class="visually-hidden">Loading...</span>
  </div>
  <!-- loader end -->

<!-- Modal -->
<div id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 88%;">
      <div class="modal-header" style="background-color:rgb(255, 255, 255);">
        <a href="#" class="close" data-dismiss="modal" style="position: absolute; right: 24px;">&times;</a>
        <h4 class="modal-title">Photo</h4>
      </div>
      <div class="modal-body" id="modalBody" style="background-color: #ffffff;">
      </div>
      
    </div>
  </div>
</div>


  <!--table-->
  <div class="container">
      
            <?php
                

                if(isset($_GET['action']) && $_GET['action'] == 'delete'){
                  $id = (int)$_GET['id'];
                  $sql = "DELETE FROM student_details WHERE id = '$id'";
                  if ($conn->query($sql) === TRUE) {
                    echo "<script>
                      alert('Record deleted successfully');
                      window.location.href = 'view.php';
                    </script>";
                    } else {
                    echo "Error deleting record: " . $conn->error;
                    }
                    
                    
                }
                if(isset($_GET['action']) && $_GET['action'] == 'update'){
                  $id = $_GET['id'];
                  $sql = "SELECT student_details.id,student_details.name,student_details.age,student_details.gender,class_details.class,student_details.photo,student_details.dob,class_details.id  as c_id FROM student_details INNER JOIN class_details ON student_details.class_id = class_details.id WHERE student_details.id = '$id'";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                     
                      $name = $row['name'];
                      $age = $row['age'];
                      $class = $row['class'];
                      $gender = $row['gender'];
                      $photo = $row['photo'];
                      $date = $row['dob'];
                  
                    
          ?>

          <!-- The Modal -->
             <div class="container col-8 border shadow p-3 mb-5 bg-body rounded">
             <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" onsubmit = "return validate()">
            <div class="container">
              <h2 class="text-center">Edit Student</h2>
              <div class="form-group row mt-2">
              <label for="name" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>Name:</label>
              <div class="col-sm-4">
                <input type="hidden" class="form-control mt-2" id="id" name="id" value="<?php echo $id; ?>" required>
                
                <input type="text" class="form-control mt-2" id="name" name="name" value="<?php echo $name; ?>" required>
              </div>
              <label for="class" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>Class:</label>
              <div class="col-sm-4">
                <select class="form-select mt-2" id="class_id" name="class_id" aria-label="Default select example">
                <?php
                $sql1 = "SELECT * FROM class_details WHERE status = 'active'";
                $classes = $conn->query($sql1);
                
                while($row1 = $classes->fetch_assoc()){
                $selected = ($row1['class'] == $class) ? 'selected' : '';
                echo "<option value=".$row1['id']." $selected>".$row1['class']."</option>";
                }
                ?>
                </select>
              </div>
              </div>
              <div class="form-group row mt-2">
              <label for="age" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>Age:</label>
              <div class="col-sm-4">
                <input type="number" class="form-control mt-2" id="age" name="age" value="<?php echo $age; ?>" >
              </div>
              <label for="gender" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>Gender:</label>
              <div class="col-sm-4">
                <select class="form-select mt-2" id="gender" name="gender" aria-label="Default select example">
                <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($gender == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              </div>
              <div class="form-group row mt-2">
              <label for="dob" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>DOB:</label>
              <div class="col-sm-4">
                <input type="date" class="form-control mt-2" id="dob" name="date" value="<?php echo $date; ?>" >
              </div>
              
              <label for="file" class="col-sm-2 col-form-label text-end mt-2"><span class="text-danger">*</span>File:</label>
              <div class="col-sm-4 row">
                <div class="col-sm-10">
                <input type="file" class="form-control mt-2" id="file" name="file" value="<?php echo $photo; ?>">
                
                </div>
                <div class="col-sm-2 mt-3">
                <?php echo "<td><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$photo\")'><img src='witness.png' style='width:20px;height:auto;'></a></td>"; ?>

                </div>
              </div>
              <div class="col-sm-2 mt-3">
              </div>
              </div>
              <div class="form-group row mt-2">
              <div class="col-12 text-center">
                <input type="submit" class="btn btn-primary mt-2" name="supdate" value="Update">
              </div>
              </div>
            </div>
             </form>
             </div>


             <div class="container col-8 border shadow p-3 mb-5 bg-body rounded">
              <div class="col-12">
                <h2 class="text-center">Gallery Images</h2>
              </div>
              <div class="row col-12 d-flex r">
                <div class="col-2 text-end mt-3">
                  <h6>Add Files</h6>
                </div>
                <div class="col-10">
                <input type="file" name="files[]" id="files" multiple = "multiple" >
                </div>
                <div class="col-12">
                  <div id="result">
                  <?php
                  
                  $sql = "SELECT * FROM student_gallery WHERE student_id = '$id'";
                  $result = $conn->query($sql);
                  // while ($row = $result->fetch_assoc()) {
                  //   $photo = $row['gallery_photo'];
                  //   echo "<div><img src='$photo' width='100px' height='100px' style='margin-bottom: 10px;'></div>";
                  // }
                  
                  echo "<div style='display: flex; flex-wrap: wrap;'>"; // Start a flex container
                  while ($row = $result->fetch_assoc()) {
                    $photo = $row['gallery_photo'];
                    $image_id = $row['gallery_id'];
                    $student_id = $row['student_id'];
                    echo "<div style='border: 1px solid #ddd; margin: 5px; position: relative; width: calc(20.33% - 10px);'>";
                    echo "<span  style='position: absolute; top: 5px; right: 5px; color: black;cursor:pointer;' onclick='deletePhoto(\"$image_id,$student_id\")'>X</span>";
                    echo "<img src='$photo' width='100%' height='auto' style='display: block;'>";
                    echo "</div>";
                  }
                  echo "</div>";
                  ?>
                  </div>
                </div>
              </div>
             </div>
             <script>
                    document.getElementById('files').addEventListener('change', function() {
		        	      var id =document.getElementById('id').value;
                    var formData = new FormData();
                    var files = document.getElementById('files').files;
                    console.log(files);
                    console.log(id);
                    for (var i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                    }
		        	      formData.append('id', id);
                    document.getElementById('loader').style.display = 'block';
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'view.php', true);
                    xhr.onreadystatechange = function() {
                      if (xhr.readyState == 4) {
                      // Hide loader
                      document.getElementById('loader').style.display = 'none';

                      if (xhr.status == 200) {
                        alert('Files uploaded successfully');
                        window.location.href = 'view.php?action=update&id=' + id;
                      } else {
                        alert('File upload failed. Please try again.');
                      }
                    }
                    
                    };
                    xhr.send(formData);
                });
              
              function deletePhoto(image_id) {
                var ids = image_id.split(',');
                var image_id = ids[0];
                var student_id = ids[1];
                console.log('ok');
                console.log(image_id);
                console.log(student_id);
                document.getElementById('loader').style.display = 'block';

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'view.php?image_id=' + image_id, true);
                xhr.onreadystatechange = function() {
                  if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('Photo deleted successfully');
                    window.location.href = 'view.php?action=update&id=' + student_id;
                  }
                };
                xhr.send();
              }
    </script>
    
    <?php
	

    ?>
             


<?php


?>
<?php
}}
mysqli_free_result($result);
}else{
  ?>
  <div class="row col-12 d-flex text-align-center">
    <div class="row col-6">
    <h2>Search Students</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
    <div class="col-8 d-flex">
    <select class="form-select" name="id" aria-label="Default select example">
                  <?php
                  $sql = "SELECT * FROM class_details WHERE status = 'active'";
                  $classes = $conn->query($sql);
                  echo "<option value='default'  selected>Select a class</option>";
                  while($row = $classes->fetch_assoc()){
                      
                    if(isset($_GET['filter']) && $_GET['id'] == $row['id']){
                      echo "<option selected>".$row['class']."</option>";
    
                    }else{
                      echo "<option value=".$row['id'].">".$row['class']."</option>";
                  }
                }
                  
                  ?>
    </select>
    <input type="submit" value="Filter" name="filter" class="btn btn-primary ms-2">
    <a href="view.php"  class="btn btn-warning ms-2">Clear</a>
    </div>
    </form>
    </div>
  </div>
  </form>
    </div>
      
    
  </div>
 
  </div>
  <div class="container">
  <table class="table table-primary">
      
       

      <?php
  $name = '';
  $age = '';
  $class = '';
  $gender = '';
  if(($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['filter']))){
    $c_id = $_GET['id'];
    ?>
    <thead>
        <tr>
          
          <th scope="col " style="text-align: center;">Registration Id</th>
          <th scope="col ">Name</th>
          <th scope="col " style="text-align: center;">Class</th>
          <th scope="col " style="text-align: center;">Age</th>
          <th scope="col " style="text-align: center;">Dob</th>
          <th scope="col " style="text-align: center;">Gender</th>
          <th scope="col " style="text-align: center;">Action</th>
          <th scope="col " style="text-align: center;">Image</th>
          <th scope="col " style="text-align: center;">Status</th>

          
        </tr>
      </thead>
      <tbody>
    <?php
    if($c_id == 'default'){
    $sql = "SELECT student_details.id,student_details.name,student_details.age,student_details.gender,class_details.class,student_details.priority,student_details.photo, student_details.register_id,student_details.status,student_details.dob FROM student_details INNER JOIN class_details ON student_details.class_id = class_details.id ORDER BY student_details.priority ASC";

    }else{
    $sql = "SELECT student_details.id,student_details.name,student_details.age,student_details.gender,class_details.class,student_details.priority,student_details.photo, student_details.register_id,student_details.status,student_details.dob FROM student_details INNER JOIN class_details ON student_details.class_id = class_details.id WHERE class_id = '$c_id' ORDER BY student_details.priority ASC";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $self = $_SERVER['PHP_SELF'];
        $id = $row["id"];
        echo "<tr>";
        
        echo "<td class='text-center'>" . $row["register_id"]. "</td>";
        echo "<td >" . $row["name"]. "</td>";
        echo "<td class='text-center'>" . $row["class"]. "</td>";
        echo "<td class='text-center'>" . $row["age"]. "</td>";
        echo "<td class='text-center'>" . $row["dob"]. "</td>";
        echo "<td class='text-center'>" . $row['gender']. "</td>";
        
       
        echo "<td class='text-center'>
        <a href='$self?action=update&id=$id' class='btn btn-success'>edit</a>
        <a href='$self?action=delete&id=$id' class='btn btn-danger'>delete</a></td>";
        
        $path =$row['photo'];
        // echo "<td><a href=''><img src='$path' style='width:50px;height:auto;'></a></td>";
        echo "<td class='text-center'><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$path\")'><img src='witness.png' style= 'width:20px;height:auto;' ></a></td>";
       
        $i = $row['id'];
        $checked = ($row['status'] == 'active') ? 'checked' : '';
        echo "<td class='text-center'><input class='input-switch' type='checkbox' id='$i' $checked>
              <label class='label-switch' for='$i'></label>
              <span class='info-text'></span></td>";
        echo "</tr>";

      }
      mysqli_free_result($result);
      } else {
      echo "<td colspan='6' class='text-center'>0 results</td>";
      }
      

  }else{
    $sql2 = "SELECT student_details.id, student_details.name, student_details.age, student_details.gender, class_details.class, student_details.priority,student_details.photo,student_details.register_id,student_details.status,student_details.dob FROM student_details INNER JOIN class_details ON student_details.class_id = class_details.id ORDER BY student_details.priority ASC";
    $result = $conn->query($sql2);
    ?>
    <thead>
        <tr>
          
          <th scope="col " style="text-align: center;">Registration Id</th>
          <th scope="col ">Name</th>
          <th scope="col " style="text-align: center;">Class</th>
          <th scope="col " style="text-align: center;">Age</th>
          <th scope="col " style="text-align: center;">Dob</th>
          <th scope="col " style="text-align: center;">Gender</th>
          <th scope="col " style="text-align: center;">Action</th>
          <th scope="col " style="text-align: center;">Image</th>
          <th scope="col " style="text-align: center;">Status</th>
          <th scope="col " style="text-align: center;">Priority</th>
        </tr>
      </thead>
      <tbody>
        <?php

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $self = $_SERVER['PHP_SELF'];
        $id = $row["id"];
        // echo "<pre>";
        // var_dump($row);
        // echo "</pre>";
        echo "<tr>";
        $priority = $row['priority'];
        echo "<td class='text-center'>" . $row["register_id"]. "</td>";
        echo "<td >" . $row["name"]. "</td>";
        echo "<td class='text-center'>" . $row["class"]. "</td>";
        echo "<td class='text-center'>" . $row["age"]. "</td>";
        echo "<td class='text-center'>" . $row["dob"]. "</td>";
        echo "<td class='text-center'>" . $row['gender']. "</td>";
        
        $p= $row['priority'];
        //echo "<td><a href='$self?action=update&id=$id' class='btn btn-success'>update</a> <a href='$self?action=delete&id=$id' class='btn btn-danger'>delete</a></td>";
            echo "<td  class='text-center'>
            <a href='$self?action=update&id=$id' class='btn btn-success'>Edit Student</a>
            <a href='$self?action=delete&id=$id' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete Student</a></td>";
            $path =$row['photo'];
            // echo "<td><a href=''><img src='$path' style='width:50px;height:auto;'></a></td>";
            echo "<td class='text-center'><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$path\")'><img src='witness.png' style= 'width:20px;height:auto;' ></a></td>";
            $i = $row['id'];
            $checked = ($row['status'] == 'active') ? 'checked' : '';
            echo "<td class='text-center'><input class='input-switch' type='checkbox' id='$i' $checked>
              <label class='label-switch' for='$i'></label>
              <span class='info-text'></span></td>";
            if($p == 1) {
              echo "<td  class='text-center'><a href='$self?action=down&p=$p&id=$id'><img src='download.png' style='width:20px;height:auto;' ></a></td>";
            } elseif($p == $result->num_rows) {
              echo "<td  class='text-center'><a href='$self?action=up&p=$p&id=$id'><img src='up-arrow.png' style= 'width:20px;height:auto;' ></a></td>";
            }else{
              echo "<td  class='text-center'><a href='$self?action=up&p=$p&id=$id'><img src='up-arrow.png' style= 'width:20px;height:auto;' ></a>
              <a href='$self?action=down&p=$p&id=$id'><img src='download.png' style='width:20px;height:auto;' ></a></td>";
            }
            echo "</tr>";
      }
      mysqli_free_result($result);
    }else {
      echo "<p>0 results</p>";
      }
      

  }
}



?>

      </tbody>
    </table>
    </div>
  </div>
  <?php
  if (isset($_GET['action']) && ($_GET['action'] == 'up' || $_GET['action'] == 'down')) {
    $priority = (int)$_GET['p'];
    $id = (int)$_GET['id'];
    $new_priority = ($_GET['action'] == 'up') ? $priority - 1 : $priority + 1;

    // Swap priorities
    $sql = "UPDATE student_details SET priority = $new_priority WHERE priority = $priority AND id = $id";
    $conn->query($sql);

    $sql = "UPDATE student_details SET priority = $priority WHERE priority = $new_priority AND id != $id";
    $conn->query($sql);

    echo "<script>window.location.href = 'view.php';</script>";
    
  }

  if (isset($_GET['action']) && $_GET['action'] == 'updatestatus') {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    echo "<h2>$status</h2>";
    $status = ($status == 'active') ? 'active' : 'inactive';
    $sql = "UPDATE student_details SET status = '$status' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Status updated successfully');window.location.href = 'view.php';</script>";
    } else {
      echo "Error updating status: " . $conn->error;
    }
  }
 
  ?>

  <script>
$(document).ready(function() {
    $('.input-switch').click(function() {
        var id = $(this).attr('id');  // Get the ID of the checkbox
        var status = $(this).prop('checked');  // Check if it is checked or not
        status = (status == true) ? 'active' : 'inactive';  // Set the status accordingly
        $.ajax({  
    type: 'GET',  
    url: 'view.php', 
    data: { action: 'updatestatus', id: id, status: status },
    success: function(response) {
        console.log(status);
        console.log(id);
        console.log('updatestatus');
        alert('Status updated successfully');
    }
});
});
});



  
      $(document).ready(function(){
        $('.close').click(function(){
          $("#myModal").css({"display": "none"});
 
        });
      });
 
      function passMessage(imagePath) {
        // Set the content of the modal body
        // $('#btnid').('show');
        // const btnDiv = document.getElementById('btnid');
       
       
       $("#myModal").css({"display": "block"});
       const path =imagePath;
      $('#modalBody').html('<img src="' + path + '" width="400px"  alt="Student Photo" class="img-fluid">');
 
 
 
 
 
        console.log('Testing Div - ', JSON.stringify(imagePath));
        // $('#modalBody').text('$path');
      }
    </script>

    <script>
          function validate() {
        var name = document.getElementById('name').value;
        var age = document.getElementById('age').value;
        var class_id = document.getElementById('class_id').value;
        var dob = document.getElementById('dob').value;
        var gender = document.getElementById('gender').value;

        var namePattern = /^[a-zA-Z\s]+$/;
        var datePattern = /^\d{4}-\d{2}-\d{2}$/;

        if (!name || !namePattern.test(name)) {
            alert('Please enter a valid name without special characters.');
            return false;
        }

        if (!age) {
            alert("Please enter a valid age");
            return false;
        }

        if (!class_id) {
            alert("Please select a class");
            return false;
        }

        if (!dob || !datePattern.test(dob)) {
            alert('Please enter a valid date in the format YYYY-MM-DD.');
            return false;
        }

        if (gender === '') {
            alert('Please select a gender.');
            return false;
        }

        return true;
    }



    </script>
<!-- 
  <script src="js/modal"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
