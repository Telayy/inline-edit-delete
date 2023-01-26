<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Datatable</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Template Main CSS File -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>
  <main id="main" class="main">


  <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="userData">
    <?php 
        // Load and initialize database class 
        require_once 'DB.class.php'; 
        $db = new DB(); 
 
        // Get members data from database 
        $members = $db->getRows(); 
 
        if(!empty($members)){ 
            foreach($members as $row){ 
        ?>
            <tr id="<?php echo $row['id']; ?>">
                <td><?php echo $row['id']; ?></td>
                <td>
                    <span class="editSpan first_name"><?php echo $row['first_name']; ?></span>
                    <input class="form-control editInput first_name" type="text" name="first_name" value="<?php echo $row['first_name']; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan last_name"><?php echo $row['last_name']; ?></span>
                    <input class="form-control editInput last_name" type="text" name="last_name" value="<?php echo $row['last_name']; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan email"><?php echo $row['email']; ?></span>
                    <input class="form-control editInput email" type="text" name="email" value="<?php echo $row['email']; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan status"><?php echo $row['status']; ?></span>
                    <select class="form-control editInput status" name="status" style="display: none;">
                        <option value="Active" <?php echo $row['status'] == 'Active'?'selected':''; ?>>Active</option>
                        <option value="Inactive" <?php echo $row['status'] == 'Inactive'?'selected':''; ?>>Inactive</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-primary editBtn">Edit</button>
                    <button type="button" class="btn btn-danger deleteBtn">Delete</button>
                    
                    <button type="button" class="btn btn-success saveBtn" style="display: none;">Save</button>
                    <button type="button" class="btn btn-danger confirmBtn" style="display: none;">Confirm</button>
                    <button type="button" class="btn btn-secondary cancelBtn" style="display: none;">Cancel</button>
                </td>
            </tr>
        <?php 
            } 
        }else{ 
            echo '<tr><td colspan="6">No record(s) found...</td></tr>'; 
        } 
    ?>
    </tbody>
</table>

  </main><!-- End #main -->



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Template Main JS File --> 
  <script src="js/jquery.min.js"></script>
  


  <script>
$(document).ready(function(){
    $('.editBtn').on('click',function(){
        //hide edit span
        $(this).closest("tr").find(".editSpan").hide();

        //show edit input
        $(this).closest("tr").find(".editInput").show();

        //hide edit button
        $(this).closest("tr").find(".editBtn").hide();

        //hide delete button
        $(this).closest("tr").find(".deleteBtn").hide();
        
        //show save button
        $(this).closest("tr").find(".saveBtn").show();

        //show cancel button
        $(this).closest("tr").find(".cancelBtn").show();
        
    });
    
    $('.saveBtn').on('click',function(){
        $('#userData').css('opacity', '.5');

        var trObj = $(this).closest("tr");
        var ID = $(this).closest("tr").attr('id');
        var inputData = $(this).closest("tr").find(".editInput").serialize();
        $.ajax({
            type:'POST',
            url:'userAction.php',
            dataType: "json",
            data:'action=edit&id='+ID+'&'+inputData,
            success:function(response){
                if(response.status == 1){
                    trObj.find(".editSpan.first_name").text(response.data.first_name);
                    trObj.find(".editSpan.last_name").text(response.data.last_name);
                    trObj.find(".editSpan.email").text(response.data.email);
                    trObj.find(".editSpan.status").text(response.data.status);
                    
                    trObj.find(".editInput.first_name").val(response.data.first_name);
                    trObj.find(".editInput.last_name").val(response.data.last_name);
                    trObj.find(".editInput.email").val(response.data.email);
                    trObj.find(".editInput.status").val(response.data.status);
                    
                    trObj.find(".editInput").hide();
                    trObj.find(".editSpan").show();
                    trObj.find(".saveBtn").hide();
                    trObj.find(".cancelBtn").hide();
                    trObj.find(".editBtn").show();
                    trObj.find(".deleteBtn").show();
                }else{
                    alert(response.msg);
                }
                $('#userData').css('opacity', '');
            }
        });
    });

    $('.cancelBtn').on('click',function(){
        //hide & show buttons
        $(this).closest("tr").find(".saveBtn").hide();
        $(this).closest("tr").find(".cancelBtn").hide();
        $(this).closest("tr").find(".confirmBtn").hide();
        $(this).closest("tr").find(".editBtn").show();
        $(this).closest("tr").find(".deleteBtn").show();

        //hide input and show values
        $(this).closest("tr").find(".editInput").hide();
        $(this).closest("tr").find(".editSpan").show();
    });
    
    $('.deleteBtn').on('click',function(){
        //hide edit & delete button
        $(this).closest("tr").find(".editBtn").hide();
        $(this).closest("tr").find(".deleteBtn").hide();
        
        //show confirm & cancel button
        $(this).closest("tr").find(".confirmBtn").show();
        $(this).closest("tr").find(".cancelBtn").show();
    });
    
    $('.confirmBtn').on('click',function(){
        $('#userData').css('opacity', '.5');

        var trObj = $(this).closest("tr");
        var ID = $(this).closest("tr").attr('id');
        $.ajax({
            type:'POST',
            url:'userAction.php',
            dataType: "json",
            data:'action=delete&id='+ID,
            success:function(response){
                if(response.status == 1){
                    trObj.remove();
                }else{
                    trObj.find(".confirmBtn").hide();
                    trObj.find(".cancelBtn").hide();
                    trObj.find(".editBtn").show();
                    trObj.find(".deleteBtn").show();
                    alert(response.msg);
                }
                $('#userData').css('opacity', '');
            }
        });
    });
});
</script>




</body>



</html>