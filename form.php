<?php
require_once 'connection.php';
?>
<?php




if($_SERVER['REQUEST_METHOD'] == 'POST'  && isset($_POST['submit'])){
    // print_r($_REQUEST);die;
    
    if ((!empty($_POST['name'])) && (!empty($_POST['age'])) && (!empty($_POST['gender']) && (!empty($_POST['class'])) )) {
        
        
        $target_dir = "students_images/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check !== false) {
            echo "File is an image - " . $check["mime"] . "";
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
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      
        $name = $_POST['name'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $class = $_POST['class'];
        $student_id = $_POST['student_id'];
        $sql2 = "SELECT * FROM student_details ORDER BY priority DESC LIMIT 1";
        $result2 = $conn->query($sql2);
        
        if ($result2 && $result2->num_rows > 0) {
            $data = $result2->fetch_assoc();
            $priority = $data['priority'] + 1;
            echo "p ok";
            
        } else {
            $priority = 1; // Default priority if no records found
        }

        echo "it ok";
            $sql = "insert into student_details(name,class_id,age,gender,priority,photo,reg_id) values ('$name','$class','$age','$gender','$priority','$target_file','$student_id')";
            if($conn->query($sql) == TRUE){
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
        <a class="nav-link active btn btn-warning" aria-current="page" href="view.php">Main Page</a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link btn btn-warning" href="form.php">Add Student</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " aria-disabled="true"></a>
    </li>
    </ul>
    <!-- nav  end-->

<div class="container ">
    <center><h3>Register</h3></center>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
       <?php
       
       $sql3 = "SELECT * FROM student_details ORDER BY id DESC LIMIT 1";
        $result3 = $conn->query($sql3);
        $row3 = $result3->fetch_assoc();
        $reg_id = str_pad($row3['register_id'] + 1, 4, '0', STR_PAD_LEFT);
?>
    <div class="mb-3">
            <label for="student_id" class="form-label">Registration Id</label>
            <input type="text" name="student_id" class="form-control" id="student_id" value = "<?php echo $reg_id;?>" required>
        </div>    
    <div class="mb-3">
            <label for="name" class="form-label"><span class="text-danger">*</span>Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="class"><span class="text-danger">*</span>Class</label>
            <div class="dropdown">
            <select class="form-select" name="class" aria-label="Default select example">
                <?php
                $sql = "SELECT * FROM class_details WHERE status = 'active'";
                $classes = $conn->query($sql);
                
                // echo "<option value='' disabled selected>Select a class</option>";
                
                while($row = $classes->fetch_assoc()){
                    echo "<option value=".$row['id'].">".$row['class']."</option>";
                }
                ?>
            </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="age" class="form-label"><span class="text-danger">*</span>Age</label>
            <input type="number" name="age" class="form-control" id="age" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label"><span class="text-danger">*</span>Image</label>
            <input type="file" name="file" class="form-control" id="file" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label"><span class="text-danger">*</span>Gender</label><br>
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
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="Register">
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
