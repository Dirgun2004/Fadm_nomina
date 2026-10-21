<div class="empresas">
    <div class="empresas__volver">
        <a href="/admin/vacaciones/crear-personalizado?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-plus"></i>
            Variable
        </a>
        <a href="/admin/vacaciones/crear?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-plus"></i>
            Crear
        </a>

        <a href="/admin/empresas/detalle?id=<?php echo $_GET['id'] ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-angle-left"></i>
            volver
        </a>
    </div>
    <div class="empresas__grid">
        <?php if(!empty($vacaciones)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>PERIODO</th>
                        <th>EMPLEADO</th>
                        <th>TOTAL</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($vacaciones as $vacacion){ ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <p><?php echo date("d-m-Y", strtotime($vacacion->fecha_inicio)) . '<br>' . date("d-m-Y", strtotime($vacacion->fecha_fin)) ?></p>
                        </td>
                        <td class="table__td"><?php echo $vacacion->empleado->nombres . ' <br> ' . $vacacion->empleado->apellidos; ?></td>
                        <td class="table__td">Bs. <?php echo number_format($vacacion->asignacion_deduccion_total, 2, ',', '.'); ?></td>

                        <td class="table__acciones">
                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-caret-down" style="font-size: 1.1rem;"></i>
                                </button>
                                
                                <div class="dropdown-menu">
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/vacaciones/reporte?id=<?php echo $vacacion->id . '&empresa=' . $vacacion->empresa_id; ?>">
                                        <i class="fa-solid fa-file-lines" style="margin-right: 8px; color: #72051a;"></i> Recibo
                                    </a>
                                </div>
                            </div>

                            <form action="/admin/vacaciones/eliminar" method="POST" class="form-eliminar">
                                <input type="hidden" name="id" value="<?php echo $vacacion->id; ?>">
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