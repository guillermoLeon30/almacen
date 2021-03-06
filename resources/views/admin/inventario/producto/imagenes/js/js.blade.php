<script type="text/javascript">

$('#imagenIngresar').fileinput({
  previewFileType: "image",
  allowedFileTypes: ["image"],
  minImageHeight: 520,
    maxFileSize: 1024, //1MB
    language: "es",
    browseLabel: "Escojer Imagen",
    browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
});

$('.select2').select2({
  dropdownParent: $('#modalIngresarImagen')
});

function ingresarImagenProducto() {
  $('#modalIngresarImagen').modal('show');
}

//----------------------GENERAR TABLA-------------------------
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    
    generarTabla(page);
  });

  function generarTabla(page) {
    $.ajax({
      headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
      url: '{{ url('admin/inventario/productos/imagenes') }}/{{ $producto->id }}',
      type: 'GET',
      data: {'page':page},
      dataType: 'json',
      beforeSend: function () {
        $('.box').append('<div class="overlay">'+
                            '<i class="fa fa-refresh fa-spin"></i>'+
                         '</div>');
      },
      success: function (data) {
        $('#tbodyTablaImagenes').html(data);
        $('.overlay').detach();
      }
    });
  }
//--------------------------------------------------------------

$('#formImgresarImagen').submit(function (e) {
  e.preventDefault();

  $.ajax({
    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    url: '{{ url('admin/inventario/productos/imagenes') }}',
    type: 'POST',
    data: new FormData($("#formImgresarImagen")[0]),
    dataType: 'json',
    contentType: false,
    processData: false,
    beforeSend: function () {
      $('.box').append('<div class="overlay">'+
                        '<i class="fa fa-refresh fa-spin"></i>'+
                      '</div>');

      $('#modalIngresarImagen').modal('hide');
    },
    success: function(data){
      $('.overlay').detach();
      var page = $('.pagination .active span').html();
      toastr.success('Se ingres?? la imagen correctamente.');

      generarTabla(page);
    },
    error: function (data) {
      $('.overlay').detach();

      mensaje('error', data, '#mensaje');
    }
  });
});

function eliminar(id) {
  $('#eliminarId').val(id);
  $('#modalEliminar').modal('show');
}

$('#formEliminar').submit(function (e) {
  e.preventDefault();
  var id = $('#eliminarId').val();

  $.ajax({
    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    url: '{{ url('admin/inventario/productos/imagenes') }}/' + id,
    type: 'DELETE',
    dataType: 'json',
    beforeSend: function () {
      $('#modalEliminar').modal('hide');
      $('.box').append('<div class="overlay">'+
                        '<i class="fa fa-refresh fa-spin"></i>'+
                       '</div>');
    },
    success: function () {
      $('.overlay').detach();
      var page = $('.pagination .active span').html();
      
      toastr.success('Se elimin?? la imagen correctamente.');
      generarTabla(page);
    },
    error: function (data) {
      $('.overlay').detach();
      mensaje2('error', 'Ocurrio un error con la conexi??n', '#mensaje');
    }
  });
});

function bajarNumeroOrden(idImagen) {
  $.ajax({
    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    url: '{{ url('admin/inventario/productos/imagenes') }}/' + idImagen,
    type: 'POST',
    data: {
      '_method':'PUT',
      'mover':'abajo'
    },
    dataType: 'json',
    beforeSend: function () {
      $('.box').append('<div class="overlay">'+
                        '<i class="fa fa-refresh fa-spin"></i>'+
                     '</div>');
    },
    success: function (data) {
      $('.overlay').detach();
      var page = $('.pagination .active span').html();
      
      toastr.success('Se actualiz?? el orden de la imagen correctamente.');
      generarTabla(page);
    },
    error: function (data) {
      $('.overlay').detach();
      mensaje2('error', 'Ocurrio un error con la conexi??n', '#mensaje');
    }
  });
}

function subirNumeroOrden(idImagen) {
  $.ajax({
    headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    url: '{{ url('admin/inventario/productos/imagenes') }}/' + idImagen,
    type: 'POST',
    data: {
      '_method':'PUT',
      'mover':'arriba'
    },
    dataType: 'json',
    beforeSend: function () {
      $('.box').append('<div class="overlay">'+
                        '<i class="fa fa-refresh fa-spin"></i>'+
                     '</div>');
    },
    success: function () {
      $('.overlay').detach();
      var page = $('.pagination .active span').html();
      
      toastr.success('Se actualiz?? el orden de la imagen correctamente.');
      generarTabla(page);
    },
    error: function (data) {
      $('.overlay').detach();
      mensaje2('error', 'Ocurrio un error con la conexi??n', '#mensaje');
    }
  });
}

//---------------------MENSAJE------------------------------
  function mensaje(tipo, data, lugar) {
    var tipoAlerta, icono, titulo, html, mensajes='';

    if (tipo == 'ok') {
      tipo = 'alert alert-success alert-dismissible';
      icono = 'icon fa fa-check';
      titulo = 'Exito!';
      mensajes = data.mensaje;

    }else if (tipo == 'error') {
      tipo = 'alert alert-danger alert-dismissible';
      icono = 'icon fa fa-ban';
      titulo = 'Alerta!';
      var arrayMensajes = data.responseJSON;
      mensajes = '<ul>';
      $.each(arrayMensajes, function (i, reg) {
        mensajes += '<li>'+reg+'</li>';
      });
      mensajes += '</ul>';
    }

    var html =  '<div class="'+tipo+'">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">??</button>'+
                  '<h4><i class="'+icono+'"></i> '+titulo+'</h4>'+
                  mensajes+
                '</div>';
              
    $(lugar).html(html); 
  }

  function mensaje2(tipo, mensaje, lugar) {
    var tipoAlerta, icono, titulo, html, mensajes='';

    if (tipo == 'ok') {
      tipo = 'alert alert-success alert-dismissible';
      icono = 'icon fa fa-check';
      titulo = 'Exito!';
    }else if (tipo == 'error') {
      tipo = 'alert alert-danger alert-dismissible';
      icono = 'icon fa fa-ban';
      titulo = 'Alerta!';
    }

    var html =  '<div class="'+tipo+'">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">??</button>'+
                  '<h4><i class="'+icono+'"></i> '+titulo+'</h4>'+
                  mensaje+
                '</div>';
              
    $(lugar).html(html); 
  }

</script>