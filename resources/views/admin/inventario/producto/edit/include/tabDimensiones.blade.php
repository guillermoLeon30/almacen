<div class="box box-primary">
  <div class="box-header">
    <h2 class="box-title">Dimensiones</h2>

    <div class="box-tools">
      <div class="input-group" style="width: 300px;">
        <input class="form-control" type="text" name="dimension" id="txtDimension">

        <div class="input-group-btn">
          <button id="btnAgregarDimensionTabla" type="button" class="btn btn-primary">Agregar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="box-body table-resposive no-padding">
    <table class="table table-hover">
      <thead>
        <tr>
          <th></th>
          <th>Dimensiones</th>
          <th>Opciones</th>
        </tr>
      </thead>

      <tbody id="trTablaDimension">
        @php
          $i=0;
        @endphp

        @foreach($producto->listaDescripciones() as $descripcion)
          <tr id="filaDimension{{$i}}">
            <td>
              <button onclick="moverArribaFilaDimension({{$i}})" type="button" class="btn btn-warning">
                <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
              </button>

              <button onclick="moverAbajoFilaDimension({{$i}})" type="button" class="btn btn-warning">
                <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
              </button>
            </td>
            <td>
              <input type="hidden" class="dimensiones" value="{{$descripcion->dimension}}">
              {{$descripcion->dimension}}
            </td>
          </tr>
          @php
          $i++;
        @endphp
        @endforeach
        @for ($i = 0; $i < count($producto->listaDescripciones()); $i++)
          
        @endfor
      </tbody>
    </table>
  </div>
</div>