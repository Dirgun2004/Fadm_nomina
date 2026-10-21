document.addEventListener('DOMContentLoaded', function() {
    const selectEmpleado = document.querySelector('#empleado_id');
    const inputDiasSolicitados = document.querySelector('#dias_solicitados');
    const infoDias = document.querySelector('#info-dias');

    selectEmpleado.addEventListener('change', async function() {
        const empleadoId = this.value;

        if(!empleadoId) return;

        try {
            // Hacemos la petición AJAX a la ruta de tu controlador
            const respuesta = await fetch(`/api/empleado-vacaciones?empleado_id=${empleadoId}`);
            const resultado = await respuesta.json();

            if(resultado.success) {
                // Actualizamos el input de forma dinámica
                inputDiasSolicitados.value = resultado.dias_disponibles;
                inputDiasSolicitados.max = resultado.dias_disponibles;
                
                // Mensaje informativo visual para el usuario
                infoDias.textContent = `Este empleado tiene ${resultado.dias_disponibles} días disponibles (${resultado.anos_servicio} años de servicio).`;
                infoDias.style.color = 'green';
            } else {
                infoDias.textContent = 'No se pudieron calcular los días para este empleado.';
                infoDias.style.color = 'red';
            }
        } catch (error) {
            console.error('Error al obtener los días del empleado:', error);
        }
    });
});