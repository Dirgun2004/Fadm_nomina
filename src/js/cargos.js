const btnCargo = document.querySelector('#boton-agregar-cargo');

if(btnCargo){
    btnCargo.addEventListener('click', async (e) => {
        e.preventDefault();

        const { value: nombreCargo } = await Swal.fire({
        title: "Agrega un cargo",
        input: "text",
        inputLabel: "Ej. Contador Público",
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        customClass: {
        confirmButton: 'btn-alerta-confirmar', // Tu clase personalizada para confirmar
        cancelButton: 'btn-alerta-cancelar'   // Tu clase personalizada para cancelar
    },

        inputValidator: (value) => {
            if (!value.trim()) {
                return "El campo está vacío";
            }
        }
        });
// 2. Si la contadora escribió el cargo y presionó Guardar, lo enviamos al servidor
        if (nombreCargo) {
            try {
                // Preparamos los datos igual que en tu curso
                const datos = new FormData();
                datos.append('nombre', nombreCargo); // Ajusta la clave según lo que reciba tu PHP

                const respuesta = await fetch("/api/cargo/crear", {
                    method: 'POST',
                    body: datos
                });

                const resultado = await respuesta.json();

                if(resultado.resultado || resultado.tipo === 'exito') {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El cargo se agregó correctamente',
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Recarga para actualizar la tabla
                    });
                } else {
                    Swal.fire('Error', resultado.mensaje || 'No se pudo guardar el cargo', 'error');
                }

            } catch (error) {
                console.log(error);
                Swal.fire('Error', 'Hubo un error de conexión', 'error');
            }
        }       
    });
}