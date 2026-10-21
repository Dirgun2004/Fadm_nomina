<div class="empresas__volver">
    <a href="/admin/vacaciones?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
        <i class="fa-solid fa-angle-left"></i>
        volver
    </a>
</div>

<div class="formulario__contenedor">
    <?php 
        include_once __DIR__ . '../../../templates/alertas.php';
    ?>
    <form method="POST" class="form">
        <fieldset class="form__fieldset">
            <legend class="form__legend">Periodo:</legend>
            <div class="form__camp">
                <label for="empleados" class="form__label">Empleados:</label>
                <select name="empleado_id" id="empleado_id" class="form__select" required>
                    <option value="" selected disabled>-- SELECCIONAR --</option>
                    <?php foreach($empleados as $empleado){ ?>
                        <option value="<?php echo $empleado->id; ?>"><?php echo $empleado->nombres . ' ' . $empleado->apellidos; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form__camp">
                <label for="inicio" class="form__label">Inicio:</label>
                <input 
                type="date"
                class="form__input" 
                name="fecha_inicio" 
                id="inicio"
                required
                />
            </div> 
            <div class="form__camp">
                <label for="dias_solicitados" class="form__label">Dias:</label>
                <input 
                type="number"
                class="form__input" 
                name="dias_solicitados" 
                id="dias_solicitados" 
                value="15"
                required
                />
                <small id="info-dias">Seleccione un empleado para ver sus días disponibles.</small>
            </div> 
        </fieldset>
        <button class="form__submit" type="submit"><?php echo pagina_actual('/crear') ? 'Crear' : 'Actualizar' ?></button>
    </form>
</div>