@extends('layouts.app')

@section('content')
    <!-- Modal gallo -->
<div class="modal fade" id="modal-gallo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar gallina</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-gallo">
            <div class="modal-body">
                <div class="alert alert-light">
                    <p>Los campos con <span class="text-danger">*</span> son obligatorios</p>
                </div>
                @csrf
                <div class="form-group mb-2 row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label for="padre_id">Padre </label>
                        <select class="form-control " name="padre_id" id="padre_id">

                        </select>

                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label for="madre_id">Madre </label>
                        <select class="form-control" name="madre_id" id="madre_id">

                        </select>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label for="placa">Placa *</label>
                    <input type="text" class="form-control" name="placa" id="placa" required>
                </div>

                <div class="form-group mb-2">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre">
                </div>

                <div class="form-group mb-2">
                    <label for="marca_nacimiento">Marca de nacimiento *</label>
                    <input type="text" class="form-control" name="marca_nacimiento" id="marca_nacimiento" required>
                </div>

                {{-- <div class="form-group mb-2">
                    <label for="marca_federacion">Marca de federacion *</label>
                    <input type="text" class="form-control" name="marca_federacion" id="marca_federacion" required>
                </div> --}}

                <div class="form-group mb-2">
                    <label for="color">Color *</label>
                    <select class="form-control" name="color" id="color" required>
                        <option value="Zambo">Zambo</option>
                        <option value="Melao">Melao</option>
                        <option value="Giro">Giro</option>
                        <option value="Marañon">Marañon</option>
                        <option value="Calica">Calica</option>
                        <option value="Pinto">Pinto</option>
                        <option value="Jabao">Jabao</option>
                        <option value="Camagüey">Camagüey</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="color_alternativo">Color alternativo *</label>
                    <input type="text" class="form-control" name="color_alternativo" id="color_alternativo">
                </div>

                <div class="form-group mb-2">
                    <label for="cresta">Cresta *</label>
                    <select class="form-control" name="cresta" id="cresta" required>
                        <option value="Lisa">Lisa</option>
                        <option value="Roseta">Roseta</option>
                        <option value="Pava">Pava</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="fecha_nacimiento">Fecha de nacimiento *</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </div>

                <div class="form-group mb-2">
                    <label for="luna">Luna *</label>
                    <select class="form-control" name="luna" id="luna" required>
                        <option value="Nueva">Nueva</option>
                        <option value="Creciente cóncava">Creciente</option>
                        <option value="Cuarto creciente">Cuarto creciente</option>
                        <option value="Creciente gibosa">Creciente gibosa</option>
                        <option value="Llena">Llena</option>
                        <option value="Menguante gibosa">Menguante</option>
                        <option value="Cuarto menguante">Cuarto menguante</option>
                        <option value="Menguante cóncava">Menguante cóncava</option>
                    </select>
                </div>



                <div class="form-group mb-2">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label for="imagen">Fotografia(s) *</label>
                    <input type="file" multiple class="form-control" name="imagen[]" id="imagen">
                </div>

                <div class="form-group mb-2">
                    <label for="estatus">Estatus *</label>
                    <select class="form-control" name="estatus" id="estatus">
                        <option>Activa</option>
                        <option>Inactiva</option>
                        <!-- <option>Vendido</option> -->
                        <option>Fallecida</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger" id="btn-save">Guardar</button>
            </div>
        </form>  
      </div>
    </div>
  </div>

      <!-- Modal gallo -->
<div class="modal fade" id="modal-pedigree" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Pedigree</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row" id="pedigree-section">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

   <!-- Modal venta -->
<div class="modal fade" id="modal-venta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Vender gallina</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-venta">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <h4>Placa del Gallo: <span id="placa-venta"></span></h4>
                    <input type="hidden" name="gallo_id">
                </div>
                <hr>
                <div class="form-group mb-2 row">
                    <div class="col-md-6">
                        <label for="nombre_cliente">Nombre del cliente</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente">
                    </div>
                    <div class="col-md-6">
                        <label for="telefono">Telefono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono">
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label for="monto">Monto</label>
                    <input type="text" class="form-control" name="monto" id="monto">
                </div>
                <div class="form-group mb-2">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Vender</button>
            </div>
        </form>
      </div>
    </div>
  </div>

    <section class="container-fluid bg-white p-4">
        <h2>Gallinas</h2>
        <hr>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-gallo">
            <span class="material-symbols-outlined float-start me-2">
                add_circle
            </span>
            Nueva gallina
        </button>
        <a href="{{ route('report.all.gallinas') }}" class="btn btn-dark " target="_blank">
            <span class="material-symbols-outlined float-start me-2">
                picture_as_pdf
            </span>
            PDF
        </a>
        <form id="form-search" class="my-2">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="dato" id="dato" placeholder="Buscar...">
            </div>
        </form>

        <div id="gallos-section" class="row"></div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        getGallos();
        getGallosPlacas();
        getGallinasPlacas();

        $(document).on('click', '.sell', function(){
            id = $(this).attr('data-id');
            placa = $(this).attr('data-placa');
            $('#placa-venta').html(placa)
            $('[name=gallo_id]').val(id)
            $('#modal-venta').modal('show')
        })

        $(document).on('submit', '#form-gallo', function(e){
                e.preventDefault();
                let form =  new FormData(this);
                let timerInterval;
                Swal.fire({
                title: "¡Procesando!",
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
                fetch('api/gallinas', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.msj,

                        showClass: {
                            popup: `
                            animate__animated
                            animate__fadeInUp
                            animate__faster
                            `
                        },
                        hideClass: {
                            popup: `
                            animate__animated
                            animate__fadeOutDown
                            animate__faster
                            `
                        }
                        });
                        getGallos();
                        getGallosPlacas();
                        getGallinasPlacas();
                    $('#form-gallo').trigger('reset')
                    $('#modal-gallo').modal('hide')
                 });
            })

            $(document).on('submit', '#form-venta', function(e){
                e.preventDefault();
                let form =  new FormData(this);
                let timerInterval;
                Swal.fire({
                title: "¡Procesando!",
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
                fetch('api/ventas', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.msj,

                        showClass: {
                            popup: `
                            animate__animated
                            animate__fadeInUp
                            animate__faster
                            `
                        },
                        hideClass: {
                            popup: `
                            animate__animated
                            animate__fadeOutDown
                            animate__faster
                            `
                        }
                        });
                        getGallos();
                    $('#form-venta').trigger('reset')
                    $('#modal-venta').modal('hide')
                 });
            })

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
                fetch('api/gallinas/search', {
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
                    moment.locale('es');
                    data.data.forEach(e => {
                        console.log(e)
                        if(e.gallinas_imagenes.length > 0){
                            // e.gallos_imagenes.forEach(f => {
                            //     if(e.gallos_imagenes.length == 1){
                            //         size = "w-100"
                            //     }else if(e.gallos_imagenes.length = 2){
                            //         size = "w-50"
                            //     }else{
                            //         size = "w-25"
                            //     }

                            //     if(f.extention == 'png' || f.extention == 'jpg') {
                            //             template_img += `
                            //             <img src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size} float-left">
                            //             `;
                            //     }

                            //     if(f.extention == 'mp4') {
                            //         template_video += `
                            //         <video src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size}" controls/>
                            //         `;
                            //     }

                            //     if(f.extention != 'mp4' && f.extention != 'png' && f.extention != 'jpg') {
                            //         template_file += `
                            //         <a href="files/gallos/${e.placa}/${f.imagen}" alt="" class="btn btn-outline-primary ${size}">
                            //             <span class="material-symbols-outlined">
                            //                 download
                            //             </span> 
                            //             Descargar ${f.route}                                    
                            //         </a>
                            //         `;
                            //     }
                            // });
                            template_img = `<img src="files/gallinas/${e.id}/${e.gallinas_imagenes[0].imagen}" class="card-img-top" alt="...">`
                        }else{
                            template_img = `<img src="img/avatar-2.png" alt="" class="w-100">`;
                        }

                        if(e.estatus != "Vendido"){
                            console.log(e.estatus)
                            btn = `<a href="#" class="btn btn-dark my-2  btn-sm float-end sell" data-id="${e.id}" data-placa="${e.placa}" data-bs-toggle="tooltip" title="Vender gallo">
                                            <span class="material-symbols-outlined float-start">
                                                sell
                                            </span> 
                                        </a>`;
                        }
                    

                        template += `
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-4">
                            <div class="card mt-4 mx-auto shadow">
                                ${template_img}
                                <div class="card-body border-top">                                
                                    <h5 class="card-title text-muted">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            loyalty
                                        </span> 
                                        Placa: <i class="float-end text-black">${e.placa}</i>
                                    </h5>
                                    <h5 class="card-title text-muted">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            barefoot
                                        </span> 
                                        Marca: <i class="float-end text-black">${e.marca_nacimiento}</i>
                                    </h5>
                                    <h5 class="card-title text-muted">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            favorite
                                        </span> 
                                        Estado: <i class="float-end text-black">${e.estatus}</i>
                                    </h5>
                                    <hr>
                                    <div class="container-fluid">
                                        ${btn}
                                       <!-- <a href="#" class="btn text-danger my-2  btn-sm float-end pedigree" data-id="${e.id}" data-bs-toggle="tooltip" title="Pedigree">
                                            <span class="material-symbols-outlined float-start mx-2">
                                                account_tree
                                            </span>
                                        </a>
                                        <a href="report/show/${e.id}" class="btn text-muted my-2  btn-sm float-end" data-id="${e.id}" data-bs-toggle="tooltip" title="Imprimir ficha">
                                            <span class="material-symbols-outlined float-start mx-2">
                                                picture_as_pdf
                                            </span>
                                        </a> -->
                                        <a href="#" class="btn text-muted my-2  btn-sm float-end edit" data-id="${e.id}" data-bs-toggle="tooltip" title="Editar gallo">
                                            <span class="material-symbols-outlined float-start mx-2">
                                                edit
                                            </span>
                                        </a>
                                        <a href="#" class="btn text-danger my-2  btn-sm float-end delete" data-id="${e.id}" data-bs-toggle="tooltip" title="Eliminar gallo">
                                            <span class="material-symbols-outlined float-start mx-2">
                                                delete_forever
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        template_file = "";
                        template_img = "";
                        template_video = "";
                        btn="";
                    });
                    $('#gallos-section').html(template)

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

                fetch('api/gallinas/'+id, {
                    method: 'GET',
                }).then(response => response.json()
                ).then(function(res){   
                    console.log(res)
                    $('#modal-gallo').modal('show')


                    $('#padre_id option').each(function() {
                        if($(this).val() != ""){
                            let padre = (res.data.gallos_hijos.length > 0 && res.data.gallos_hijos[0].padre != null) ? res.data.gallos_hijos[0].padre.id : "";
                               if($(this).val() == padre){
                                   $(this).prop('selected', true);
                               }
                        }
                    });

                    $('#madre_id option').each(function() {
                        if($(this).val() != ""){
                            let madre = (res.data.gallos_hijos.length > 0 && res.data.gallos_hijos[0].madre != null) ? res.data.gallos_hijos[0].madre.id : "";
                            if($(this).val() == madre){
                                $(this).prop('selected', true);
                            }
                        }
                    });

                    $('#cresta option').each(function() {
                        if($(this).val() == res.data.cresta){
                            $(this).prop('selected', true);
                        }
                    });

                    $('#color option').each(function() {
                        if($(this).val() == res.data.color){
                            $(this).prop('selected', true);
                        }
                    });

                    $('#luna option').each(function() {
                        if($(this).val() == res.data.luna){
                            $(this).prop('selected', true);
                        }
                    });

                    $('#estatus option').each(function() {
                        if($(this).val() == res.data.estatus){
                            $(this).prop('selected', true);
                        }
                    });

                    $('input[name=placa]').val(res.data.placa);
                    $('input[name=nombre]').val(res.data.nombre);
                    $('input[name=marca_nacimiento]').val(res.data.marca_nacimiento);
                    $('input[name=marca_federacion]').val(res.data.marca_federacion);
                    $('input[name=color]').val(res.data.color);
                    $('input[name=color_alternativo]').val(res.data.color_alternativo);
                    $('input[name=cresta]').val(res.data.cresta);
                    $('input[name=fecha_nacimiento]').val(res.data.fecha_nacimiento);
                    $('input[name=luna]').val(res.data.luna);
                    $('input[name=peleas]').val(res.data.peleas);
                    $('[name=observaciones]').val(res.data.observaciones);

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
                fetch(`api/gallinas/${id}`, {
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
                    getGallosPlacas();
                    getGallinasPlacas();
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
                    fetch('api/gallinas/'+id, {
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
                        getGallos();
                        getGallosPlacas();
                        getGallinasPlacas();
                    })
                    
                }
                });
            })

            // Pedigree
            $(document).on('click', '.pedigree', function(e){
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

                fetch('api/gallinas/'+id, {
                    method: 'GET',
                }).then(response => response.json()
                ).then(function(res){   
                    console.log(res)
                    let template = "", 
                        template_img = "";
                        if(res.data.gallos_hijos.length > 0){
                        if(res.data.gallos_hijos[0].padre != null){
                            if(res.data.gallos_hijos[0].padre.gallos_imagenes.length > 0){
                                template_img = `<img src="files/gallos/${res.data.gallos_hijos[0].padre.id}/${res.data.gallos_hijos[0].padre.gallos_imagenes[0].imagen}" class="img-fluid rounded-start h-100" alt="...">`
                            }else{
                                template_img = `<img src="img/avatar.png" alt="" class="img-fluid rounded-start h-100">`;
                            }                          
                            template = `
                                    <div class="col-md-12">
                                        <div class="card mb-3" style="max-width: 540px;">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    ${template_img}
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="alert bg-black text-white card-title">
                                                            <span class="material-symbols-outlined float-start mx-2 text-warning">
                                                                star_half
                                                            </span> 
                                                            Padre
                                                        </h5>
                                                        <hr>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                loyalty
                                                            </span> 
                                                            Placa: <i class="float-end text-black">${res.data.gallos_hijos[0].padre.placa}</i>
                                                        </h5>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                barefoot
                                                            </span> 
                                                            Marca: <i class="float-end text-black">${res.data.gallos_hijos[0].padre.marca_nacimiento}</i>
                                                        </h5>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                favorite
                                                            </span> 
                                                            Estado: <i class="float-end text-black">${res.data.gallos_hijos[0].padre.estatus}</i>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                            `;
                        }

                        if(res.data.gallos_hijos[0].madre != null){
                            if(res.data.gallos_hijos[0].madre.gallinas_imagenes.length > 0){
                                template_img = `<img src="files/gallinas/${res.data.gallos_hijos[0].madre.id}/${res.data.gallos_hijos[0].madre.gallinas_imagenes[0].imagen}" class="img-fluid rounded-start h-100" alt="...">`
                            }else{
                                template_img = `<img src="img/avatar-2.png" alt="" class="img-fluid rounded-start vh-100">`;
                            }                          
                            template += `
                                    <div class="col-md-12">
                                        <div class="card mb-3" style="max-width: 540px;">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    ${template_img}
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="alert bg-black text-white card-title">
                                                            <span class="material-symbols-outlined float-start mx-2 text-warning">
                                                                star
                                                            </span> 
                                                            Madre
                                                        </h5>
                                                        <hr>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                loyalty
                                                            </span> 
                                                            Placa: <i class="float-end text-black">${res.data.gallos_hijos[0].madre.placa}</i>
                                                        </h5>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                barefoot
                                                            </span> 
                                                            Marca: <i class="float-end text-black">${res.data.gallos_hijos[0].madre.marca_nacimiento}</i>
                                                        </h5>
                                                        <h5 class="card-title text-muted">
                                                            <span class="material-symbols-outlined float-start mx-2">
                                                                favorite
                                                            </span> 
                                                            Estado: <i class="float-end text-black">${res.data.gallos_hijos[0].madre.estatus}</i>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                            `;
                        }
                    }

                    res.data.hijos.forEach(e => {
                        if(e.tipo == "Gallo"){
                            console.log(e.tipo)
                            if(e.gallo.gallos_imagenes.length > 0){
                                template_img = `<img src="files/gallos/${e.gallo.id}/${e.gallo.gallos_imagenes[0].imagen}" class="img-fluid rounded-start h-100" alt="...">`
                            }else{
                                template_img = `<img src="img/avatar.png" alt="" class="img-fluid rounded-start h-100">`;
                            }                          
                            template += `
                                <div class="col-md-12">
                                    <div class="card mb-3" style="max-width: 540px;">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                ${template_img}
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">Hijo</h5>
                                                    <hr>
                                                    <h5 class="card-title text-muted">
                                                        <span class="material-symbols-outlined float-start mx-2">
                                                            loyalty
                                                        </span> 
                                                        Placa: <i class="float-end text-black">${e.gallo.placa}</i>
                                                    </h5>
                                                    <h5 class="card-title text-muted">
                                                        <span class="material-symbols-outlined float-start mx-2">
                                                            barefoot
                                                        </span> 
                                                        Marca: <i class="float-end text-black">${e.gallo.marca_nacimiento}</i>
                                                    </h5>
                                                    <h5 class="card-title text-muted">
                                                        <span class="material-symbols-outlined float-start mx-2">
                                                            favorite
                                                        </span> 
                                                        Estado: <i class="float-end text-black">${e.gallo.estatus}</i>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }

                    });

                    $('#pedigree-section').html(template)
                    $('#modal-pedigree').modal('show')

                

                }).catch(function(err){
                    Swal.fire({
                        icon: 'error',
                        text: 'err'
                    })
                    console.log(err)
                })
            })

            async function getGallos(){
                const response = await fetch('api/gallinas');
                const data = await response.json();
                let template = "", 
                    template_img = "",
                    template_video = "",
                    template_file = "",
                    btn = "",
                    size = "",
                    btnLike = "";
                moment.locale('es');
                data.data.forEach(e => {
                    console.log(e)
                    if(e.gallinas_imagenes.length > 0){
                        // e.gallos_imagenes.forEach(f => {
                        //     if(e.gallos_imagenes.length == 1){
                        //         size = "w-100"
                        //     }else if(e.gallos_imagenes.length = 2){
                        //         size = "w-50"
                        //     }else{
                        //         size = "w-25"
                        //     }

                        //     if(f.extention == 'png' || f.extention == 'jpg') {
                        //             template_img += `
                        //             <img src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size} float-left">
                        //             `;
                        //     }

                        //     if(f.extention == 'mp4') {
                        //         template_video += `
                        //         <video src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size}" controls/>
                        //         `;
                        //     }

                        //     if(f.extention != 'mp4' && f.extention != 'png' && f.extention != 'jpg') {
                        //         template_file += `
                        //         <a href="files/gallos/${e.placa}/${f.imagen}" alt="" class="btn btn-outline-primary ${size}">
                        //             <span class="material-symbols-outlined">
                        //                 download
                        //             </span> 
                        //             Descargar ${f.route}                                    
                        //         </a>
                        //         `;
                        //     }
                        // });
                        template_img = `<img src="files/gallinas/${e.id}/${e.gallinas_imagenes[0].imagen}" class="card-img-top" alt="...">`
                    }else{
                        template_img = `<img src="img/avatar-2.png" alt="" class="w-100">`;
                    }

                    if(e.estatus != "Vendido" && e.estatus != "Fallecida"){
                        console.log(e.estatus)
                        btn = `<a href="#" class="btn btn-dark my-2  btn-sm float-end sell" data-id="${e.id}" data-placa="${e.placa}" data-bs-toggle="tooltip" title="Vender gallo">
                                        <span class="material-symbols-outlined float-start">
                                            sell
                                        </span> 
                                    </a>`;
                    }
                

                    template += `
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-4">
                        <div class="card mt-4 mx-auto shadow">
                            ${template_img}
                            <div class="card-body border-top">                                
                                <h5 class="card-title text-muted">
                                    <span class="material-symbols-outlined float-start mx-2">
                                         loyalty
                                    </span> 
                                    Placa: <i class="float-end text-black">${e.placa}</i>
                                </h5>
                                <h5 class="card-title text-muted">
                                    <span class="material-symbols-outlined float-start mx-2">
                                         barefoot
                                    </span> 
                                    Marca: <i class="float-end text-black">${e.marca_nacimiento}</i>
                                </h5>
                                <h5 class="card-title text-muted">
                                    <span class="material-symbols-outlined float-start mx-2">
                                         favorite
                                    </span> 
                                    Estado: <i class="float-end text-black">${e.estatus}</i>
                                </h5>
                                <hr>
                                <div class="container-fluid">
                                    <a href="#" class="btn text-danger my-2  btn-sm float-end pedigree" data-id="${e.id}" data-bs-toggle="tooltip" title="Pedigree">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            account_tree
                                        </span>
                                    </a>
                                    <a href="report/show-gallina/${e.id}" class="btn text-muted my-2  btn-sm float-end" data-id="${e.id}" data-bs-toggle="tooltip" title="Imprimir ficha" target="_blank">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            picture_as_pdf
                                        </span>
                                    </a>
                                    <a href="#" class="btn text-muted my-2  btn-sm float-end edit" data-id="${e.id}" data-bs-toggle="tooltip" title="Editar gallina">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            edit
                                        </span>
                                    </a>
                                    <a href="#" class="btn text-danger my-2  btn-sm float-end delete" data-id="${e.id}" data-bs-toggle="tooltip" title="Eliminar gallina">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            delete_forever
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    template_file = "";
                    template_img = "";
                    template_video = "";
                    btn="";
                });
                $('#gallos-section').html(template)

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
                let template = "<option value>--- SELECCIONE UNA PLACA ---</option>";
                moment.locale('es');
                console.log(data)
                data.data.forEach(e => {
                    template += `
                        <option value="${e.id}">${e.placa}</option>
                    `;
                });
                $('#padre_id').html(template)
                // new TomSelect("[name=padre_id]",{
                //     persist: false,
                //     createOnBlur: true,
                //     create: true
                // });
            }

            async function getGallinasPlacas(){
                const response = await fetch('api/gallinas');
                const data = await response.json();
                let template = "<option value>--- SELECCIONE UNA PLACA ---</option>";
                moment.locale('es');
                console.log(data)
                
                data.data.forEach(e => {
                    template += `
                        <option value="${e.id}">${e.placa}</option>
                    `;
                });
                $('#madre_id').html(template)
                // new TomSelect("[name=madre_id]",{
                //     persist: false,
                //     createOnBlur: true,
                //     create: true
                // });
            }

        })
</script>
@endsection
