
<div class="empresas">
    <div class="empresas__grid">
        <?php if(!empty($empresas)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Rif</th>
                        <th>Contribuyente</th>
                        <th>Empleados</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($empresas as $empresa){ ?>
                    <tr class="table__tr">
                        <td class="table__td"><?php echo $empresa->nombre; ?></td>
                        <td class="table__td"><?php echo $empresa->rif; ?></td>
                        <td class="table__td"><?php echo $empresa->contribuyente->tipo; ?></td>
                        <td class="table__td">3</td>
                        <td class="table__td">
                            <p class="table__td-<?php echo strtolower($empresa->estatus->estado); ?>"><?php echo $empresa->estatus->estado; ?></p>
                        </td>
                        <td class="table__acciones">
                            <a href="/admin/empresas/detalle?id=<?php echo $empresa->id; ?>" class="table__acciones-editar table__td-accion">
                                    <i class="fa-solid fa-eye"></i>
                            </a>
                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle dropdown-toggle-adjust table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                                
                                <div class="dropdown-menu dropdown-menu-adjust">
                                    <a rel="noopener noreferrer" href="/admin/empresas/editar?id=<?php echo $empresa->id; ?>">
                                        <i class="fa-solid fa-pen-to-square" style="margin-right: 8px; color: #007BFF;"></i> 
                                        Editar
                                    </a>

                                    <!-- MODAL DE VACACIONES INICIO -->
                                </div>
                            </div>
                            <form action="/admin/empresas/eliminar" method="POST" class="form-eliminar">
                                <input type="hidden" name="id" value="<?php echo $empresa->id; ?>">
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

<div class="empresas__crear">
    <a href="/admin/empresas/crear" class="empresas__crear-enlace">
        <i class="fa-solid fa-plus"></i>
        Crear
    </a>
</div>


    <?php
    echo $paginacion ?? '';
    ?>