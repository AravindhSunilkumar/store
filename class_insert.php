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

//status update==========================status update==========================status update==========================status update==========================status update==========================

if (isset($_GET['action']) && $_GET['action'] == 'updatestatus') {
  $id = (int)$_GET['id'];
  $status = $_GET['status'];
  echo "<h2>$status</h2>";
  $status = ($status == 'active') ? 'active' : 'inactive';
  $sql = "UPDATE class_details SET status = '$status' WHERE id = '$id'";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Status updated successfully');window.location.href = 'class_insert.php';</script>";
  } else {
    echo "Error updating status: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class</title>
  <style>
    
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
  </style>
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

     <!-- loader -->
  <div id="blockScreen" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0, 0, 0, 0.22); z-index:9999;">
    <div id="loader"   style=" position: absolute; left: 50%; top: 35%;" class="spinner-grow text-info" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>           
  </div>
  <!-- loader end -->
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

  <!-- class view start -->
  <div class="container mt-4">
    <table class="table table-primary dblur" id="">
      <thead>
        <tr>
          <th>Class Name</th>
          <th class="text-center">Age From</th>
          <th class="text-center">Age To</th>
          <th class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $sql = "select * from class_details order by class_priority  asc";
         $result = $conn->query($sql);
         if($result->num_rows){
          while($row = $result->fetch_assoc()){
            $class_name = $row['class'];
            $age_from = $row['age_from'];
            $age_to = $row['age_to'];
            $status = $row['status'];
            $checked = ($row['status'] == 'active') ? 'checked' : '';
            $i = $row['id'];
            
            ?>
            <tr>
              <td class=""><?php echo $class_name ;?></td>
              <td class="text-center"><?php echo $age_from;?></td>
              <td class="text-center"><?php echo $age_to;?></td>
              <td class="text-center"><input class='input-switch' type='checkbox' id='<?php echo $i;?>' <?php echo $checked;?>>
                  <label class='label-switch' for='<?php echo $i;?>'></label>
                  <span class='info-text'></span></td>
            </tr>
            
            <?php
          }
         }
        ?>
      </tbody>

    </table>
  </div>
  <!-- class view end -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.input-switch').forEach(function(element) {
        element.addEventListener('click', function() {
          var id = this.id;  // Get the ID of the checkbox
          var status = this.checked;  // Check if it is checked or not
          status = (status == true) ? 'active' : 'inactive';  // Set the status accordingly

          document.getElementById('blockScreen').style.display = 'block';
          document.querySelector('.dblur').style.filter = 'blur(8px)';
          
          fetch(`class_insert.php?action=updatestatus&id=${id}&status=${status}`)
            .then(response => response.text())
            .then(data => {
              console.log(status);
              console.log(id);
              console.log('updatestatus');
              document.getElementById('blockScreen').style.display = 'none';
              document.querySelector('.dblur').style.filter = 'none';
            })
            .catch(error => console.error('Error:', error));
        });
      });
    });
  </script>

  <script>
    document.getElementById('class_name').focus();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>
