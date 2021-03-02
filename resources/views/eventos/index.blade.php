@extends('layouts.app')

@section('scripts')
 <link href="{{ asset('fullcalendar/core/main.css') }}" rel="stylesheet">
 <link href="{{ asset('fullcalendar/daygrid/main.css') }}" rel="stylesheet">
 <link href="{{ asset('fullcalendar/list/main.css') }}" rel="stylesheet">
 <link href="{{ asset('fullcalendar/timeGrid/main.css') }}" rel="stylesheet">

 <script src="{{ asset('fullcalendar/core/main.js') }}"></script>
 <script src="{{ asset('fullcalendar/interaction/main.js') }}"></script>
 <script src="{{ asset('fullcalendar/daygrid/main.js') }}"></script>
 <script src="{{ asset('fullcalendar/timeGrid/main.js') }}"></script>
 <script src="{{ asset('fullcalendar/list/main.js') }}"></script>

<script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {

          defaultDate:new Date(2021,02,01),
           plugins: [ 'dayGrid', 'timeGrid', 'list','interaction' ],
            // defaultView:'timeGridWeek'

            header:{
              left:'prev,next today Miboton',
              center:'title',
              right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },

            customButtons:{
              Miboton:{
                  text:"Boton",
                  click:function (){
                      // alert('Hola perro');
                      $('#exampleModal').modal('toggle');
                  }
              }
            },

            dateClick:function (info) {

              limpiarFormulario();

              $('#txtFecha').val(info.dateStr);

              $("#btnAgregar").prop("disabled",false);
              $("#btnModificar").prop("disabled",true);
              $("#btnBorrar").prop("disabled",true);

              $('#exampleModal').modal('toggle');
            },

            eventClick:function (info) {

              $("#btnAgregar").prop("disabled",true);
              $("#btnModificar").prop("disabled",false);
              $("#btnBorrar").prop("disabled",false);

              // datos base
              console.log(info);
              console.log(info.event.title);
              console.log(info.event.start);
              console.log(info.event.end);

              console.log(info.event.textColor);
              console.log(info.event.backgroundColor);

              //propiedades extendidas
              console.log(info.event.extendedProps.description);

              $('#txtID').val(info.event.id);
              $('#txtTitulo').val(info.event.title);

                mes = (info.event.start.getMonth()+1)
                dia = (info.event.start.getDay())
                anio = (info.event.start.getFullYear())

                mes = (mes<10)?"0"+mes:mes;
                dia = (dia<10)?"0"+dia:dia;

                minutos = info.event.start.getMinutes();
                hora = info.event.start.getHours();

                minutos = (minutos<10)?"0"+minutos:minutos;
                hora = (hora<10)?"0"+hora:hora;


                horario =(hora+":"+minutos);

              $('#txtFecha').val(anio+"-"+mes+"-"+dia);
              $('#txtHora').val(horario);
              $('#txtColor').val(info.event.backgroundColor);

              $('#txtDescription').val(info.event.extendedProps.description);

              $('#exampleModal').modal();
            },

            // events:[
            //     {
            //         title: "Mi evento 1",
            //         start:"2021-03-01 12:30:00",
            //         descripcion:"Description del evento 1"
            //
            //     },{
            //         title: "Mi evento 2",
            //         start:"2021-03-02 12:30:00",
            //         end:"2021-03-03 12:30:00",
            //         color:"#ffccaa",
            //         textcolor:"000000",
            //         descripcion:"Description del evento 2"
            //     }
            // ]
            events:"{{ url('/eventos/show')  }}"

        });
        calendar.setOption('locale','Es');

        calendar.render();

        $('#btnAgregar').click(function(){
            ObjEvento= recolectarDatosGUI('POST');
            EnviarInformacion('', ObjEvento);
        });

        $('#btnBorrar').click(function(){
            ObjEvento= recolectarDatosGUI('DELETE');
            EnviarInformacion('/destroy/'+$('#txtID').val(), ObjEvento);
        });

        $('#btnModificar').click(function(){
            ObjEvento= recolectarDatosGUI('PATCH');
            EnviarInformacion('/update/'+$('#txtID').val(), ObjEvento);
        });

        function recolectarDatosGUI(method){
            nuevoEvento={
                id:$('#txtID').val(),
                title:$('#txtTitulo').val(),
                description:$('#txtDescription').val(),
                color:$('#txtColor').val(),
                textColor:'#ffffff',
                start:$('#txtFecha').val()+$('#txtHora').val(),
                end:$('#txtFecha').val()+$('#txtHora').val(),

                '_token':$("meta[name='csrf-token']").attr("content"),
                '_method':method
            }
            console.log(nuevoEvento)
            return (nuevoEvento);

        }

        function EnviarInformacion(accion,ObjEvento){
            $.ajax(
                    {
                       type:"POST",
                        {{--url:"{{ url('/') }}"+accion,--}}
                         url: "{{route('eventos.store')}}"+accion,
                        data:ObjEvento,
                        success:function (msg){
                              console.log(msg);
                              $('#exampleModal').modal('toggle');
                              calendar.refetchEvents();

                             },
                        error:function(){alert("hay un error");}
                    }
                );
        }

        function limpiarFormulario(){
              $('#txtID').val("");
              $('#txtTitulo').val("");
              $('#txtFecha').val("");
              $('#txtHora').val("");
              $('#txtColor').val("");
              $('#txtDescription').val("");
        }

      });

    </script>

@endsection


@section('content')
        <div class="row">
            <div class="col"></div>
                <div class="col-9">
                    <div id='calendar'></div>
                </div>
            <div class="col"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                  <div class="form-row">

                      <input class="form-control" type="hidden" name="txtID" id="txtID">
                      <input class="form-control" type="text" name="txtFecha" id="txtFecha">

                      <div class="form-group col-8">
                          <label for="">Titulo</label>
                          <input class="form-control" type="text" name="txtTitulo" id="txtTitulo">
                      </div>

                      <div class="form-group col-4">
                          <label for="">Hora</label>
                          <input class="form-control" type="time" name="txtHora" id="txtHora" min="07:00" max="19:00" step="600">
                      </div>


                      <label for="">Description</label>
                      <textarea class="form-control" name="txtDescription" id="txtDescription" cols="30" rows="3"></textarea>
                      <br>

                      <label for="">Color</label>
                      <input class="form-control" type="color" name="txtColor" id="txtColor">

                  </div>

              </div>

              <div class="modal-footer">
                  <button class="btn btn-success" id="btnAgregar">Agregar</button>
                  <button class="btn btn-warning" id="btnModificar">Modificar</button>
                  <button class="btn btn-danger" id="btnBorrar">Borrar</button>
                  <button class="btn btn-secondary" id="btnCancelar" data-dismiss="modal">Cancelar</button>

              </div>
            </div>
          </div>
        </div>

@endsection
