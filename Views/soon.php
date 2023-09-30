<?php
    include ('head.php');
    session_start();
    if( !isset( $_SESSION['UID'] ) )
        header("location: /");
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <hr>
            <div class="row">
                <div class="col-12">
                    <a href="/"><img src="../assets/img/logo.png" alt="Maker - IggI" class="img-thumbnail img-fluid logo-list"></a>
                </div>
                <div class="col-12 text-right">
                    <?php include('dropmenu.php'); ?>
                </div>
            </div>
            <hr>
        </div>
        <div class="col-12 text-center">
            <a href="/"><img src="../assets/img/under.png" alt="Maker - IggI" width="50%"></a>
            <hr>
            <blockquote class="blockquote">
                <p class="mb-0">Section under construction, coming soon.</p>
                <footer class="blockquote-footer">Develop team<cite title="Source Title"> IggI</cite></footer>
            </blockquote>
            <a href="/"><i class="fa fa-chevron-left"></i> Return to dashboard</a>
        </div>
    </div>
</div>
<?php include ('foot.php');