<script>

//---------------------------------SELECT PRODUCTOS-------------------------------------
$('#selectProducto').select2({
  dropdownParent: $('#modalIngresarItems'),
  ajax: {
    id: function (e) {
      return producto.id;
    },
    url: '{{ url('admin/inventario/productos/lista') }}',
    type: 'GET',
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        filtro: params.term,
        page: params.page
      };
    },
    processResults: function (data, params) {
      params.page = params.page || 1;

      return {
        results: data.productos,
        pagination: {
              more: (params.page * 20) < data.total_count
            }
      };
    }
  },
  cache: true,
  templateResult: formatProducto,
  templateSelection: formatRepoProducto
});

function formatProducto (producto) {

  if (!producto.id) { return producto.text; }
  var $producto = $(
      '<span>'+
      '<table>'+
        '<tbody>'+
          '<tr>'+
            '<td rowspan="3">'+
              '<img class="img-responsive img-thumbnail" src="'+producto.imagen+'" height="100px" width="50px"/>'+
            '</td>'+
            '<td>'+
              producto.categoria +
            '</td>'+
          '</tr>'+

          '<tr>'+
            '<td>'+
              '<strong>Código: </strong> '+ producto.codigo +
            '</td>'+
          '</tr>'+

          '<tr>'+
            '<td>'+
              '<strong>Márca: </strong> '+ producto.marca +
            '</td>'+
          '</tr>'+
        '</thead>'+
      '</tbody>'+
    '</span>'
  );
  return $producto;
}

function formatRepoProducto(producto) {
  if (!producto.id) { return producto.text; }
  var $producto = $(
        '<span>'+
        producto.categoria+
        '<b>Codigo: </b>' + producto.codigo +
        '<b> Marca: </b>' + producto.marca +
        '<input id="ImagenProducto" type="hidden" value="'+producto.imagen+'">'+
        '<input id="CodigoProducto" type="hidden" value="'+producto.codigo+'">'+
        '<input id="MarcaProducto" type="hidden" value="'+producto.marca+'">'+
        '<input id="CategoriaProducto" type="hidden" value="'+producto.categoria+'">'+
      '</span>'
  );

  return $producto;
}
//---------------------------------MODAL ITEM----------------------------------------
$('#selectDimension').select2({
  dropdownParent: $('#modalIngresarItems')
});

$('#selectProducto').on('select2:select', function (evt) {
  var idProducto = $('#selectProducto').val();

  $.ajax({
    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    url: '{{ url('admin/inventario/productos/producto') }}/' + idProducto,
    type: 'GET',
    dataType: 'json',
    beforeSend: function () {
      $('#selectDimension').prop("disabled", true);
      $('#selectColor').prop("disabled", true);
    },
    success: function (data) {
      $('#selectDimension').prop("disabled", false);
      $('#selectColor').prop("disabled", false);
      imprimirDimensiones(data.dimensiones);
      imprimirColores(data.colores);
    },
    error: function (data) {
      $('#selectDimension').prop("disabled", false);
      $('#selectColor').prop("disabled", false);

      mensaje2('error', 'Se produjo un error en la conexión.', '#mensaje');
    }
  });
});

function imprimirDimensiones(dimensiones) {
  var opciones = '';

  $.each(dimensiones, function (i, dimension) {
    opciones += '<option value="'+dimension.id+'">'+dimension.dimension+'</option>';
  });

  $('#selectDimension').html(opciones);
}

function imprimirColores(colores) {
  var opciones = '';

  $.each(colores, function (i, color) {
    opciones += '<option value="'+color.id+'">'+color.color+'</option>';
  });

  $('#selectColor').html(opciones);
}
//--------------------------------------------------------------------------------
var contItems = 0;

$('#btnGuardarItems').click(function () {
  if (esValidoIngresarItem()) {
    var producto_id = $('#selectProducto').val();
    var dimension_id = $('#selectDimension').val();
    var color_id = $('#selectColor').val();
    var precio = $('#precioProducto').val();
    var cantProducto = $('#cantProducto').val();
    var imagen = $('#ImagenProducto').val();
    var categoria = $('#CategoriaProducto').val();
    var marca = $('#MarcaProducto').val();
    var dimension = $('#selectDimension option:selected').html();
    var color = $('#selectColor option:selected').html();
    var precio = $('#precioProducto').val();
    var cantProducto = $('#cantProducto').val();
    var subtotalCompra = precio * cantProducto;

    var fila = '<tr id="filaItem'+contItems+'">'+
                  '<td>'+
                    '<button class="btn btn-danger" onclick="eliminarItem('+contItems+')">'+
                      '<i class="glyphicon glyphicon-trash"></i>'+
                    '</button>'+
                  '</td>'+
                  '<td><img class="img-responsive img-thumbnail" width="50px" src="'+imagen+'"></td>'+
                  '<td>'+categoria+'</td>'+
                  '<td>'+marca+'</td>'+
                  '<td>'+dimension+'</td>'+
                  '<td>'+color+'</td>'+
                  '<td>$'+precio+'</td>'+
                  '<td>'+cantProducto+'</td>'+
                  '<td>$'+subtotalCompra.toFixed(2)+'</td>'+
                  '<input class="filaSubtotal" type="hidden" value="'+subtotalCompra+'">'+
                  '<input class="filas" type="hidden" value="'+contItems+'">'+
                  '<input class="fila'+contItems+'" type="hidden" name="producto_id" value="'+producto_id+'">'+
                  '<input class="fila'+contItems+'" type="hidden" name="dimension_id" value="'+dimension_id+'">'+
                  '<input class="fila'+contItems+'" type="hidden" name="color_id" value="'+color_id+'">'+
                  '<input class="fila'+contItems+'" type="hidden" name="precio" value="'+precio+'">'+
                  '<input class="fila'+contItems+'" type="hidden" name="cantidad" value="'+cantProducto+'">'+
                '</tr>';
    $('#tablaItems').append(fila);
    $('#modalIngresarItems').modal('hide');
    contItems++;
    totalCompra();
  }
});

function eliminarItem(item) {
  $('#filaItem'+item).remove();
  totalCompra();
}

function esValidoIngresarItem() {
  var producto_id = $('#selectProducto').val();
  var dimension_id = $('#selectDimension').val();
  var color_id = $('#selectColor').val();
  var precio = $('#precioProducto').val();
  var cantProducto = $('#cantProducto').val();
  var arrItems = items();

  if (!producto_id || !dimension_id || !color_id || precio <= 0 || cantProducto <= 0) return false;

  for (var i = 0; i < arrItems.length; i++) {
    if (arrItems[i].producto_id == producto_id && arrItems[i].dimension_id == dimension_id && items[i].color_id == color_id) return false;
  }

  return true;
}

function totalCompra() {
  var subtotal = 0;
  var iva = 0;

  $('.filaSubtotal').each(function (i, subtotalTab) {
    subtotal = subtotal + parseFloat(subtotalTab.value);
  });

  iva = subtotal * '{{ $iva }}' / 100;
  var total = subtotal + iva;

  $('#subTotalCompra').val(subtotal.toFixed(2));
  $('#iva').val(iva.toFixed(2));
  $('#totalCompra').val(total.toFixed(2));
  $('#total').val(total.toFixed(2));
}

function items() {
  var items = [];
  var item = {};

  $('.filas').each(function (i, fila) {
    $('.fila'+fila.value).each(function (i, nodo) {
      item[nodo.name] = nodo.value;
    });
    items.push(item);
    item = {};
  });

  return items;
}

</script>