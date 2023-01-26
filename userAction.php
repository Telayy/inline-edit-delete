<?php 
 
// Load and initialize database class 
require_once 'DB.class.php'; 
$db = new DB(); 
 
if(($_POST['action'] == 'edit') && !empty($_POST['id'])){ 
    // Update data 
    $userData = array( 
        'first_name' => $_POST['first_name'], 
        'last_name' => $_POST['last_name'], 
        'email' => $_POST['email'], 
        'status' => $_POST['status'] 
    ); 
    $condition = array( 
        'id' => $_POST['id'] 
    ); 
    $update = $db->update($userData, $condition); 
 
    if($update){ 
        $response = array( 
            'status' => 1, 
            'msg' => 'Member data has been updated successfully.', 
            'data' => $userData 
        ); 
    }else{ 
        $response = array( 
            'status' => 0, 
            'msg' => 'Something went wrong!' 
        ); 
    } 
     
    echo json_encode($response); 
    exit(); 
}elseif(($_POST['action'] == 'delete') && !empty($_POST['id'])){ 
    // Delete data 
    $condition = array('id' => $_POST['id']); 
    $delete = $db->delete($condition); 
 
    if($delete){ 
        $returnData = array( 
            'status' => 1, 
            'msg' => 'Member data has been deleted successfully.' 
        ); 
    }else{ 
        $returnData = array( 
            'status' => 0, 
            'msg' => 'Something went wrong!' 
        ); 
    } 
     
    echo json_encode($returnData); 
    exit(); 
} 
 
?>