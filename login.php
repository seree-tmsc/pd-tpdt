<?php    
    include_once('include/chk_Session.php');
    $message = "";

    if($user_email == "")
    {
        try
        {
            if(isset($_POST["btn_Login"]))
            {
                if(empty($_POST["param_email"]) || empty($_POST["param_password"]))
                {
                    $message = '<label> * Required Filed</label>';
                }
                else
                {
                    include_once('include/db_Conn.php');
                    //echo $encrypt = base64_encode($string);
                    //echo $decrypt = base64_decode($encrypt);

                    $strSql = "SELECT * ";
                    $strSql .= "FROM TPDT_MAS_Users_ID ";
                    $strSql .= "WHERE user_email = '" . $_POST["param_email"] . "' ";                    
                    $strSql .= "AND user_pwd = '" . base64_encode($_POST["param_password"]) . "' ";
                    //echo $strSql . "<br>";
                                        
                    $statement = $conn->prepare($strSql);
                    $statement->execute();                    
                    $nRecCount = $statement->rowCount();
                    //echo $nRecCount . "<br>" ;
                    
                    if($nRecCount == 1)
                    {                     
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        /*
                        echo $_POST["param_password"];

                        print_r($result);
                        echo $result[0]['emp_code'];
                        echo $result[0]['user_email'];
                        echo $result[0]['user_pwd'];
                        echo $result[0]['user_type'];
                        */
                        
                        $_SESSION["ses_email"] = $result[0]["user_email"];
                        $_SESSION["ses_user_type"] = $result[0]["user_type"];
                        $_SESSION["ses_emp_code"] = $result[0]["emp_code"];
                        $_SESSION["ses_sbu"] = $result[0]["user_myteam"];

                        if (file_exists('images/' . $result[0]["emp_code"] . '.jpg'))
                        {
                            $_SESSION["ses_user_picture"] = 'images/' . $result[0]["emp_code"] . '.jpg';
                        }
                        else
                        {
                            $_SESSION["ses_user_picture"] = 'images/user_32x32.jpg';
                        }
                        header("location:pMain.php");
                        
                    }
                    else
                    {
                        $message = '<label> e-Mail or Password not correct </label>';
                    }
                }
            }
        }
        catch(PDOException $error)
        {
            $message = $error->getMessage();
        }
    }
    else
    {
        echo "<script> 
                alert('You are login already ... The system will redirect to main page'); 
                window.location.href='pMain.php'; 
            </script>";
    } 
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <title>TMSC TPDT System V.1.0</title>        
        
        <link rel="stylesheet" href="../vendors/bootstrap-3.3.7-dist/css/bootstrap.min.css">
        <script src="../vendors/jquery-3.2.1/jquery-3.2.1.min.js"></script>
        <script src="../vendors/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

        <link rel="icon" href="images/tmsc-logo-64x32.png" type="image/x-icon" />
        <link rel="shortcut icon" href="images/tmsc-logo-64x32.png" type="image/x-icon" />

        <style>
            .login-bg{
                background-image: url('images/background-01.jpg');
                background-repeat: no-repeat; 
                background-size: cover; 
                opacity:0.8;
            }
        </style>
    </head>
    
    <body class='login-bg'>
        <br><br><br>
        <!-- Begin Container -->
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading" align="center" >
                            <br>
                            <img src="images/tmsc-new-logo-1.png">                            
                            <br>
                            <br>
                        </div>
                        
                        <div class="panel-body" style='background-color: LemonChiffon; color:red;'>
                            <form method="post">
                                <div class="form-group">
                                    <label>e-Mail : *</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="email" name="param_email" value="" placeholder="Input e-Mail" autofocus class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Password : *</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>                                        
                                        <input type="password" name="param_password" value="" placeholder="Input Password" class="form-control">
                                    </div>
                                    
                                </div>
                                <br>
                                <div align="right">
                                    <input type="submit" name="btn_Login" value="Login" class="btn btn-success">
                                </div>
                            </form>
                        </div>

                        <div class="panel-footer" align="right" style='color:red;'>
                            <h5>Please input e-Mail and Password</h5>
                            <?php
                            if(isset($message))
                            {
                                echo '<label class="text-danger">' . $message . '</label>';
                            }
                            ?>          
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <!-- End Container -->
    </body>
</html>