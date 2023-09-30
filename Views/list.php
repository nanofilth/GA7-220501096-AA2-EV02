<?php
    // Incluir el controlador GlobalController.php
    require_once '../Controllers/GlobalController.php';
    // Incluir la sección 'head' del HTML
    include ('head.php');
    
    // Verificar si el usuario ha iniciado sesión; si no, redirigirlo a la página de inicio
    if( !isset( $_SESSION['UID'] ) )
        header("location: /");
    
    // Crear una instancia del controlador GlobalController
    $globalCtrl = new GlobalController();
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <hr>
            <div class="row">
                <div class="col-12">
                    <!-- Enlace a la página principal con un logo -->
                    <a href="/"><img src="../assets/img/logo.png" alt="Maker - IggI" class="img-thumbnail img-fluid logo-list"></a>
                </div>
                <div class="col-12 text-right">
                    <?php include('dropmenu.php'); ?>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php include('error.php'); ?>
            <div class="row">
                <div class="col-3">
                    <ul class="nav flex-column">
                        <?php 
                            // Generar opciones de navegación en función de las opciones en la variable de sesión
                            foreach( $_SESSION['OPTIONS'] as $key => $option ){
                                // Determinar si la opción actual está activa
                                $active = (isset($_REQUEST['source']) && $option['route'] == $_REQUEST['source'])?'active':'inactive';
                                echo "<li class='nav-item $active-source'>
                                <a class='nav-link' href='/Views/list.php?source=$option[route]'><i class='fa fa-sm $option[class]'></i> $option[name]</a>
                                </li>";
                            } 
                        ?>
                    </ul>
                </div>
                <?php if( isset( $_REQUEST['source'] ) ){ ?>
                    <div class="col-9 info-section">
                        <div class="loader text-center">
                            <small>Loading data...</small><br><br>
                            <div class="spinner-border text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="result table-responsive">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Título de la sección basado en la fuente seleccionada -->
                                    <h1 class="title-crud"><?php echo $_REQUEST['source']; ?></h1>
                                </div>
                                <div class="col-12 text-right">
                                    <!-- Botón para agregar nuevos elementos -->
                                    <button class="btn btn-success btn-sm add">Add <?php echo $_REQUEST['source']; ?></button>
                                </div>
                            </div><hr>
                            <table class="table table-hover table-sm">
                                <thead class="thead-dark text-center">
                                    <tr>
                                    <th scope="col">#</th>
                                    <?php
                                        // Obtener información de las columnas de la fuente seleccionada
                                        $data = $globalCtrl->buildGrid( $_REQUEST['source'] );
                                        // Generar encabezados de tabla basados en las columnas
                                        foreach( $data['columns'] as $column){
                                            echo "<th scope='col'>$column[Field]</th>";
                                        }
                                    ?>
                                    <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                            // Generar fila por registro obtenido de la fuente seleccionada, agregand acción de edición y eliminación
                                            $colspan = count($data['columns'])+2;
                                            if( count( $data['rows'] ) > 0 ){ 
                                                foreach( $data['rows'] as $key => $row){
                                                    $rdata = http_build_query($row, '', ',');
                                                    $html = "<tr><td class='text-center align-middle'><small>".($key+1)."</small></td>";
                                                    foreach($row as $k => $cell){
                                                        $cls = ($k == 'id')?'text-center':'';
                                                        $cellcls = '';
                                                        if($k == 'state'){
                                                            $cellcls = ($cell == 1)?'text-success':'text-danger';
                                                            $cell = ($cell == 1)?'Enable':'Disable';
                                                        }
                                                        $html .= "<td class='$cls $k $cellcls align-middle' data-$k='$cell'>$cell</td>";
                                                    }
                                                    $html .= "<td class='text-center'><button type='button' class='btn btn-light btn-sm edit' data-row='$rdata'><i class='fa fa-pencil'></i></button>";
                                                    $html .= "<button type='button' class='btn btn-light btn-sm delete' data-url='/Controllers/GlobalController.php?table=$_REQUEST[source]&method=dropRecord&id=$row[id]'>";
                                                    $html .= "<i class='fa fa-trash-o'></i></button></td></tr>";
                                                    echo $html;
                                                }
                                            }else{
                                                // Mensaje si no hay datos
                                                echo "<tr class='text-center without-data'><td colspan='$colspan'><small>Without data</small></td></tr>";
                                            }
                                        ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="<?php echo $colspan; ?>">
                                            <!-- Formulario para agregar o actualizar elementos -->
                                            <form action='../Controllers/GlobalController.php' method='post' class="form-crud" id="form-crud">
                                                <div class="form-row align-items-center">
                                                <?php
                                                    foreach( $data['columns'] as $column){
                                                        $disable = ($column['Field'] == 'id')?'readonly':'';
                                                        $required = ($column['Field'] == 'id')?'':'required';
                                                        $formInput = "<div class='col sec-$column[Field]'>";
                                                        if( $column['Field'] != 'state')
                                                            $formInput .= "<input type='text' class='form-control form-control-sm field-$column[Field]' placeholder='Enter $column[Field]' name='$column[Field]' $disable $required/>";
                                                        else{
                                                            $formInput .= "<select class='custom-select custom-select-sm field-$column[Field]' name='$column[Field]' required>";
                                                            $formInput .= "<option selected>Select $column[Field]</option><option value='1'>Enable</option>";
                                                            $formInput .= "<option value='0'>Disable</option></select>";
                                                        }
                                                        echo $formInput."</div>";
                                                    }
                                                ?>
                                                <input type="hidden" name="method" class="inputMethod">
                                                <input type="hidden" name="table" value="<?php echo $_REQUEST['source']; ?>">
                                                <div class="col-auto">
                                                    <!-- Botón para guardar cambios -->
                                                    <button type="submit" class="btn btn-success btn-sm save"><i class="fa fa-save"></i></button>
                                                    </div>
                                                <div class="col-auto">
                                                    <!-- Botón para restablecer el formulario -->
                                                    <button type="button" class="btn btn-light btn-sm reset"><i class="fa fa-times"></i></button>
                                                </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="col-9 text-center info-section">
                        <small>Select any option to get started</small><br><br>
                        <img src="../assets/img/select.png" alt="Maker - IggI" width="50%">
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php include ('foot.php'); ?>