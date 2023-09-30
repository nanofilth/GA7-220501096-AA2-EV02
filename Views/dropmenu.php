<div class="btn-group">
    <!-- Botón de dropdown que muestra el nombre de usuario de la sesión -->
    <button type="button" class="btn btn-sm dropdown-toggle btn-drop" data-toggle="dropdown" aria-expanded="false">
        <small><?php echo 'Hello, '. $_SESSION['USERNAME'].PHP_EOL; ?></small>
    </button>
    <!-- Menú desplegable con opciones para el usuario -->
    <div class="dropdown-menu">
        <!-- Enlace a la página de inicio del panel de control -->
        <small><a class="dropdown-item" href="/">Dashboard</a></small>
        <!-- Enlace a la página de perfil (a implementar) -->
        <small><a class="dropdown-item" href="/Views/soon.php">Profile</a></small>
        <!-- Enlace a la página de configuración (a implementar) -->
        <small><a class="dropdown-item" href="/Views/soon.php">Settings</a></small>
        <!-- Enlace a la página de estadísticas (a implementar) -->
        <small><a class="dropdown-item" href="/Views/soon.php">Statistics</a></small>
        <!-- Separador en el menú -->
        <div class="dropdown-divider"></div>
        <!-- Enlace para cerrar sesión del usuario -->
        <small><a class="dropdown-item" href="/Controllers/LoginController.php?method=logout"><i class="fa fa-sign-out"></i> Logout</a></small>
    </div>
</div>
