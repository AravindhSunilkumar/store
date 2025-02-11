function validate() {
  document.getElementById('blockScreen').style.display = 'block';
  document.querySelector('.dblur').style.filter = 'blur(8px)';
  console.log('fname');
  // Retrieve values of the form fields
  var fname = document.getElementById('fname').value;
  console.log(fname);
  var lname = document.getElementById('lname').value;
  var age = document.getElementById('age').value;
  var class_id = document.getElementById('class_id').value;
  var dob = document.getElementById('dob').value;
  var gender = document.getElementById('gender').value;
  var fathername = document.getElementById('fathername').value;
  var fphonenumber = document.getElementById('fphonenumber').value;
  var femailid = document.getElementById('femailid').value;
  var mothername = document.getElementById('mothername').value;
  var mphonenumber = document.getElementById('mphonenumber').value;
  var memailid = document.getElementById('memailid').value;
  var primary = document.querySelector('input[name="primary"]:checked');
  
  // Validate First Name
  if (!fname) {
    console.log(fname);
    alert('Please enter a valid first name without special characters.');
    return false;
  }

  // Validate Last Name
  if (!lname ) {
    alert('Please enter a valid last name without special characters.');
    return false;
  }

  // Validate Age
  if (!age || isNaN(age) || age <= 0) {
    alert('Please enter a valid age.');
    return false;
  }

  // Validate Class
  if (!class_id) {
    alert('Please select a class.');
    return false;
  }

  // Validate Date of Birth
  var datePattern = /^\d{4}-\d{2}-\d{2}$/;
  if (!dob || !datePattern.test(dob)) {
    alert('Please enter a valid date in the format YYYY-MM-DD.');
    return false;
  }

  // Validate Gender
  if (!gender) {
    alert('Please select a gender.');
    return false;
  }

  // Validate Father Name
  if (!fathername || /[^a-zA-Z ]/.test(fathername)) {
    alert('Please enter a valid father name.');
    return false;
  }

  // Validate Father Mobile Number
  var phonePattern = /^[0-9]{10}$/;
  if (!fphonenumber || !phonePattern.test(fphonenumber)) {
    alert('Please enter a valid 10-digit mobile number for father.');
    return false;
  }

  // Validate Father Email ID
  var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  if (!femailid || !emailPattern.test(femailid)) {
    alert('Please enter a valid email ID for father.');
    return false;
  }

  // Validate Mother Name
  if (!mothername || /[^a-zA-Z ]/.test(mothername)) {
    alert('Please enter a valid mother name.');
    return false;
  }

  // Validate Mother Mobile Number
  if (!mphonenumber || !phonePattern.test(mphonenumber)) {
    alert('Please enter a valid 10-digit mobile number for mother.');
    return false;
  }

  // Validate Mother Email ID
  if (!memailid || !emailPattern.test(memailid)) {
    alert('Please enter a valid email ID for mother.');
    return false;
  }

  // Validate Primary Contact Selection
  if (!primary) {
    alert('Please select a primary contact.');
    return false;
  }

  return true;
}

function confirmDelete(id) {
document.getElementById('blockScreen').style.display = 'block';
document.querySelector('.dblur').style.filter = 'blur(8px)';
if (confirm('Are you sure you want to delete this student?')) {
var xhr = new XMLHttpRequest();
xhr.open('GET', 'view.php?action=delete&id=' + id, true);
xhr.onreadystatechange = function() {
if (xhr.readyState == 4 && xhr.status == 200) {
alert('Student deleted successfully');
document.getElementById('blockScreen').style.display = 'none';
document.querySelector('.dblur').style.filter = 'none';
window.location.href = 'view.php';
}
};
xhr.send();
}else{
document.getElementById('blockScreen').style.display = 'none';
document.querySelector('.dblur').style.filter = 'none';
}
}




$(document).ready(function() {
  $('.input-switch').click(function() {
    var id = $(this).attr('id');  // Get the ID of the checkbox
    var status = $(this).prop('checked');  // Check if it is checked or not
    status = (status == true) ? 'active' : 'inactive';  // Set the status accordingly

    
      document.getElementById('blockScreen').style.display = 'block';
      document.querySelector('.dblur').style.filter = 'blur(8px)';
      $.ajax({  
        type: 'GET',  
        url: 'view.php', 
        data: { action: 'updatestatus', id: id, status: status },
        success: function(response) {
          console.log(status);
          console.log(id);
          console.log('updatestatus');
          // alert('Status updated successfully');
          $('#blockScreen').css('display', 'none');
          document.querySelector('.dblur').style.filter = 'none';
          
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





      function changePriority(direction, priority, id) {
        document.getElementById('blockScreen').style.display = 'block';
        
        document.querySelector('.dblur').style.filter = 'blur(8px)';
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'view.php?action=' + direction + '&p=' + priority + '&id=' + id, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.href = 'view.php';
          }
        };
        xhr.send();
      }

      document.getElementById('files').addEventListener('change', function() {
        document.getElementById('blockScreen').style.display = 'block';
        document.querySelector('.dblur').style.filter = 'blur(8px)';
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
          

          if (xhr.status == 200) {
            $('#Gallery').load(document.URL +  ' #Gallery');
           

            // document.getElementById('text').innerHTML = 'image uploaded successfully';
            // document.getElementById('alert').style.display = 'block';
            alert('Files uploaded successfully');
            document.getElementById('blockScreen').style.display = 'none';
            
            document.getElementById('loader').style.display = 'none';
            document.querySelector('.dblur').style.filter = 'none';
            
            // window.location.href = 'view.php?action=update&id=' + id;
          } else {
            alert('File upload failed. Please try again.');
          }
        }
        
        };
        xhr.send(formData);
    });
  
  function deletePhoto(image_id) {
    document.getElementById('blockScreen').style.display = 'block';
    document.querySelector('.dblur').style.filter = 'blur(8px)';
    var ids = image_id.split(',');
    var image_id = ids[0];
    var student_id = ids[1];
    console.log('ok');
    console.log(image_id);
    console.log(student_id);
    

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'view.php?image_id=' + image_id, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        $('#Gallery').load(document.URL +  ' #Gallery');
        alert('Photo deleted successfully');
        document.getElementById('blockScreen').style.display = 'none';
        document.getElementById('loader').style.display = 'none';
        document.querySelector('.dblur').style.filter = 'none';

        // window.location.href = 'view.php?action=update&id=' + student_id;
      }
    };
    xhr.send();
  }

//Gallery View




// document.getElementById('student_name').addEventListener('change',function(){
//     var name = document.getElementById('student_name');
//     var xhr = new X
// });
