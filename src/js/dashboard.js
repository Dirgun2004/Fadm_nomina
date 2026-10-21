const btnFeriado = document.querySelector('#boton-feriado');

if(btnFeriado) {
    btnFeriado.addEventListener('click', async () => {
        const { value: formValues } = await Swal.fire({
            title: 'Registrar Nuevo Feriado',
            html: `
                <div class="form" style="text-align: left; margin-top: 1rem;">
                    <div class="form__camp">
                        <label class="form__label" >Fecha del Feriado</label>
                        <input type="date" id="swal-fecha" class="form__input">
                    </div>
                    <div class="form__camp">
                        <label class="form__label" >Descripción / Motivo</label>
                        <input type="text" id="swal-descripcion" class="form__input" placeholder="Ej. Carnaval, 19 de Abril...">
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar Feriado',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'form__submit', // Puedes usar tu clase de botón del SCSS si deseas
            },
            preConfirm: () => {
                const fecha = document.getElementById('swal-fecha').value;
                const descripcion = document.getElementById('swal-descripcion').value;

                // Validación básica antes de cerrar el modal
                if (!fecha || !descripcion) {
                    Swal.showValidationMessage('Por favor completa ambos campos');
                    return false;
                }

                return { fecha, descripcion };
            }
        });

        // Si la contadora presionó "Guardar" y pasó la validación
        if (formValues) {
            // Aquí puedes enviar los datos por AJAX (fetch) a tu controlador PHP
            guardarFeriadoEnServidor(formValues);
        }
    });
}

async function guardarFeriadoEnServidor(datos) {
    try {
        const respuesta = await fetch('/api/feriado/crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const resultado = await respuesta.json();

        if(resultado.tipo === 'exito') {
            Swal.fire('¡Guardado!', 'El feriado se registró correctamente.', 'success')
                .then(() => {
                    location.reload(); // Recargas para actualizar la tabla o vista si es necesario
                });
        } else {
            Swal.fire('Error', resultado.mensaje || 'No se pudo guardar el feriado', 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Ocurrió un error de conexión con el servidor', 'error');
    }
}

document.addEventListener('click', function(e) {
    // 1. Detectar si se hizo clic en el botón del dropdown o su interior
    const toggleBtn = e.target.closest('.dropdown-toggle');
;
    if (toggleBtn) {
        const dropdown = toggleBtn.closest('.dropdown');
        
        // Cerrar los demás dropdowns abiertos
        document.querySelectorAll('.dropdown').forEach(item => {
            if (item !== dropdown) {
                item.classList.remove('activo');
            }
        });

        // Alternar el actual
        dropdown.classList.toggle('activo');
        return;
    }

    // 2. Si se hace clic fuera de cualquier dropdown, cerrarlos todos
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(item => {
            item.classList.remove('activo');
        });
    }
});
