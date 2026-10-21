<div class="empresas__volver">
    <a href="/admin/nomina/empresa?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
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
            <legend class="form__legend">Datos de la nomina:</legend>
            <div class="form__camp">
                <label for="nombre" class="form__label">Tipo Nomina:</label>
                <select name="tipo_nomina" id="tipo_nomina" class="form__select" required>
                    <?php foreach($tipoNomina as $tipo){ ?>
                    <option value="<?php echo $tipo->id; ?>"><?php echo $tipo->tipo; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form__camp">
                <label class="form__label">Periodo de Nomina:</label>
                <div class="form__radio-contenedor">
                    <?php foreach($nominaPeriodo as $periodo){ ?>
                    <label class="form__radio-label">
                        <input type="radio" class="form__radio-input" name="tipo_periodo" value="<?php echo $periodo->id; ?>" checked>
                        <span><?php echo $periodo->tipo; ?></span>
                    </label>
                    <?php } ?>
                </div>
            </div>
        </fieldset>
        <fieldset class="form__fieldset">
            <legend class="form__legend">Periodo:</legend>
            <div class="form__camp">
                <label for="fecha_inicio" class="form__label">Desde:</label>
                <input 
                type="date"
                class="form__input" 
                name="fecha_inicio" 
                id="fecha_inicio"
                required
                />
            </div> 
            <div class="form__camp">
                <label for="fecha_fin" class="form__label">Hasta:</label>
                <input 
                type="date"
                class="form__input" 
                name="fecha_fin" 
                id="fecha_fin"
                required
                />
            </div> 
        </fieldset>
        <button class="form__submit" type="submit"><?php echo pagina_actual('/crear') ? 'Crear' : 'Actualizar' ?></button>
    </form>
</div>