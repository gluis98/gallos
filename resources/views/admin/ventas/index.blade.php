@extends('layouts.app')

@section('content')
    
    <section class="container-fluid bg-white p-4">
        <h2>Ventas realizadas</h2>
        <hr>
        {{-- <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-gallo">
            <span class="material-symbols-outlined float-start me-2">
                add_circle
            </span>
            Nuevo gallo
        </button> --}}
        {{-- <button class="btn btn-dark ">
            <span class="material-symbols-outlined float-start me-2">
                picture_as_pdf
            </span>
            PDF
        </button> --}}
        <form id="form-search" class="my-2">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="dato" id="dato" placeholder="Buscar...">
            </div>
        </form>
        <h4>Total Ingresos: $ <span id="total-ventas" class="fw-bold"></span></h4>
        <div class="responsive-table">
            <table class="table table-bordered" id="table-ventas">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Gallo vendido</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        getVentas();




            $(document).on('submit', '#form-search', function(e){
                e.preventDefault();
                let form =  new FormData(this);
                let timerInterval;
                Swal.fire({
                title: "¡Procesando!",
                html: "La ventana se cerrará automáticamente en <b></b> milliseconds.",
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
                });
                fetch('api/ventas/search', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => response.json())
                .then(data => {
                    let template = "", 
                        template_img = "",
                        template_video = "",
                        template_file = "",
                        btn = "",
                        size = "",
                        btnLike = "";
                        total_ventas = 0;
                        moment.locale('es');
                        data.data.forEach(e => {
                            template += `
                                <tr>
                                    <td>${e.id}</td>
                                    <td>${moment(e.created_at).format('l')}</td>
                                    <td>${e.gallo.id}</td>
                                    <td>${e.nombre_cliente}</td>
                                    <td>${(e.telefono != null) ? e.telefono : 'SIN TELÉFONO'}</td>
                                    <td>${e.monto}</td>
                                    <td>${(e.observaciones != null) ? e.observaciones : ''}</td>
                                    <td>
                                        <a href="#" class="btn text-danger my-2  btn-sm float-end delete" data-id="${e.id}" data-bs-toggle="tooltip" title="Eliminar venta">
                                                <span class="material-symbols-outlined float-start mx-2">
                                                    delete_forever
                                                </span>
                                            </a>
                                    </td>
                                </tr>
                            `;
                            total_ventas += e.monto;
                            
                        });
                        $('#total-ventas').html(total_ventas)
                        $('#table-ventas > tbody').html(template)

                        $(()=>{
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl)
                            })
                        })
                    
                 });
            })

            $(document).on('click', '.edit', function(e){
                e.preventDefault();
                let timerInterval;
                id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Procesando...',
                    html: 'La ventana se cerrará en <b></b> milisegundos.',
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })

                fetch('api/gallos/'+id, {
                    method: 'GET',
                }).then(response => response.json()
                ).then(function(res){   
                    
                    $('#modal-gallo').modal('show')


                    $('input[name=padre_id]').val((res.data.gallos_hijos.length > 0) ? res.data.gallos_hijos[0].padre.placa : "");
                    $('input[name=madre_id]').val((res.data.gallos_hijos.length > 0 && res.data.gallos_hijos[0].gallina != null) ? res.data.gallos_hijos[0].gallina.placa : "");
                    $('input[name=placa]').val(res.data.placa);
                    $('input[name=marca_nacimiento]').val(res.data.marca_nacimiento);
                    $('input[name=marca_federacion]').val(res.data.marca_federacion);
                    $('input[name=color]').val(res.data.color);
                    $('input[name=cresta]').val(res.data.cresta);
                    $('input[name=fecha_nacimiento]').val(res.data.fecha_nacimiento);
                    $('input[name=luna]').val(res.data.luna);
                    $('input[name=peleas]').val(res.data.peleas);
                    $('[name=observaciones]').val(res.data.observaciones);
                    
                    $('[name=estatus]').prepend(`<option selected>${res.data.estatus}</option>`);
                    $('#form-gallo').attr('id', 'form-gallo-edit');
                

                }).catch(function(err){
                    Swal.fire({
                        icon: 'error',
                        text: 'err'
                    })
                    console.log(err)
                })
            })

            $(document).on('submit', '#form-gallo-edit',function(e){
                e.preventDefault();
                let form =  new FormData(this);
                    form.append('_method', 'PUT');
                let timerInterval;
                Swal.fire({
                title: "¡Editando!",
                html: "La ventana se cerrará automáticamente en <b></b> milliseconds.",
                timer: 500000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
                });
                console.log(id)
                fetch(`api/gallos/${id}`, {
                    method: 'POST',
                    body: form
                }).then(response => response.text())
                .then(data => {
                    console.log(data)
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.msj,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    getGallos();
                    $('#form-gallo-edit').trigger('reset')
                    $('#form-gallo-edit').attr('id', 'form-gallo');
                    $('#modal-gallo').modal('hide')
                 });
            })

            $(document).on('click', '.delete', function(){
                let id = $(this).attr('data-id');
                Swal.fire({
                title: "¿Estás seguro(a) de querer eliminar este gallo?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar."
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch('api/ventas/'+id, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(res => {
                        Swal.fire({
                            title: "Eliminada",
                            text: res.msj,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        getVentas();
                    })
                    
                }
                });
            })

           
            async function getVentas(){
                const response = await fetch('api/ventas');
                const data = await response.json();
                let template = "", 
                    template_img = "",
                    template_video = "",
                    template_file = "",
                    btn = "",
                    size = "",
                    btnLike = "",
                    total_ventas = 0;
                moment.locale('es');
                data.data.forEach(e => {
                    template += `
                        <tr>
                            <td>${e.id}</td>
                            <td>${moment(e.created_at).format('l')}</td>
                            <td>${e.gallo.id}</td>
                            <td>${e.nombre_cliente}</td>
                            <td>${(e.telefono != null) ? e.telefono : 'SIN TELÉFONO'}</td>
                            <td>${e.monto}</td>
                            <td>${(e.observaciones != null) ? e.observaciones : ''}</td>
                            <td>
                                <a href="#" class="btn text-danger my-2  btn-sm float-end delete" data-id="${e.id}" data-bs-toggle="tooltip" title="Eliminar venta">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            delete_forever
                                        </span>
                                    </a>
                            </td>
                        </tr>
                    `;
                    total_ventas += e.monto;
                    
                });
                $('#total-ventas').html(total_ventas)
                $('#table-ventas > tbody').html(template)

                $(()=>{
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                })
            }

            async function getGallosPlacas(){
                const response = await fetch('api/gallos');
                const data = await response.json();
                let template = "";
                moment.locale('es');
                data.data.forEach(e => {
                    template += `
                        <option value="${e.placa}">${e.placa}</option>
                    `;
                });
                $('#padre_id').html(template)
            }

            async function getGallinasPlacas(){
                const response = await fetch('api/gallinas');
                const data = await response.json();
                let template = "";
                moment.locale('es');
                console.log(data)
                data.data.forEach(e => {
                    template += `
                        <option value="${e.placa}">${e.placa}</option>
                    `;
                });
                $('#madre_id').html(template)
            }

        })
</script>
@endsection
