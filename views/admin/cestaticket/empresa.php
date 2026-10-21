<div class="empresas">
    <div class="empresas__volver">
        <a href="/admin/cestaticket/crear?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-plus"></i>
            Crear
        </a>
        <a href="/admin/empresas/detalle?id=<?php echo $_GET['id'] ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-angle-left"></i>
            volver
        </a>
    </div>
    <div class="empresas__grid">
        <?php if(!empty($cestatickets)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Empleados</th>
                        <th>Asiganaciones</th>
                        <th>Deducciones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cestatickets as $cestaticket){ ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <p><?php echo date("d-m-Y", strtotime($cestaticket->periodo_inicio)); ?></p>
                            <p><?php echo date("d-m-Y", strtotime($cestaticket->periodo_fin)); ?></p>
                        </td>
                        <td class="table__td"><?php echo $totalEmpleados ?? ''; ?></td>
                        <td class="table__td"><?php echo number_format($cestaticket->total_asignacion, 2, ',', '.'); ?></td>
                        <td class="table__td"><?php echo number_format($cestaticket->total_deduccion, 2, ',', '.'); ?></td>

                        <td class="table__acciones">
                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-caret-down" style="font-size: 1.1rem;"></i>
                                </button>
                                
                                <div class="dropdown-menu">
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/cestaticket/empresa/reporte?id=<?php echo $cestaticket->id; ?>&empresa=<?php echo $_GET['id']; ?>">
                                        <i class="fa-solid fa-file-lines" style="margin-right: 8px; color: #72051a;"></i> Resumen General
                                    </a>
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/cestaticket/empresa/reporte-recibos?id=<?php echo $cestaticket->id; ?>&empresa=<?php echo $_GET['id']; ?>">
                                        <i class="fa-solid fa-percent" style="margin-right: 8px; color: #72051a;"></i> Recibos
                                    </a>
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/cestaticket/empresa/reporte-banco?id=<?php echo $cestaticket->id; ?>&empresa=<?php echo $_GET['id']; ?>">
                                        <i class="fa-solid fa-building-columns" style="margin-right: 8px; color: #72051a;"></i> Resumen para Banco
                                    </a>
                                </div>
                            </div>

                            <form action="/admin/cestaticket/eliminar" method="POST" class="form-eliminar">
                                <input type="hidden" name="id" value="<?php echo $cestaticket->id; ?>">
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