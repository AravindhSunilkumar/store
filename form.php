<?php
require_once 'connection.php';
?>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'  && isset($_POST['submit'])){
    // print_r($_REQUEST);die;
    
    if ((!empty($_POST['name'])) && (!empty($_POST['age'])) && (!empty($_POST['gender']) && (!empty($_POST['class'])) )) {
        
        
        function uploadImage($file) {
            $target_dir = "students_images/";
            $random_string = rand(1000,9999); 
            $target_file = $target_dir . $random_string . '_' . basename($file["name"]);
            
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
            // echo "File is an image - " . $check["mime"] . "";
            $uploadOk = 1;
            } else {
            $message =  "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
            }
            if (file_exists($target_file)) {
            $message = "<script>alert('Sorry, file already exists.');</script>";
            $uploadOk = 0;
            }

            
            if ($file["size"] > 5000000) { //5mb
            $message = "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $message =  "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            echo $message;
            return false;
            
            } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                return false;
            }
            }
        }

        $target_file = uploadImage($_FILES["file"]);
        if ($target_file === false) {
            // Handle the error appropriately
            echo $message;
                  echo "<script>window.location.href = 'form.php';</script>";
                  return;
            exit;
        }
        
        
        
        
        
        $name = $conn->real_escape_string((string)$_POST['name']);
        $age = $conn->real_escape_string($_POST['age']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $class = $conn->real_escape_string($_POST['class']);
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $sql2 = "SELECT * FROM student_details ORDER BY priority DESC LIMIT 1";
        $result2 = $conn->query($sql2);
        
        if ($result2 && $result2->num_rows > 0) {
            $data = $result2->fetch_assoc();
            $priority = $data['priority'] + 1;
            // echo "p ok";
            
        } else {
            $priority = 1; // Default priority if no records found
        }

        // echo "it ok";
            $sql = "INSERT INTO student_details(name, class_id, age, gender, priority, photo, register_id,dob) VALUES ('$name', '$class', '$age', '$gender', '$priority', '$target_file', '$student_id','$dob')";
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                if (!empty($_FILES['files']['name'][0])) {
                    foreach ($_FILES['files']['name'] as $key => $value) {
                        $file = array(
                            'name' => $_FILES['files']['name'][$key],
                            'type' => $_FILES['files']['type'][$key],
                            'tmp_name' => $_FILES['files']['tmp_name'][$key],
                            'error' => $_FILES['files']['error'][$key],
                            'size' => $_FILES['files']['size'][$key]
                        );
                        $gallery_file = uploadImage($file);
                        if ($gallery_file !== false) {
                            $sql_gallery = "INSERT INTO student_gallery(student_id, gallery_photo) VALUES ('$last_id', '$gallery_file')";
                            if ($conn->query($sql_gallery) !== TRUE) {
                                echo $conn->error;
                            }
                        }
                    }
                }
                echo "<script>
                alert('successfull');window.location.href = 'view.php';
            </script>";
            // header('Location: view.php');
            }else{
                echo $conn->error;
            }
            $conn->close();
        
        
       
    }else{
        echo "<script>
            alert('Data is missing');
        </script>";
    }
}
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'GET' and (isset($_GET['dob']))){
    $dob = $_GET['dob'];
    $dobDate = new DateTime($dob);
    $currentDate = new DateTime();
    $age = $currentDate->diff($dobDate)->y;
    $stmt = $conn->prepare("SELECT * FROM class_details WHERE age_from <= ? AND age_to >= ?");
    $stmt->bind_param("ii", $age, $age);
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = array();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $classes[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "$age", "classes" => $classes));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h3 {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .form-check {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- nav -->
    <ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="nav-link active btn btn-warning" aria-current="page" href="view.php">Home</a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link btn btn-warning" href="form.php">Add Student</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " aria-disabled="true"></a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn btn-warning" href="class_insert.php">Add Class</a>
    </li>
    </ul>
    <!-- nav  end-->

<div class="container  ">
    <center><h3>Student Registration</h3></center>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" onsubmit="return validate()">
        <?php
        
        $sql3 = "SELECT * FROM student_details ORDER BY id DESC LIMIT 1";
         $result3 = $conn->query($sql3);
         $row3 = $result3->fetch_assoc();
         $reg_id = str_pad($row3['register_id'] + 1, 4, '0', STR_PAD_LEFT);
    ?>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="student_id" class="form-label">Registration Id</label>
                <input type="text" name="student_id" class="form-control" id="student_id" value = "<?php echo $reg_id;?>" required readonly>
            </div>    
            
        </div>
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label"><span class="text-danger">*</span>Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="dob" class="form-label"><span class="text-danger">*</span>Dob</label>
                <input type="date" name="dob" class="form-control" id="dob" onchange="dobCall(this.value)" required>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="age" class="form-label"><span class="text-danger">*</span>Age</label>
                <input type="number" name="age" class="form-control" id="age" required readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="class"><span class="text-danger">*</span>Class</label>
                <div class="dropdown">
                <select class="form-select" name="class" id="class_id" aria-label="Default select example" required>
                    <option value=""></option>       
                </select>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="file" class="form-label"><span class="text-danger">*</span>Image</label>
                <input type="file" name="file" class="form-control" id="file" required>
                    <span class="text-danger"><i>upload files png,jpg,jpeg.(5MB)</i></span>
                        
            </div>              
        </div>
    </div>
    
         
         
         
         <div class="mb-3">
              <label for="gender" class="form-label"><span class="text-danger">*</span>Gender</label>
              <div class="form-check form-check-inline">
                    <input type="radio" name="gender" class="form-check-input" id="male" value="male" required>
                    <label class="form-check-label" for="male">Male</label>
              </div>
              <div class="form-check form-check-inline">
                    <input type="radio" name="gender" class="form-check-input" id="female" value="female" required>
                    <label class="form-check-label" for="female">Female</label>
              </div>
              <div class="form-check form-check-inline">
                    <input type="radio" name="gender" class="form-check-input" id="other" value="other" required>
                    <label class="form-check-label" for="other">Other</label>
              </div>
              <div class="mb-3">
                    <label for="files" class="form-label">Gallery</label>
                    <input type="file" class="form-control" name="files[]" id="files" multiple="multiple">
                    <span class="text-danger"><i>upload files png,jpg,jpeg.(5MB)</i></span>

              </div>
              
         </div>
         <input type="submit" name="submit" class="btn btn-primary" value="Register">
    </form>
    </div>

    <script>
        function dobCall(val) {
    const apiUrl = 'form.php?dob=' + val;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the age field
            document.getElementById("age").value = data.status;

            // Get class select element
            let classSelect = document.getElementById("class_id");
            classSelect.innerHTML = '<option value="">Select Class</option>'; // Reset options

            // Append new options from API response
            data.classes.forEach(cls => {
            let option = document.createElement("option");
            option.value = cls.id;
            option.textContent = cls.class;
            classSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

    </script>
    <script>

    function validate() {
         const name = document.getElementById('name').value.trim();
         const age = document.getElementById('age').value;
         const dob = document.getElementById('dob').value;
         const file = document.getElementById('file').value;
         const gender = document.querySelector('input[name="gender"]:checked');
         
         if (name === '') {
              alert('Name is required.');
              return false;
         }
         
         if (age === '' || age <= 0) {
              alert('Please enter a valid age.');
              return false;
         }
         
         if (dob === '') {
              alert('Date of birth is required.');
              return false;
         }
         
         const dobDate = new Date(dob);
         const today = new Date();
         
         if (dobDate > today) {
              alert('Enter a valid Date of birth.');
              return false;
         }
         
         if (file === '') {
              alert('Image is required.');
              return false;
         }
         
         if (!gender) {
              alert('Gender is required.');
              return false;
         }
         
         return true;
    }
    </script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
