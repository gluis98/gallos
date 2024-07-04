@extends('layouts.app')

@section('content')
    <!-- Modal gallo -->
<div class="modal fade" id="modal-gallo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar gallo</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-gallo">
            <div class="modal-body">
                <div class="alert alert-light">
                    <p>Los campos con <span class="text-danger">*</span> son obligatorios</p>
                </div>
                @csrf
                <div class="form-group mb-2">
                    <label for="placa">Placa *</label>
                    <input type="text" class="form-control" name="placa" id="placa" required>
                </div>
                <div class="form-group mb-2">
                    <label for="marca_nacimiento">Marca de nacimiento *</label>
                    <input type="text" class="form-control" name="marca_nacimiento" id="marca_nacimiento" required>
                </div>

                <div class="form-group mb-2">
                    <label for="marca_federacion">Marca de federacion *</label>
                    <input type="text" class="form-control" name="marca_federacion" id="marca_federacion" required>
                </div>

                <div class="form-group mb-2">
                    <label for="color">Color *</label>
                    <input type="text" class="form-control" name="color" id="color" required>
                </div>

                <div class="form-group mb-2">
                    <label for="cresta">Cresta *</label>
                    <input type="text" class="form-control" name="cresta" id="cresta" required>
                </div>

                <div class="form-group mb-2">
                    <label for="fecha_nacimiento">Fecha de nacimiento *</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </div>

                <div class="form-group mb-2">
                    <label for="luna">Luna *</label>
                    <input type="text" class="form-control" name="luna" id="luna" required>
                </div>

                <div class="form-group mb-2">
                    <label for="peleas">Cantidad de peleas *</label>
                    <input type="text" class="form-control" name="peleas" id="peleas" required>
                </div>

                <div class="form-group mb-2">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label for="imagen">Fotografia(s) *</label>
                    <input type="file" multiple class="form-control" name="imagen[]" id="imagen" required>
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

    <section class="container-fluid bg-white p-4">
        <h2>Gallos</h2>
        <hr>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-gallo">
            <span class="material-symbols-outlined float-start me-2">
                add_circle
            </span>
            Nuevo gallo
        </button>
        <button class="btn btn-dark ">
            <span class="material-symbols-outlined float-start me-2">
                picture_as_pdf
            </span>
            PDF
        </button>
        <form id="form-search" class="my-2">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="search" id="search" placeholder="Buscar...">
            </div>
        </form>

        <div id="gallos-section" class="row"></div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        getGallos();

        $('#form-gallo').submit(function(e){
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
                fetch('api/gallos', {
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
                    $('#form-gallo').trigger('reset')
                 });
            })

            $('#form-gallo-edit').submit(function(e){
                e.preventDefault();
                let form =  new FormData(this);
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
                console.log(post_id)
                fetch(`api/posts/${post_id}`, {
                    method: 'POST',
                    body: form
                }).then(response => response.text())
                .then(data => {
                    console.log(data)
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
                        getPost();
                    $('#form-gallo-edit').trigger('reset')
                    $('#modal-gallo-edit').modal('hide')
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
                    fetch('api/gallos/'+id, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(res => {
                        Swal.fire({
                            title: "Eliminada",
                            text: res.msj,
                            icon: "success"
                        });
                        getGallos();
                    })
                    
                }
                });
            })

            async function getGallos(){
                const response = await fetch('api/gallos');
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
                    if(e.gallos_imagenes.length > 0){
                        e.gallos_imagenes.forEach(f => {
                            if(e.gallos_imagenes.length == 1){
                                size = "w-100"
                            }else if(e.gallos_imagenes.length = 2){
                                size = "w-50"
                            }else{
                                size = "w-25"
                            }

                            if(f.extention == 'png' || f.extention == 'jpg') {
                                    template_img += `
                                    <img src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size} float-left">
                                    `;
                            }

                            if(f.extention == 'mp4') {
                                template_video += `
                                <video src="files/gallos/${e.placa}/${f.imagen}" alt="" class="${size}" controls/>
                                `;
                            }

                            if(f.extention != 'mp4' && f.extention != 'png' && f.extention != 'jpg') {
                                template_file += `
                                <a href="files/gallos/${e.placa}/${f.imagen}" alt="" class="btn btn-outline-primary ${size}">
                                    <span class="material-symbols-outlined">
                                        download
                                    </span> 
                                    Descargar ${f.route}                                    
                                </a>
                                `;
                            }
                        });
                    }

                

                    template += `
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-4">
                        <div class="card mt-4 mx-auto shadow">
                            <img src="files/gallos/${e.placa}/${e.gallos_imagenes[0].imagen}" class="card-img-top" alt="...">
                            <div class="card-body">                                
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
                                    <a href="#" class="btn btn-dark my-2  btn-sm float-end edit" data-id="${e.id}" data-bs-toggle="tooltip" title="Vender gallo">
                                        <span class="material-symbols-outlined float-start">
                                            sell
                                        </span> 
                                    </a>
                                    <a href="#" class="btn text-muted my-2  btn-sm float-end edit" data-id="${e.id}" data-bs-toggle="tooltip" title="Imprimir ficha">
                                        <span class="material-symbols-outlined float-start mx-2">
                                            picture_as_pdf
                                        </span>
                                    </a>
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
                });
                $('#gallos-section').html(template)

                $(()=>{
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                })
            }
    })
</script>
@endsection
