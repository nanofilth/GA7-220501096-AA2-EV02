<!-- Comprobar si existe la variable de sesión 'ERRMSG' y si no está vacía -->
<?php if( isset( $_SESSION['ERRMSG'] ) && !empty($_SESSION['ERRMSG'])){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php
            // Mostrar el contenido de la variable de sesión 'ERRMSG' como mensaje de error
            echo $_SESSION['ERRMSG'];
        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<!-- Cierre bloque IF -->
<?php } ?>