<div class="empresas__volver">
    <a href="/admin/empresas" class="empresas__crear-enlace">
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
            <legend class="form__legend">Datos de la empresa:</legend>
            <div class="form__camp">
                <label for="nombre" class="form__label">Nombre:</label>
                <input 
                type="text" 
                name="nombre" 
                id="nombre" 
                class="form__input"
                value="<?php echo $empresa->nombre ?? ''; ?>" 
                required
                />
            </div>
            <div class="form__camp">
                <label for="rif" class="form__label">Rif:</label>
                <input 
                type="text" 
                name="rif" 
                id="rif" 
                class="form__input"
                value="<?php echo $empresa->rif ?? ''; ?>" 
                required
                />
            </div>
            <div class="form__camp">
                <label for="direccion" class="form__label">Direccion:</label>
                <textarea name="direccion" id="direccion" rows="4" maxlength="120" class="form__textarea" required><?php echo $empresa->direccion ?? ''; ?></textarea>
            </div>
            <div class="form__camp">
                <label for="contribuyente_id" class="form__label">Contribuyente:</label>
                <select class="form__select" name="contribuyente_id" id="contribuyente_id" required>
                    <option value="" disabled <?php echo empty($empresa->contribuyente_id) ? 'selected' : ''; ?> > -- SELECCIONA --</option>
                    <option value="1" <?php echo ($empresa->contribuyente_id === '1') ? 'selected' : ''; ?> >Ordinario</option>
                    <option value="2" <?php echo ($empresa->contribuyente_id === '2') ? 'selected' : ''; ?> >Especial</option>
                </select>
            </div>
            <div class="form__camp">
                <label for="estatus_id" class="form__label">Estatus:</label>
                <select class="form__select" name="estatus_id" id="estatus_id" required>
                    <option value="" disabled <?php echo empty($empresa->estatus_id) ? 'selected' : ''; ?> > -- SELECCIONA --</option>
                    <option value="1" <?php echo ($empresa->estatus_id === '1') ? 'selected' : ''; ?> >Activa</option>
                    <option value="2" <?php echo ($empresa->estatus_id === '2') ? 'selected' : ''; ?> >Inactiva</option>
                </select>
            </div>
        </fieldset>
        <button class="form__submit" type="submit"><?php echo pagina_actual('/crear') ? 'Crear' : 'Actualizar' ?></button>
    </form>
</div>