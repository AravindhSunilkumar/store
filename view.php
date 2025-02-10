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
  $name = $conn->real_escape_string($_POST['name']);
  $age = $conn->real_escape_string($_POST['age']);
  $class = $conn->real_escape_string($_POST['class_id']);
  $gender = $conn->real_escape_string($_POST['gender']);
  $date = $conn->real_escape_string($_POST['date']);
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
  }
  
  



        //file uploading 
        if(!empty($_FILES["file"]["name"])){
              $message = '';
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
                  $message = "<script>alert('File is not an image.');</script>";
                  $uploadOk = 0;
                  }
              }

              // Check if file already exists
              if (file_exists($target_file)) {
                  $message = "<script>alert('Sorry, file already exists.');</script>";
                  $uploadOk = 0;
              }

              // Check file size
                if ($_FILES["file"]["size"] > 5000000) { // 5000000 bytes is approximately 5 MB
                  $message = "<script>alert('Sorry, your file is too large.');</script>";
                  $uploadOk = 0;
              }

              // Allow certain file formats
              if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                  && $imageFileType != "gif") {
                  $message = "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                  $uploadOk = 0;
              }

              // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                  echo $message;
                  echo "<script>window.location.href = 'view.php?action=update&id=$id';</script>";
                  return;

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

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
  $id = (int)$_GET['id'];
  $sql = "UPDATE student_details SET status = 'deleted' WHERE id = '$id'";
  if ($conn->query($sql) === TRUE) {
  // Get the priority of the deleted row
  $prioritySql = "SELECT priority FROM student_details WHERE id = '$id'";
  $priorityResult = $conn->query($prioritySql);
  if ($priorityResult->num_rows > 0) {
    $priorityRow = $priorityResult->fetch_assoc();
    $priority = $priorityRow['priority'];
    $lastPrioritySql = "SELECT MAX(priority) AS max_priority FROM student_details where status != 'deleted'";
    $lastPriorityResult = $conn->query($lastPrioritySql);
    if ($lastPriorityResult->num_rows > 0) {
      $lastPriorityRow = $lastPriorityResult->fetch_assoc();
      $maxPriority = $lastPriorityRow['max_priority'];

      // Swap priorities
      $updateDeletedPrioritySql = "UPDATE student_details SET priority = $maxPriority WHERE id = '$id'";
      $conn->query($updateDeletedPrioritySql);

      $updateMaxPrioritySql = "UPDATE student_details SET priority = $priority WHERE priority = $maxPriority AND id != '$id'";
      $conn->query($updateMaxPrioritySql);
    }
    echo "<script>
    alert('Record deleted successfully. Priority was: $priority');
    window.location.href = 'view.php';
    </script>";
  } else {
    echo "<script>
    alert('Record deleted successfully');
    window.location.href = 'view.php';
    </script>";
  }
  } else {
 echo "<script>alert('Error deleting record: ' . $conn->error')</script>";;
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display</title>


  <link rel="stylesheet" type="text/css" href="css_files/modal.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <!-- nav -->
  <ul class="nav justify-content-center">
    <li class="nav-item mt-2">
        <a class="nav-link active btn btn-warning" aria-current="page"  href="view.php">Home</a>
    </li>
    
    <li class="nav-item ml-2 mt-2">
        <a class="nav-link btn btn-warning ml-2" href="form.php">Add Student</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " aria-disabled="true"></a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn btn-warning" href="class_insert.php">Add Class</a>
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
  <div id="blockScreen" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.22); z-index:9999;">
    <div id="loader"   style=" position: absolute; left: 50%; top: 35%;" class="spinner-grow text-info" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>           
  </div>
  <!-- loader end -->

  <!-- alert -->
  <!-- <div id="alert" style="display:none;" class="alert  alert-dismissible fade show" role="alert">
      <strong id='text'></strong> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
  </div> -->
<!-- alert end -->
<!-- Modal -->
<div id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 88%;">
      <div class="modal-header" style="background-color:rgb(255, 255, 255);">
        <a href="#" class="close" data-dismiss="modal" style="position: absolute;right: 2px;top: -15px;color:black;">&times;</a>
        <h4 class="modal-title">Photo</h4>
      </div>
      <div class="modal-body" id="modalBody" style="background-color: #ffffff;">
      </div>
      
    </div>
  </div>
</div>


<?php


?>


  <div class="container">
      
            <?php
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

             <div class="container col-8 border shadow p-3 mb-5 bg-body rounded dblur">
                  <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" onsubmit="if (!validate()) { document.getElementById('blockScreen').style.display = 'none'; document.querySelector('.dblur').style.filter = 'none'; return false; }">
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
                      <?php echo "<td><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$photo\")'><img src='witness.png' style='width:20px;height:auto;' alt='Witness Image'></a></td>"; ?>

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


             <div  class="container col-8 border shadow p-3 mb-5 bg-body rounded">
              <div class="col-12">
                <h2 class="text-center">Gallery Images</h2>
              </div>
              <div class="row col-12 d-flex r">
                <div class="col-2 text-end mt-3">
                  <h6>Add Files :</h6>
                </div>
                <div class="col-10">
                <input type="file" name="files[]" id="files" multiple = "multiple" >
                </div>
                <div class="col-12" id="Gallery">
                  <div id="result">
                  <?php
                  $sql = "SELECT * FROM student_gallery WHERE student_id = '$id'";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                    echo "<div style='display: flex; flex-wrap: wrap;'>"; // Start a flex container
                    while ($row = $result->fetch_assoc()) {
                      $photo = $row['gallery_photo'];
                      $image_id = $row['gallery_id'];
                      $student_id = $row['student_id'];
                      echo "<div style='border: 1px solid #ddd; margin: 5px; position: relative; width: calc(20.33% - 10px);'>";
                      echo "<span style='position: absolute; top: 5px; right: 5px; color: black; cursor: pointer;' onclick='deletePhoto(\"$image_id,$student_id\")'>X</span>";
                      echo "<img src='$photo' width='100%' height='auto' style='display: block;' alt='Gallery image'>";
                      echo "</div>";
                    }
                    echo "</div>";
                  } else {
                    echo "<div class = 'col-12 text-center mt-3' ><p>No images found in the gallery.</p></div>";
                  }
                  ?>
                  </div>
                </div>
              </div>
             </div>
             

             


            <?php
              }}
              mysqli_free_result($result);
              }elseif(isset($_GET['action']) && $_GET['action'] == 'gallery'){
  //Gallery view----==================================================Gallery view================================Gallery view================================Gallery view===============================================================
              $id = $_GET['id'];
              $name = $_GET['name'];
              $sql = "SELECT student_gallery.gallery_photo, student_details.name,student_details.register_id, student_details.age, student_details.gender, class_details.class, student_details.dob FROM student_gallery INNER JOIN student_details ON student_gallery.student_id = student_details.id INNER JOIN class_details ON student_details.class_id = class_details.id WHERE student_gallery.student_id = '$id'";
              $result = $conn->query($sql);
              if($result->num_rows > 0){
                $studentDetails = $result->fetch_assoc();
                ?>
    <div class="container border shadow">
      <div class="row col-12 text-center">
        <h2>Student Details</h2>
      </div>
      <hr><hr>
      <div class="row col-12 ">
        <strong><p>Registration ID : <?php echo ucfirst($studentDetails['register_id']);?> </p></strong>
        <strong><p>Student Name : <?php echo ucfirst($studentDetails['name']);?> </p></strong>
        <strong><p>Age : <?php echo $studentDetails['age'];?> </p></strong>
        <strong><p>Gender : <?php echo ucfirst($studentDetails['gender']);?> </p></strong>
        <strong><p>Class : <?php echo ucfirst($studentDetails['class']);?> </p></strong>
        <strong><p>Date of Birth : <?php echo date('jS M Y', strtotime($studentDetails['dob']));?> </p></strong>
      </div>
      <hr><hr>
      <div class="row col-12 text-center">
        <h2>Gallery</h2>
      </div>
      <hr><hr>
      <div class="row mt-4">
        <?php
        do {
          ?>
          <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card" style="width: 100%;">
              <img class="card-img-top" src="<?php echo $studentDetails['gallery_photo'] ?>" alt="Card image cap">
            </div>
          </div>
          <?php 
        } while($studentDetails = $result->fetch_assoc()); 
        ?> 
      </div>
    </div>
    <?php
  } else {
    $dob = '';
    $age = '';
    $gender = '';
    $class = '';
    echo "<div class='container border shadow'>
    <div class='row col-12 text-center'>
    <h2>Student Details</h2></div><hr><hr>
    <div class='row col-12 '><strong><p>Registration ID : $id </p></strong>
    <strong><p>Student Name : $name </p>
    </div><hr><hr>
    <div class='row col-12 text-center'>
    <h2>Gallery</h2>
    </div><hr><hr>
    <div class='row mt-4'>
    <p>No images found in the gallery.</p>
    <div class='text-center mt-3'>
      <a href='view.php?action=update&id=<?php echo $id; ?>' class='btn btn-success btn-sm'>Add Gallery</a>
    </div>
    </div></div>";
  
  }

}else{
  //students view----==================================================students view================================students view================================students view===============================================================
  ?>
  <div class="row col-12 d-flex text-align-center ">
    <div class="row col-12">
      <h2>Search Students</h2>
      <div class="col-12">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
          <div class="col-8 d-flex">
            <select class="form-select me-2" name="id" aria-label="Default select example">
              <?php
              $sql = "SELECT * FROM class_details WHERE status = 'active'";
              $classes = $conn->query($sql);
              echo "<option value='default' selected>Select a class</option>";
              while($row = $classes->fetch_assoc()){
                if(isset($_GET['filter']) && $_GET['id'] == $row['id']){
                  echo "<option selected>".$row['class']."</option>";
                } else {
                  echo "<option value=".$row['id'].">".$row['class']."</option>";
                }
              }
              ?>
            </select>
            <input type="text" name="studentname" id="student_name" value="<?php echo (isset($_GET['studentname'])) ? $_GET['studentname'] : ''; ?>" class="form-control me-2" placeholder="Enter a Name">
            <input type="text" name="registerid" id="registerid" value="<?php echo (isset($_GET['registerid'])) ? $_GET['registerid'] : ''; ?>" class="form-control me-2" placeholder="Enter a register ID">
            <input type="submit" value="Filter" name="filter" class="btn btn-primary me-2">
            <a href="view.php" class="btn btn-warning">Clear</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="container dblur">
  <table class="table table-primary dblur table-overflow">
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
          <th scope="col " style="text-align: center;">Gallery</th>
          <th scope="col " style="text-align: center;">Status</th>

          
        </tr>
      </thead>
      <tbody>
    <?php
    $c_id = isset($_GET['id']) && $_GET['id'] !== 'default' ? $conn->real_escape_string($_GET['id']) : '';
    $studentname = isset($_GET['studentname']) ? $conn->real_escape_string($_GET['studentname']) : '';
    $registerid = isset($_GET['registerid']) ? $conn->real_escape_string($_GET['registerid']) : '';

    // Base query
    $sql = "SELECT 
                student_details.id,
                student_details.name,
                student_details.age,
                student_details.gender,
                class_details.class,
                student_details.priority,
                student_details.photo,
                student_details.register_id,
                student_details.status,
                student_details.dob 
            FROM student_details 
            INNER JOIN class_details ON student_details.class_id = class_details.id 
            WHERE (student_details.status = 'active' OR student_details.status = 'inactive')";

  
    $conditions = array();

    if ($c_id !== '') {
        $conditions[] = "student_details.class_id = '$c_id'";
    }

    if (!empty($studentname)) {
        $conditions[] = "student_details.name LIKE '%$studentname%'";
    }

    if (!empty($registerid)) {
        $conditions[] = "student_details.register_id = '$registerid'";
    }

    
    if (count($conditions) > 0) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    
    $sql .= " ORDER BY student_details.priority ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $self = $_SERVER['PHP_SELF'];
        $id = $row["id"];
        echo "<tr>";
        
        echo "<td class='text-center'>" . $row["register_id"]. "</td>";
        echo "<td >" . ucfirst($row["name"]). "</td>";
        echo "<td class='text-center'>" . ucfirst($row["class"]). "</td>";
        echo "<td class='text-center'>" . $row["age"]. "</td>";
        echo "<td class='text-center'>" . date('jS M Y', strtotime($row["dob"])). "</td>";
        echo "<td class='text-center'>" . ucfirst($row['gender']). "</td>";
        
       
        echo "<td  class='text-center'>
        <a href='$self?action=update&id=$id' class=''><img src='edit.png' style='width:20px;height:auto;' alt='edit image'></a>
        <div style='display:inline-block; width:10px;'></div>
        <a href='#' class='' onclick='confirmDelete(\"$id\")'><img src='delete.png' style='width:20px;height:auto;' alt='delete image'></a>
        </td>";
        
        $path =$row['photo'];
        // echo "<td><a href=''><img src='$path' style='width:50px;height:auto;'></a></td>";
        echo "<td class='text-center'><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$path\")'><img src='witness.png' style= 'width:20px;height:auto;' alt='edit image ></a></td>";
        echo "<td class='text-center'><a href='$self?action=gallery&id=$id&name=$name'   ><img src='gallery.png' style= 'width:20px;height:auto;' alt='gallery image ></a></td>";
       
        $i = $row['id'];
        $checked = ($row['status'] == 'active') ? 'checked' : '';
        echo "<td class='text-center'><input class='input-switch' type='checkbox' id='$i' $checked>
              <label class='label-switch' for='$i'></label>
              <span class='info-text'></span></td>";
        echo "</tr>";

      }
      mysqli_free_result($result);
      } else {
      echo "<td colspan='10' class='text-center'>0 results</td>";
      }
      

  }else{
    $sql2 = "SELECT * FROM student_details ORDER BY priority DESC LIMIT 1";
        $result2 = $conn->query($sql2);
        
        if ($result2 && $result2->num_rows > 0) {
            $data = $result2->fetch_assoc();
            $lastpriority = $data['priority'];
            // echo "p ok";
            
        } else {
            $priority = 1; // Default priority if no records found
        }
    $sql2 = "SELECT student_details.id, student_details.name, student_details.age, student_details.gender, class_details.class, student_details.priority,student_details.photo,student_details.register_id,student_details.status,student_details.dob FROM student_details INNER JOIN class_details ON student_details.class_id = class_details.id WHERE student_details.status = 'active' OR student_details.status = 'inactive'  ORDER BY student_details.priority ASC";
    $result = $conn->query($sql2);
    ?>
    <thead class="dblur">
        <tr>
          
          <th scope="col " style="text-align: center;" class="dblur">Registration Id</th>
          <th scope="col ">Name</th>
          <th scope="col " style="text-align: center;">Class</th>
          <th scope="col " style="text-align: center;">Age</th>
          <th scope="col " style="text-align: center;">Dob</th>
          <th scope="col " style="text-align: center;">Gender</th>
          <th scope="col " style="text-align: center;">Action</th>
          <th scope="col " style="text-align: center;">Image</th>
          <th scope="col " style="text-align: center;">Gallery</th>
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
        echo "<td >" . ucfirst($row["name"]). "</td>";
        echo "<td class='text-center'>" . ucfirst($row["class"]). "</td>";
        echo "<td class='text-center'>" . $row["age"]. "</td>";
        echo "<td class='text-center'>" . date('jS M Y', strtotime($row["dob"])). "</td>";
        echo "<td class='text-center'>" . ucfirst($row['gender']). "</td>";
        
        $p= $row['priority'];
        $name= $row['name'];
        
        echo "<td  class='text-center'>
            <a href='$self?action=update&id=$id' class=''><img src='edit.png' style='width:20px;height:auto;' ></a>
            <div style='display:inline-block; width:10px;'></div>
            <a href='#' class='' onclick='confirmDelete(\"$id\")'><img src='delete.png' style='width:20px;height:auto;' ></a>
        </td>";
            $path =$row['photo'];
            // echo "<td><a href=''><img src='$path' style='width:50px;height:auto;'></a></td>";
            echo "<td class='text-center'><a href='#' class='' data-toggle='modal' id='btnid' onclick='passMessage(\"$path\")'><img src='witness.png' style= 'width:20px;height:auto;' ></a></td>";
            echo "<td class='text-center'><a href='$self?action=gallery&id=$id&name=$name'   ><img src='gallery.png' style= 'width:20px;height:auto;' ></a></td>";
            $i = $row['id'];
            $checked = ($row['status'] == 'active') ? 'checked' : '';
            echo "<td class='text-center'><input class='input-switch' type='checkbox' id='$i' $checked>
              <label class='label-switch' for='$i'></label>
              <span class='info-text'></span></td>";
            
            if($p == 1) {
            
              echo "<td  class='text-center'><a href='#' onclick='changePriority(\"down\", $p, $id)'><img src='download.png' style='width:20px;height:auto;' ></a></td>";
            } elseif($p == $lastpriority) {
              echo "<td  class='text-center'><a href='#' onclick='changePriority(\"up\", $p, $id)'><img src='up-arrow.png' style= 'width:20px;height:auto;' ></a></td>";
            } else {
              
              echo "<td  class='text-center'><a href='#' onclick='changePriority(\"up\", $p, $id)'><img src='up-arrow.png' style= 'width:20px;height:auto;' ></a>
              <a href='#' onclick='changePriority(\"down\", $p, $id)'><img src='download.png' style='width:20px;height:auto;' ></a></td>";
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
    echo "<script></script>";
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


  <script src="js/modal.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
