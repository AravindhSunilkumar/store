<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'demo';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
if ($conn->connect_error) {
    die("connection failed :" . $conn->connect_error);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajax</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" id="name">
        <input type="text" name="email" id="email">
        
        <input type="submit" value="submit" id="submit" onclick ="data()">
    </form>
    <input type="file" name="file[]" id="file" multiple = "multiple" >
    <div id="result">
        <?php
        $sql = "select * from images";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<img src='uploads/" . $row['image'] . "' width='100px' height='100px'><span style='margin-right: 10px;'></span>";
        }
        ?>
    </div>
    <script>
        document.getElementById('file').addEventListener('change', function() {
            var formData = new FormData();
            var files = document.getElementById('file').files;
            
            for (var i = 0; i < files.length; i++) {
            formData.append('file[]', files[i]);
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'demo.php', true);
            xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert('Files uploaded successfully');
                window.location.reload();
            }
            
            };
            xhr.send(formData);
        });
    </script>
    <script>
        

        function data(){
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var xhr =  new XMLHttpRequest();
            xhr.open('POST', 'demo.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function(){
                if(xhr.readyState == 4 && xhr.status == 200){
                    alert('save successfully');
                    exit();
                }
            }
            xhr.send('name=' + name + '&email=' + email);
        }
    </script>
    <?php
    if(isset($_POST['name']) && isset($_POST['email'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $sql = "insert into tbl_user (Name, Email) values ('$name', '$email')";
        if(mysqli_query($conn, $sql)){
            echo "data inserted successfully";
        }else{
            echo "something went wrong";
        }
    }

    if (isset($_FILES['file'])) {
        $files = $_FILES['file'];
        $uploadDirectory = 'uploads/';
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = basename($files['name'][$i]);
            $targetFilePath = $uploadDirectory . $fileName;
            if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                $sql = "insert into images (image) values ('$fileName')";
                mysqli_query($conn, $sql);
                echo "File " . $fileName . " uploaded successfully.<br>";
            } else {
                echo "Error uploading file " . $fileName . ".<br>";
            }
        }
    }
    ?>
</body>
</html>