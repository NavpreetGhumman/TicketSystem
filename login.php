<?php
require_once 'header.php';
?>

<?php

$error_msg = "";
//check if file exist
if(file_exists('users.xml')) {
    //load xml file
    $xml = simplexml_load_file("users.xml");
    if(isset($_POST['login'])){

        $username = $_POST['username'];
        $password = md5($_POST['password']);
        session_start();
        foreach ($xml->children() as $p) {
            if ($password == $p->password && $username == $p->userName) {

                $_SESSION['username'] = $username;
                $_SESSION['loggedUserId'] = $p->attributes()['userId']. "";
                $_SESSION['loggedUserRole'] = $p->attributes()['userType']. "";
                $_SESSION['loggedUserEmail'] = $p->email. "";

                if($_SESSION['loggedUserRole'] == "client") {
                    header('Location: index.php');
                } else
                    {
                    header('Location: staffView.php');
                }
            }
            else{
                $error_msg = "Please enter valid username or password";
            }
        }
    }
}
?>


<header class="header">
    <div class="page-container">
        <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <p class="site-logo"><i class="fas fa-laptop"></i><span>Service Now</span></p>
                </a>
            </div>
        </nav>
    </div>
</header>
<body>

<div class="page-container">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="images/undraw_remotely_2j6y.svg" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h3>Sign In</h3>
                        </div>
                        <form action="#" method="post">
                            <div class="form-group first">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" value="<?= isset($username) ? $username : ''; ?>">
                            </div>
                            <div class="form-group last mb-4">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="form-error"><?= isset($error_msg)? $error_msg: ''; ?></div>
                            <div class="d-flex mb-5 align-items-center">
                                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                    <input type="checkbox" checked="checked"/>
                                    <div class="control__indicator"></div>
                                </label>
                                <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                            </div>
                            <input type="submit" value="Log In" name="login" class="btn btn-block btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



