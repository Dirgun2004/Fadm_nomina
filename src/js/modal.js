document.addEventListener('DOMContentLoaded', function() {
    const abrirBtns = document.querySelectorAll('.abrir-modal');
    const cerrarBtns = document.querySelectorAll('.modal__cerrar');
    const modales = document.querySelectorAll('.modal');

    // 1. Abrir el modal correspondiente según el atributo data-target
    abrirBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.dataset.target; // Lee el ID del data-target
            const modalSeleccionado = document.querySelector(`#${modalId}`);
            if(modalSeleccionado) {
                modalSeleccionado.classList.add('modal--activo');
            }
        });
    });

    // 2. Cerrar el modal al hacer clic en cualquier "X"
    cerrarBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal'); // Busca el contenedor modal más cercano
            modal.classList.remove('modal--activo');
        });
    });

    // 3. Cerrar el modal si hacen clic en el fondo gris oscuro
    window.addEventListener('click', function(e) {
        modales.forEach(modal => {
            if (e.target === modal) {
                modal.classList.remove('modal--activo');
            }
        });
    });
});

// ENVIAR DATOS DE FORMULARIOS

document.addEventListener('DOMContentLoaded', function() {
    // Seleccionamos todos los formularios que tengan la clase .form-ajax
    const formularios = document.querySelectorAll('.form-ajax');
                
    const btnCancelarModal = document.querySelectorAll('.modal__acciones-cerrar');
    btnCancelarModal.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = this.closest('.modal');
            if(modal) {
                modal.classList.remove('modal--activo');
            }
        });
    });

    formularios.forEach(formulario => {
        formulario.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita que la página recargue

            const url = this.dataset.url; // Lee a qué API debe enviar los datos (vacaciones o deducciones)
            const datos = new FormData(this); // Recoge los campos de ese formulario específico

            fetch(url, {
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                if(resultado.resultado === true) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Registrado!',
                        text: resultado.mensaje,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        this.reset(); // Limpia el formulario actual

                        // Cierra el modal contenedor más cercano
                        const modalActual = this.closest('.modal');
                        if(modalActual) {
                            modalActual.classList.remove('modal--activo');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: resultado.mensaje || 'Ocurrió un error.'
                    });
                }
            })
            .catch(error => {
                console.log('Error:', error);
            });
        });
    });
});

// ELIMINAR EMPLEADO

document.addEventListener('DOMContentLoaded', function() {
    const formulariosEliminar = document.querySelectorAll('.form-eliminar');

    formulariosEliminar.forEach(formulario => {
        formulario.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita que la página se recargue de golpe

            // 1. Mostrar la alerta de confirmación con SweetAlert2
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción cambiará el estatus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar estatus',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // 2. Si el usuario confirma
                if (result.isConfirmed) {
                    const url = this.action;
                    const datos = new FormData(this);

                    // 3. Petición Fetch por detrás
                    fetch(url, {
                        method: 'POST',
                        body: datos
                    })
                    .then(respuesta => respuesta.json())
                    .then(resultado => {
                        if(resultado.tipo === 'exito') {
                            Swal.fire(
                                '¡Actualizado!',
                                resultado.mensaje,
                                'success'
                            ).then(() => {
                                
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                resultado.mensaje,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.log('Error:', error);
                    });
                }
            });
        });
    });
});

// VERIFICAR QUE EL USUARIO NO ENVIE UNA NOMINA CON UNA FECHA ERRONEA (INICIO MAYOR QUE FIN)
document.addEventListener('DOMContentLoaded', function() {
    const formularioNomina = document.querySelector('.form'); // O puedes asignarle un ID único como #form-nomina

    if (formularioNomina) {
        formularioNomina.addEventListener('submit', function(e) {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                e.preventDefault(); // Detiene el envío del formulario por completo
                
                Swal.fire({
                    icon: 'error',
                    title: '¡Fechas incorrectas!',
                    text: 'La fecha de inicio no puede ser mayor que la fecha de fin.'
                });
            }
        });
    }
});