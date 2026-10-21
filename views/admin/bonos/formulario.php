<div class="empresas__volver">
    <a href="/admin/bonos/empresa?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
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
            <legend class="form__legend">Datos del Bono:</legend>
            <div class="form__camp">
                <label for="concepto" class="form__label">Concepto:</label>
                <input 
                type="text"
                class="form__input" 
                name="concepto" 
                id="concepto"
                placeholder="Ej. Bono de Transporte"
                required
                />
            </div> 
            <div class="form__camp">
                <label for="empleado" class="form__label">Empleado:</label>
                <select class="form__select" name="empleado_id" id="empleado">
                    <option value="" selected disabled>-- SELECCIONE --</option>
                    <?php foreach($empleados as $empleado){ ?>
                        <option class="form__select-option" value="<?php echo $empleado->id; ?>"><?php echo $empleado->nombres . ' ' . $empleado->apellidos; ?></option>
                    <?php } ?>
                </select>
            </div> 
            <div class="form__camp">
                <label for="periodo" class="form__label">Tipo de periodo:</label>
                <select class="form__select" name="tipo_periodo" id="periodo" required>
                    <option value="" selected disabled>-- SELECCIONE --</option>
                    <?php foreach($periodos as $periodo){ ?>
                        <option class="form__select-option" value="<?php echo $periodo->id; ?>"><?php echo $periodo->tipo; ?></option>
                    <?php } ?>
                </select>
            </div> 
            <div class="form__camp">
                <label for="fecha" class="form__label">Fecha de Pago:</label>
                <input 
                type="date"
                class="form__input" 
                name="fecha" 
                id="fecha"
                required
                />
            </div> 
            <div class="form__camp">
                <label for="monto_usd" class="form__label">Monto USD:</label>
                <input 
                type="text" 
                inputmode="numeric"
                name="monto_usd" 
                id="monto_usd" 
                class="form__input"
                placeholder="Ingresar Monto del bono en USD"
                required
                />
            </div>
            <div class="form__camp">
                <label for="tasa" class="form__label">Tasa:</label>
                <input 
                type="text" 
                inputmode="numeric"
                name="tasa" 
                id="tasa" 
                class="form__input"
                placeholder="Ingresar la tasa del dolar"
                required
                />
            </div>
        </fieldset>

        <button class="form__submit" type="submit"><?php echo pagina_actual('/crear') ? 'Crear' : 'Actualizar' ?></button>
    </form>
</div>