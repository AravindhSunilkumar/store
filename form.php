<?php
require_once 'connection.php';
?>
<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'  && isset($_POST['submit'])){
    // print_r($_REQUEST);die;
    $student_id = $conn->real_escape_string($_POST['student_id']);
    echo "<br>".$student_id;
    if ((!empty($_POST['age'])) && (!empty($_POST['gender']) && (!empty($_POST['class'])) )) {
        
        
        function uploadImage($file,$student_id) {
            $target_dir = "students_images/";
             
            $target_file = $target_dir . $student_id . '_' . basename($file["name"]);
            
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

        $target_file = uploadImage($_FILES["file"],$student_id);
        if ($target_file === false) {
            // Handle the error appropriately
            echo $message;
                  echo "<script>window.location.href = 'form.php';</script>";
                  return;
            exit;
        }
        
        
        
        
        
        $fname = $conn->real_escape_string((string)$_POST['fname']);
        $lname = $conn->real_escape_string((string)$_POST['lname']);
        $name = $fname . " " . $lname;
        $age = $conn->real_escape_string($_POST['age']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $class = $conn->real_escape_string($_POST['class']);
        
        $dob = $conn->real_escape_string($_POST['dob']);
        $hobbies = implode(',', array_map(array($conn, 'real_escape_string'), $_POST['hobbies']));
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
            $sql = "INSERT INTO student_details(name, class_id, age, gender, priority, photo, register_id,dob,hobbies) VALUES ('$name', '$class', '$age', '$gender', '$priority', '$target_file', '$student_id','$dob','$hobbies')";
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
                        $number = rand(0000,9999);
                        $gallery_file = uploadImage($file,$number);
                        if ($gallery_file !== false) {
                            $sql_gallery = "INSERT INTO student_gallery(student_id, gallery_photo) VALUES ('$last_id', '$gallery_file')";
                            if ($conn->query($sql_gallery) !== TRUE) {
                                echo $conn->error;
                            }
                        }
                    }
                }
                $father_name = $conn->real_escape_string((string)$_POST['fathername']);
                $fphonenumber = $conn->real_escape_string((string)$_POST['fphonenumber']);
                $femailid = $conn->real_escape_string((string)$_POST['femailid']);
                $mothername = $conn->real_escape_string((string)$_POST['mothername']);
                $mphonenumber = $conn->real_escape_string((string)$_POST['mphonenumber']);
                $memailid = $conn->real_escape_string((string)$_POST['memailid']);
                $primary = $conn->real_escape_string((string)$_POST['primary']);
                $sql = "insert into parent_details (student_id,father_name,father_number,father_mail,mother_name,mother_number,mother_mail,primary_contact) values ('$last_id','$father_name','$fphonenumber','$femailid','$mothername','$mphonenumber','$memailid','$primary')";
                if ($conn->query($sql) !== TRUE) {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
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
            alert('Data is missing');window.location.href = 'form.php';
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
        .container-form{
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 25px;

        }
        .size{
            font-size:smaller;
        }
        .input-select{
            margin-top:8px;
        }
        input.form-bottom{
            margin-bottom:0px;
        }
        
    </style>
</head>
<body class="">
    <!-- nav -->
    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="nav-link  btn btn-warning mt-2" aria-current="page" href="view.php">Home</a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link btn btn-warning mt-2" href="form.php">Add Student</a>
        </li>
        
        <li class="nav-item ">
            <a class="nav-link btn btn-warning mt-2" href="class_insert.php">Add Class</a>
        </li>
    </ul>
    <!-- nav  end-->

<!-- loader -->
<div id="blockScreen" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.22); z-index:9999;">
    <div id="loader"   style=" position: absolute; left: 50%; top: 35%;" class="spinner-grow text-info" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>           
  </div>
  <!-- loader end -->
<div class=" col-md-8 container-form  mt-4 dblur ">
    <center><h3>Student Registration</h3></center>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" onsubmit="return validate()">
        <?php
        
        $sql3 = "SELECT * FROM student_details ORDER BY id DESC LIMIT 1";
         $result3 = $conn->query($sql3);
         $row3 = $result3->fetch_assoc();
         $reg_id = str_pad($row3['register_id'] + 1, 4, '0', STR_PAD_LEFT);
    ?>
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="student_id" class="form-label">Registration Id</label>
                <input type="text" name="student_id" class="form-control form-bottom" id="student_id" value = "<?php echo $reg_id;?>"  readonly>
            </div>    
            
        </div>
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="fname" class="form-label"><span class="text-danger">*</span>First Name</label>
                <input type="text" name="fname" class="form-control form-bottom" id="fname" >
                <span class="text-danger size" id = "alertfname"></span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="lname" class="form-label"><span class="text-danger">*</span>Last Name</label>
                <input type="text" name="lname" class="form-control form-bottom" id="lname" >
                <span class="text-danger size" id = "alertlname"></span>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="dob" class="form-label"><span class="text-danger">*</span>Dob</label>
                <input type="date" name="dob" class="form-control form-bottom" id="dob" onchange="dobCall(this.value)" >
                <span class="text-danger size" id = "alertdob"></span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="age" class="form-label"><span class="text-danger">*</span>Age</label>
                <input type="number" name="age" class="form-control form-bottom" id="age"  readonly>
                <span class="text-danger size" id = "alertage"></span>

            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="mb-3">
                <label for="class"><span class="text-danger">*</span>Class</label>
                <div class="dropdown">
                <select class="form-select input-select" name="class" id="class_id" aria-label="Default select example" >
                    <option value=""></option>       
                </select>
                <span class="text-danger size" id = "alertclass"></span>
                </div>
            </div>
        </div>
        
    </div>
    <div class="row">
    <div class="col-12 col-md-6">
        <div class="mb-3">
            <label for="file" class="form-label"><span class="text-danger">*</span>Image</label>
            <input type="file" name="file" class="form-control" id="file" style="margin:0px;" onchange="showViewButton()">
            <span class="text-danger size" id="alertimage"><i>upload files png,jpg,jpeg.(5MB)</i></span>
        </div>
        <div class="mb-3">
            <button type="button" id="viewButton" class="btn btn-info mt-2" style="display:none;" onclick="viewImage()">View</button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="imagePreview" src="#" alt="Image Preview" style="max-width:100%; height:auto;">
                </div>
            </div>
        </div>
    </div>

    
        <div class="col-12 col-md-6">
            <div class="mb-3">
            <label for="files" class="form-label " >Gallery:</label>
            <input type="file" class="form-control" name="files[]" id="files" multiple="multiple" onchange="showGalleryButton()">
            </div>
            <div class="text-center" style="margin-top:-20px">
            <span class="text-danger text-center size" id = ""><i>upload files png,jpg,jpeg.(5MB)</i></span>
            </div>
            <div class="mb-3">
                <button type="button" id="galleryButton" class="btn btn-info mt-2" style="display:none;" onclick="viewGallery()">View Gallery</button>
            </div>
        </div>

        <!-- Gallery Modal -->
        <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="galleryModalLabel">Gallery Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="galleryPreview" class="d-flex flex-wrap"></div>
                    </div>
                </div>
            </div>
        </div>

       
    </div>
    <div class="row mt-2">
        <div class="col-12 col-md-6">
            <div class="mb-3">
                
                <div>
                <label for="gender" class="form-label"><span class="text-danger">*</span>Gender : </label>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" class="form-check-input" id="male" value="male" >
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" class="form-check-input" id="female" value="female" >
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" class="form-check-input" id="other" value="other" >
                        <label class="form-check-label" for="other">Other</label>
                    </div>
                    <span class="text-danger size" id = "alertgender"></span>

                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-12">
            <h3>Parent Details</h3>
        </div>
    </div>
    
    <div class="row border rounded">
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="fathername" class="form-label"><span class="text-danger">*</span>Father Name</label>
                    <input type="text" name="fathername" class="form-control form-bottom" id="fathername"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="fphonenumber" class="form-label"><span class="text-danger">*</span>Mobile Number</label>
                    <input type="text" name="fphonenumber" class="form-control form-bottom" id="fphonenumber"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="femailid" class="form-label"><span class="text-danger">*</span>Email Id</label>
                    <input type="text" name="femailid" class="form-control form-bottom" id="femailid"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
    </div>
    <div class="row border rounded mt-2">
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="mothername" class="form-label"><span class="text-danger">*</span>Mother Name</label>
                    <input type="text" name="mothername" class="form-control form-bottom" id="mothername"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="mphonenumber" class="form-label"><span class="text-danger">*</span>Mobile Number</label>
                    <input type="text" name="mphonenumber" class="form-control form-bottom" id="mphonenumber"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
        <div class="col-12 col-md-4">
                <div class="mb-3">
                    <label for="memailid" class="form-label"><span class="text-danger">*</span>Email Id</label>
                    <input type="text" name="memailid" class="form-control form-bottom" id="memailid"  >
                    <span class="text-danger size"></span>
                </div>
        </div>
    </div>
    <div class="row">
    <div class="col-12 col-md-6">
            <div class="mb-3">
                
                <div>
                <label for="primary" class="form-label"><span class="text-danger">*</span>Primary Contact: </label>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="primary" class="form-check-input" id="father" value="father" >
                        <label class="form-check-label" for="father">Father</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="primary" class="form-check-input" id="mother" value="mother" >
                        <label class="form-check-label" for="mother">Mother</label>
                    </div>
                    <span class="text-danger  size" id = "alertprimary"></span>

                </div>
            </div>
    </div>
    
    
        <div class="row mt-2">
          <div class="col-12 col-md-6">
            <div id="hobbies-container">
              <div class="dem mb-3"><label for="">Hobbies</label>
                <input type="text" name="hobbies[]" class="form-control col-6 col-md-3" placeholder="Enter a hobby">
                <button class="sm-btn btn-outline-secondary col-6 col-md-3" type="button" onclick="addHobby()">Add More</button>
              </div>
            </div>
          </div>
        </div>
                    <script>
                      function addHobby() {
                        var container = document.getElementById('hobbies-container');
                        var inputGroup = document.createElement('div');
                        inputGroup.className = 'dem mb-3';
                        inputGroup.innerHTML = '<input type="text" name="hobbies[]" class="form-control" placeholder="Enter a hobby"><button class="btn btn-outline-secondary" type="button" onclick="removeHobby(this)">Remove</button>';
                        container.appendChild(inputGroup);
                      }

                      function removeHobby(button) {
                        var inputGroup = button.parentElement;
                        inputGroup.remove();
                      }
                    </script>
    <div class="row">
        <div class="col-12 d-flex justify-content-between mt-3">
            <div class="col-auto">
                <a class="btn btn-primary btn-sm" href="javascript:history.back()">Go Back</a>
            </div>
            <div class="col-auto d-flex">
                <a class="btn btn-warning btn-sm" href="form.php">Cancel</a>
                <input type="submit" name="submit" class="btn btn-primary btn-sm ml-2" value="Register">
            </div>
        </div>
    </div>

    </form>
    </div>

    <script>
        document.getElementById("fname").focus();
        function dobCall(val) {
            document.getElementById('blockScreen').style.display = 'block';
            document.querySelector('.dblur').style.filter = 'blur(8px)';
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

                    if (data.classes.length > 0) {
                        // Append new options from API response
                        data.classes.forEach(cls => {
                            let option = document.createElement("option");
                            option.value = cls.id;
                            option.textContent = cls.class;
                            classSelect.appendChild(option);
                        });
                    } else {
                        alert('No class available for the selected age.');
                        document.getElementById("age").value = '';
                        document.getElementById("dob").value = '';
                    }

                    document.getElementById('blockScreen').style.display = 'none';
                    document.querySelector('.dblur').style.filter = 'none';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('blockScreen').style.display = 'none';
                    document.querySelector('.dblur').style.filter = 'none';
                });
        }
    </script>
    <script>

    function validate() {
    const fname = document.getElementById('fname').value.trim();
    const lname = document.getElementById('lname').value.trim();
    const age = document.getElementById('age').value;
    const dob = document.getElementById('dob').value;
    const class_id = document.getElementById('class_id').value.trim();
    const file = document.getElementById('file').value;
    const gender = document.querySelector('input[name="gender"]:checked');
    const fatherName = document.getElementById('fathername').value.trim();
    const fatherPhone = document.getElementById('fphonenumber').value.trim();
    const fatherEmail = document.getElementById('femailid').value.trim();
    const motherName = document.getElementById('mothername').value.trim();
    const motherPhone = document.getElementById('mphonenumber').value.trim();
    const motherEmail = document.getElementById('memailid').value.trim();
    const primaryContact = document.querySelector('input[name="primary"]:checked');

    // Name validation
    if (fname === '') {
        document.getElementById('alertfname').innerHTML = 'First Name is required.';
        return false;
    } else {
        document.getElementById('alertfname').innerHTML = '';
    }
    if (lname === '') {
        document.getElementById('alertlname').innerHTML = 'Last Name is required.';
        return false;
    } else {
        document.getElementById('alertlname').innerHTML = '';
    }
    // Date of birth validation
    if (dob === '') {
        document.getElementById('alertdob').innerHTML = 'Date of birth is required.';
        return false;
    } else {
        document.getElementById('alertdob').innerHTML = '';
    }

    // Age validation
    if (age === '' || age <= 0) {
        document.getElementById('alertage').innerHTML = 'Please enter a valid age.';
        return false;
    } else {
        document.getElementById('alertage').innerHTML = '';
    }

    // Class validation
    if (class_id === '') {
        document.getElementById('alertclass').innerHTML = 'Class is required.';
        return false;
    } else {
        document.getElementById('alertclass').innerHTML = '';
    }

    // Image upload validation
    if (file === '') {
        document.getElementById('alertimage').innerHTML = 'Image is required.';
        return false;
    } else {
        document.getElementById('alertimage').innerHTML = '';
    }

    // Gender validation
    if (!gender) {
        document.getElementById('alertgender').innerHTML = 'Gender is required.';
        return false;
    } else {
        document.getElementById('alertgender').innerHTML = '';
    }

    // Father name validation
    if (fatherName === '') {
        document.getElementById('fathername').nextElementSibling.textContent = 'Father Name is required.';
        return false;
    } else {
        document.getElementById('fathername').nextElementSibling.textContent = '';
    }

    // Father phone validation
    if (fatherPhone === '' || !/^\d{10}$/.test(fatherPhone)) {
        document.getElementById('fphonenumber').nextElementSibling.textContent = 'Please enter a valid 10-digit Father Mobile Number.';
        return false;
    } else {
        document.getElementById('fphonenumber').nextElementSibling.textContent = '';
    }

    // Father email validation
    if (fatherEmail === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fatherEmail)) {
        document.getElementById('femailid').nextElementSibling.textContent = 'Please enter a valid Father Email Id.';
        return false;
    } else {
        document.getElementById('femailid').nextElementSibling.textContent = '';
    }

    // Mother name validation
    if (motherName === '') {
        document.getElementById('mothername').nextElementSibling.textContent = 'Mother Name is required.';
        return false;
    } else {
        document.getElementById('mothername').nextElementSibling.textContent = '';
    }

    // Mother phone validation
    if (motherPhone === '' || !/^\d{10}$/.test(motherPhone)) {
        document.getElementById('mphonenumber').nextElementSibling.textContent = 'Please enter a valid 10-digit Mother Mobile Number.';
        return false;
    } else {
        document.getElementById('mphonenumber').nextElementSibling.textContent = '';
    }

    // Mother email validation
    if (motherEmail === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(motherEmail)) {
        document.getElementById('memailid').nextElementSibling.textContent = 'Please enter a valid Mother Email Id.';
        return false;
    } else {
        document.getElementById('memailid').nextElementSibling.textContent = '';
    }

    // Primary contact validation
    if (!primaryContact) {
        document.getElementById('alertprimary').innerHTML = 'Primary contact is required.';
        return false;
    } else {
        document.getElementById('alertprimary').innerHTML = '';
    }

    return true;
}

    </script>
<script>
    function showViewButton() {
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];
        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (!validImageTypes.includes(file.type)) {
            alert('Invalid file type. Please upload an image file (jpg, jpeg, png, gif).');
            fileInput.value = ''; // Clear the input
            document.getElementById('viewButton').style.display = 'none';
            return;
        }

        if (file.size > 5000000) { // 5MB
            alert('File size exceeds 5MB. Please upload a smaller file.');
            fileInput.value = ''; // Clear the input
            document.getElementById('viewButton').style.display = 'none';
            return;
        }

        document.getElementById('viewButton').style.display = 'inline-block';
    }

    function viewImage() {
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        };
        reader.readAsDataURL(file);
    }
</script>
<script>
    function showGalleryButton() {
        const filesInput = document.getElementById('files');
        const files = filesInput.files;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        let valid = true;

        Array.from(files).forEach(file => {
            if (!validImageTypes.includes(file.type) || file.size > 5000000) { // 5MB
                valid = false;
            }
        });

        if (!valid) {
            alert('Invalid file type or file size exceeds 5MB in gallery. Please upload image files (jpg, jpeg, png, gif) below 5MB.');
            filesInput.value = ''; // Clear the input
            document.getElementById('galleryButton').style.display = 'none';
            return;
        }

        document.getElementById('galleryButton').style.display = 'inline-block';
    }

    function viewGallery() {
        const filesInput = document.getElementById('files');
        const files = filesInput.files;
        const galleryPreview = document.getElementById('galleryPreview');
        galleryPreview.innerHTML = ''; // Clear previous previews

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.createElement('img');
                img.src = reader.result;
                img.style.maxWidth = '100px';
                img.style.margin = '5px';
                galleryPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
        galleryModal.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
