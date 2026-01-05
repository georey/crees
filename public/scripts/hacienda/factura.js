
$(document).ready(function () {
  console.log('Script cargado');
  console.log('Contenedor items-container existe:', $('#items-container').length > 0);
  
  $('#agregar-item').on('click', function () {
    console.log('Botón + clickeado');
    
    // Obtener los valores
    var descripcion = $('#descripcion').val();
    var cantidad = $('#cantidad').val();
    var unidad = $('#unidad_medida').val();
    var precio = $('#precio_unitario').val();
    var tipo = $('#tipo').val();
    var descuento = $('#descuento').val();
    var no_suj = $('#no_suj').val();
    var exenta = $('#exenta').val();

    console.log('Valores capturados:', {descripcion, cantidad, unidad, precio, tipo, descuento, no_suj, exenta});

    // Validar
    if (!descripcion || !cantidad || !unidad || !precio || !tipo || !descuento || !no_suj || !exenta) {
      alert('Completa todos los campos antes de agregar.');
      console.log('Validación falló');
      return;
    }

    console.log('Validación OK, creando item...');

    // Crear nueva fila
    var nuevoItem = `
      <div class="row">
       <div class="form-group col-md-1">
          <input type="text" name="tipo[]" class="form-control" value="${tipo}" readonly autocomplete="off">
        </div>
         <div class="form-group col-md-1">
          <input type="text" name="unidad_medida[]" class="form-control" value="${unidad}" readonly autocomplete="off">
        </div>
        <div class="form-group col-md-3">
          <input type="text" name="descripcion[]" class="form-control" value="${descripcion}" readonly autocomplete="off">
        </div>
        <div class="form-group col-md-2">
          <input type="text" name="cantidad[]" class="form-control" value="${cantidad}" readonly autocomplete="off">
        </div>
        <div class="form-group col-md-2">
          <input type="text" name="precio_unitario[]" class="form-control" value="${precio}" readonly autocomplete="off">
        </div>
        <div class="form-group col-md-1">
          <input type="text" name="descuento[]" class="form-control" value="${descuento}" readonly autocomplete="off">
        </div>
        <div class="form-group col-md-1">
          <input type="text" name="no_suj[]" class="form-control" value="${no_suj}" readonly autocomplete="off">
        </div>
       
        <div class="form-group col-md-1">
          <div class="input-group">
            <input type="text" name="exenta[]" class="form-control" value="${exenta}" readonly autocomplete="off">
           
          </div>
        </div>
         <div class="input-group-append">
              <button type="button" class="btn btn-danger btn-sm eliminar-item">&times;</button>
            </div>
      </div>
    `;

    // Agregar a la lista
    console.log('Agregando item al contenedor...');
    $('#items-container').append(nuevoItem);
    console.log('Item agregado. Total items ahora:', $('#items-container .row').length);

    // Limpiar inputs y forzar actualización
    $('#descripcion').val('').trigger('change');
    $('#cantidad').val('1').trigger('change');
    $('#unidad_medida').val('99').trigger('change');
    $('#precio_unitario').val('0').trigger('change');
    $('#tipo').val('2').trigger('change');
    $('#descuento').val('0').trigger('change');
    $('#no_suj').val('0').trigger('change');
    $('#exenta').val('0').trigger('change');
    
    // Enfocar en descripción para el siguiente item
    $('#descripcion').focus();
  });

  // Eliminar ítems agregados
  $('#items-container').on('click', '.eliminar-item', function () {
    $(this).closest('.row').remove();
  });
});

