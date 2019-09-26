<?php

if(isset($_POST['sign-in'])){
   require 'server.php';

   $mailid = $_POST['email'];
   $pass = $_POST['password'];

    if(empty($mailid) || empty($pass)){
        header("Location: ../index.php?error=emptyfields");
        exit();
   
    }else{
        $sql="SELECT * FROM login WHERE username=? OR email=?;";
        $statement=mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($statement,$sql)){
            header("Location: ../index.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($statement,"ss",$mailid,$mailid);
            mysqli_stmt_execute($statement);
            $result=mysqli_stmt_get_result($statement);
            
            if($row =mysqli_fetch_assoc($result)){
                $passCheck=password_verify($pass,$row['pass']);
                
                if($passCheck == false){
                    header("Location: ../index.php?error=wrongpassword");
                    exit();
                }

                elseif($passCheck == true){
                    session_start();
                    $_SESSION['username'] = $row['username'];
                    header("Location: ../index.php?login=success");
                    exit();
                }

                else{
                    header("Location: ../index.php?error=wrongpassword");
                    exit();
                }
            }

            else{
                header("Location: ../index.php?error=nouser");
                exit();
            }

        }
    }
}

else{
    header("Location: ../index.php");
    exit();
}