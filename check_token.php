<?php 

if (isset($_SESSION['username'])) { 


    $result = mysqli_query($koneksi, "SELECT token FROM tbl_token_user where username='".$_SESSION['username']."'");
 
    if (mysqli_num_rows($result) > 0) {
      
        $row = mysqli_fetch_array($result); 
        $token = $row['token']; 
        

        if($_SESSION['token'] != $token){
          
            session_destroy();
            header('Location: index.php');
        }
    }
      
}