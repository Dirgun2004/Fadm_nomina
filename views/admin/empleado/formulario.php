<div class="empresas__volver">
    <a href="/admin/empleados?id=<?php echo $_GET['empresa']; ?>" class="empresas__crear-enlace">
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
            <legend class="form__legend">Datos del Empleado:</legend>
            <div class="form__camp">
                <label for="nombres" class="form__label">Nombres:</label>
                <input 
                type="text" 
                name="empleado[nombres]" 
                id="nombre" 
                class="form__input"
                placeholder="Ingresar sus dos nombres"
                value="<?php echo $empleado->nombres ?? ''; ?>"
                required
                />
            </div>
            <div class="form__camp">
                <label for="apellidos" class="form__label">Apellidos:</label>
                <input 
                type="text" 
                name="empleado[apellidos]" 
                id="apellido" 
                class="form__input"
                placeholder="Ingresar sus dos apellidos"
                value="<?php echo $empleado->apellidos ?? ''; ?>"
                required
                />
            </div>
            <div class="form__camp">
                <label for="cedula" class="form__label">Cedula:</label>
                <div class="form__camp-duo">
                    <select name="empleado[documento_id]" class="form__select form__select-tipo">
                        <option value="1" selected>V</option>
                        <option value="2">E</option>
                    </select>                    
                    <input 
                    type="text"
                    inputmode="numeric" 
                    name="empleado[cedula]" 
                    id="cedula" 
                    class="form__input"
                    placeholder="Ingresar la cedula"
                    value="<?php echo $empleado->cedula ?? ''; ?>"
                    required
                    />

                </div>
            </div>
            <div class="form__camp">
                <label for="rif" class="form__label">Rif:</label>
                <input 
                type="text"
                inputmode="numeric" 
                name="empleado[rif]" 
                id="rif" 
                class="form__input"
                placeholder="Ej. 12.345.678-9"
                value="<?php echo $empleado->rif ?? ''; ?>"
                required
                />
            </div>
        </fieldset>
        <fieldset class="form__fieldset">
            <legend class="form__legend">Datos adicionales:</legend>
            <div class="form__camp">
                <label for="ingreso" class="form__label">Ingreso:</label>
                <input 
                type="date"
                class="form__input" 
                name="empleado[ingreso]" 
                id="ingreso"
                value="<?php echo $empleado->ingreso ?? ''; ?>"
                required
                />
            </div>    
            <div class="form__camp">
                <label for="cargo_id" class="form__label">Cargo:</label>
                <div class="form__camp-duo-uno">
                    <select name="empleado[cargo_id]" id="" class="form__select" required>
                        <option value="" disabled <?php echo (!$empleado->cargo_id) ? 'selected' : ''; ?> >-- SELECCIONA --</option>
                        <?php foreach($cargos as $cargo){ ?>
                            <option value="<?php echo $cargo->id ?? ''; ?>" <?php echo ($cargo->id === $empleado->cargo_id) ? 'selected' : ''; ?> ><?php echo $cargo->nombre ?? ''; ?></option>
                        <?php } ?>
                    </select>
                    <button class="table__acciones table__acciones-agregar" id="boton-agregar-cargo">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="form__camp">
                <label for="departamento_id" class="form__label">Departamento:</label>
                <div class="form__camp-duo-uno">
                    <!-- CORREGIDO: name="empleado[departamento_id]" -->
                    <select name="empleado[departamento_id]" id="departamento_id" class="form__select" required>
                        <option value="" disabled <?php echo (!$empleado->departamento_id) ? 'selected' : ''; ?>>-- SELECCIONA --</option>
                        <?php foreach($departamentos as $departamento){ ?>
                            <option value="<?php echo $departamento->id ?? ''; ?>" <?php echo ($departamento->id === $empleado->departamento_id) ? 'selected' : ''; ?>><?php echo $departamento->nombre ?? ''; ?></option>
                        <?php } ?>
                    </select>
                    
                    <!-- CORREGIDO: id único para abrir el modal de departamento -->
                    <button type="button" class="table__acciones table__acciones-agregar abrir-modal" id="boton-agregar-departamento" data-target="modal-departamento">
                        <i class="fa-solid fa-plus"></i>
                    </button>

                </div>
            </div>
            <div class="form__camp">
                <label for="sueldo" class="form__label">Sueldo base:</label>
                <div class="form__camp-duo-uno">
                <input 
                type="text"
                inputmode="decimal" 
                name="sueldo_base[sueldo]" 
                id="sueldo" 
                class="form__input"
                placeholder="Monto expresado en Bs."
                value="<?php echo isset($empleado->sueldo) && $empleado->sueldo ? number_format($empleado->sueldo->sueldo, 2, ',', '.') : ''; ?>"
                required
                />
                <input 
                type="date"
                class="form__input" 
                name="sueldo_base[fecha]" 
                id="ingreso"
                value="<?php echo isset($empleado->sueldo) && $empleado->sueldo ? $empleado->sueldo->fecha : $fechaActual; ?>"
                required
                />                     
                </div>               
            </div>            
           
        </fieldset>
        <button class="form__submit" type="submit"><?php echo pagina_actual('/crear') ? 'Crear' : 'Actualizar'; ?></button>
    </form>
                        <!-- MODAL DE DEPARTAMENTO -->
    <div id="modal-departamento" class="modal">
        <div class="modal__contenido">
            <span id="cerrarModal" class="modal__cerrar">&times;</span>
                                            
            <h2 class="modal__titulo">Agregar Departamento</h2>
            <p class="modal__subtitulo">Ej. Recursos Humanos</p>
                                            
            <form class="form form-ajax" data-url="/api/departamento/crear">
                <div class="form__camp">
                    <label for="nombre_departamento" class="form__label">Nombre:</label>
                    <!-- CORREGIDO: name="nombre" acorde a la tabla departamentos -->
                    <input type="text" id="nombre_departamento" name="nombre" class="form__input" required>
                </div>

                <div class="modal__acciones">
                    <button type="submit" class="modal__acciones-guardar">Guardar</button>
                    <!-- CORREGIDO: type="button" para evitar que envíe el formulario principal -->
                    <button type="button" class="modal__acciones-cerrar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>