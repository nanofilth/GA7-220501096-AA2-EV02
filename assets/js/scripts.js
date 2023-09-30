// Función para inicializar tooltips y popovers
function initializeJS() {
    // Inicializar tooltips
    jQuery('.tooltips').tooltip();
    // Inicializar popovers
    jQuery('.popovers').popover();
}

// Función que se ejecuta cuando el documento está listo
jQuery(document).ready(function(){

    // Llamar a la función de inicialización
    initializeJS();
    
    // Comprobar si la URL contiene 'source' y cargar contenido si es así
    if( window.location.search.includes('source') )
        loadContent();

    // Asociar una función a la acción de hacer clic en elementos con clase 'add'
    $('.add').click( () => newRecord() );
    
    // Asociar una función a la acción de hacer clic en elementos con clase 'reset'
    $('.reset').click( () => formReset() );
});

// Asociar una función a la acción de hacer clic en elementos con clase 'edit'
$(document).on("click", ".edit", function(){
    const row = this.dataset.row;
    updateRecord( row );
});

// Asociar una función a la acción de hacer clic en elementos con clase 'delete'
$(document).on("click", ".delete", function(){
    window.location = this.dataset.url;
});

// Función para cargar contenido
const loadContent = () => {
    $('.result').hide();
    $('.loader').show();
    setTimeout( () => {
        $('.result').show();
        $('.loader').hide();
    }, 1000);
}

// Función para agregar un nuevo registro
const newRecord = () => {
    formReset();
    $('.inputMethod').attr('value', 'newRecord');
    $('.form-crud').show();
    $('input:visible:first').focus();
}

// Función para actualizar un registro
const updateRecord = ( row ) => {
    const data = row.split( ',' );
    $.each(data, (i, cell) => {
        var parts = cell.split( '=' );
        var val = parts[1].replace('+', ' ');
        if( parts[0] !== 'state' )
            $('.field-'+parts[0]).attr( 'value', val);
        else
            $('.field-'+parts[0]+' option[value="'+val+'"]').attr("selected", "selected");
    });
    formReset();
    $('.sec-id').show();
    $('.inputMethod').attr('value', 'updateRecord');
    $('.form-crud').show();
    $('input:visible:eq(1)').focus();
}

// Obtener el formulario para restablecerlo
const formToReset = document.getElementById('form-crud');

// Función para restablecer el formulario
const formReset = () => {
    $('form')[0].reset();
    $('.sec-id').hide();
    $('.form-crud').hide();
}