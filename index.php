<?php
    include ('Views/head.php');
    session_start();
    if( isset( $_SESSION['UID'] ) )
        header("location: /Views/list.php");
?>
<div class="row">
    <div class="col-6 offset-md-3 login-logo text-center">
        <hr>
        <a href="/Views/login-form.php"><img src="../assets/img/logo.png" alt="Maker - IggI" class="img-thumbnail img-fluid"></a>
        <hr>
    </div>
    <div class="col-4 offset-md-4">
        <form action='Controllers/LoginController.php' method='post'>
            <?php include('Views/error.php'); ?>
            <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username" required>
                <small id="emailHelp" class="form-text text-muted">Use admin for username & password.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name='password' required>
                <input type="hidden" name="method" value="access">
            </div>
            <hr>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>
</div>
<?php
include ('Views/foot.php');