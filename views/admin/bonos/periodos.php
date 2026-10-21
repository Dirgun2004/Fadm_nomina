<div class="empresas">
    <div class="empresas__volver">
        <a href="/admin/bonos/crear?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-plus"></i>
            Crear Bono
        </a>
        <a href="/admin/empresas/detalle?id=<?php echo $_GET['id'] ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-angle-left"></i>
            Volver
        </a>
    </div>

    <div class="empresas__grid">
        <?php if(!empty($meses)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Total Registros</th>
                        <th>Monto Total USD</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($meses as $mes){ ?>
                    <tr class="table__tr">
                        
                        <td class="table__td">
                            <?php                                 
                                $mesesNombres = [
                                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                    '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                    '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                                ];
                                
                                list($anio, $mesNum) = explode('-', $mes->periodo);

                                echo '<span class="font-bold">' . ($mesesNombres[$mesNum] ?? $mes->mes_year) . ' ' . $anio . '</span>';
                            ?>
                        </td>

                        <!-- Cantidad de bonos registrados en ese mes -->
                        <td class="table__td">
                            <?php echo $mes->total_registros; ?>
                        </td>

                        <!-- Monto sumado en dólares -->
                        <td class="table__td">
                            $<?php echo number_format($mes->monto_total_usd, 2, ',', '.'); ?>
                        </td>

                        <td class="table__acciones">

                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-caret-down" style="font-size: 1.1rem;"></i>
                                </button>
                                
                                <div class="dropdown-menu">
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/bonos/empresa/listado-general?periodo=<?php echo $mes->periodo; ?>&empresa=<?php echo $_GET['id']; ?>">
                                        <i class="fa-solid fa-percent" style="margin-right: 8px; color: #72051a;"></i> Recibos
                                    </a>
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/bonos/banco?periodo=<?php echo $mes->periodo; ?>&empresa=<?php echo $_GET['id']; ?>">
                                        <i class="fa-solid fa-building-columns" style="margin-right: 8px; color: #72051a;"></i> Resumen para Banco
                                    </a>
                                </div>
                            </div>

                            <!-- Botón para Ver el Detalle de este mes específico -->
                            <a href="/admin/bonos/detalle?id=<?php echo $_GET['id']; ?>&periodo=<?php echo $mes->periodo; ?>&page=1" class="table__acciones-editar table__td-accion">
                                <i class="fa-solid fa-eye"></i>
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="table__no-resultado">
            <p>No hay registros de bonos para esta empresa</p>                        
        </div>
        <?php } ?>            
    </div>
</div>

<?php 
    // Renderizamos la paginación de los meses
    echo $paginacion ?? ''; 
?>