
<div class="empresas">
    <div class="empresas__volver">
    <a href="/admin/empleados/crear?empresa=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
        <i class="fa-solid fa-plus"></i>
        Registrar
    </a>
    <a href="/admin/empresas/detalle?id=<?php echo $_GET['id'] ?>" class="empresas__crear-enlace">
        <i class="fa-solid fa-angle-left"></i>
        volver
    </a>
    </div>
    <div class="empresas__grid">
        <?php if(!empty($empleados)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Cedula</th>
                        <th>Cargo</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($empleados as $empleado){ ?>
                    <tr class="table__tr">
                        <td class="table__td"><?php echo $empleado->nombres . ' ' . $empleado->apellidos; ?></td>
                        <td class="table__td"><?php echo $empleado->documento->tipo . '-' . $empleado->cedula; ?></td>
                        <td class="table__td"><?php echo $empleado->cargo->nombre; ?></td>
                        <td class="table__td">
                            <p class="table__td-<?php echo strtolower($empleado->estatus->estado); ?>"><?php echo $empleado->estatus->estado; ?></p>
                        </td>
                        <td class="table__acciones">
                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle dropdown-toggle-adjust table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                                
                                <div class="dropdown-menu dropdown-menu-adjust">
                                    <a rel="noopener noreferrer" href="/admin/empleados/editar?empresa=<?php echo $_GET['id']; ?>&id=<?php echo $empleado->id; ?>">
                                        <i class="fa-solid fa-pen-to-square" style="margin-right: 8px; color: #007BFF;"></i> 
                                        Editar
                                    </a>


                                    <!-- MODAL DE DEDUCCIONES INICIO -->

                                    <a rel="noopener noreferrer" href="#" class="abrir-modal" data-target="modal-deducciones-<?php echo $empleado->id; ?>">
                                        <i class="fa-solid fa-coins" style="margin-right: 8px; color: #007BFF;"></i> Deducciones
                                    </a>

                                    <div id="modal-deducciones-<?php echo $empleado->id; ?>" class="modal">
                                        <div class="modal__contenido">
                                            <span id="cerrarModal" class="modal__cerrar">&times;</span>
                                            
                                            <h2 class="modal__titulo">Deduccion Dias Empleado</h2>
                                            
                                            <form class="form form-ajax" data-url="/api/deducciones/crear">
                                                <div class="form__camp">
                                                    <label for="deduccion" class="form__label">Fecha:</label>
                                                    <input type="date" id="deduccion" name="fecha" class="form__input">
                                                </div>
                                                <div class="form__camp">
                                                    <label for="desripcion" class="form__label">Motivo:</label>
                                                    <textarea name="descripcion" id="descripcionDeduccion" rows="6" class="form__textarea"></textarea>
                                                </div>
                                                <input type="hidden" name="empleado_id" value="<?php echo $empleado->id; ?>">
                                                <button type="submit" class="form__submit">Guardar</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- MODAL DEDUCCIONES FIN -->

                                </div>
                            </div>
                            <form action="/admin/empleados/eliminar" method="POST" class="form-eliminar">
                                <input type="hidden" name="id" value="<?php echo $empleado->id; ?>">
                                <button type="submit" class="table__acciones-eliminar table__td-accion">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
 
        </div>
        <?php }else{?>
        <div class="table__no-resultado">
            <p>No hay resultados</p>                        
        </div>
        <?php } ?>            
    </div>
</div>

    <?php
    echo $paginacion ?? '';
    ?>