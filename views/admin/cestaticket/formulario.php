<div class="empresas__volver">
    <a href="/admin/cestaticket/empresa?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
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
            <legend class="form__legend">Datos del cestaticket:</legend>
            <div class="form__camp">
                <label for="inicio" class="form__label">Desde:</label>
                <input 
                type="date"
                class="form__input" 
                name="periodo_inicio" 
                id="inicio"
                required
                />
            </div> 
            <div class="form__camp">
                <label for="fin" class="form__label">Hasta:</label>
                <input 
                type="date"
                class="form__input" 
                name="periodo_fin" 
                id="fin"
                required
                />
            </div> 
                        <div class="form__camp">
                <label for="monto" class="form__label">Monto:</label>
                <input 
                type="text" 
                inputmode="numeric"
                name="monto" 
                id="monto" 
                class="form__input"
                placeholder="Ingresar Monto del CestaTicket"
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