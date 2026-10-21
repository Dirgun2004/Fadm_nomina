<div class="empresas">
    <div class="empresas__volver">
        <a href="/admin/bonos/crear?id=<?php echo $_GET['id']; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-plus"></i>
        Crear
        </a>
        <a href="/admin/bonos/empresa?id=<?php echo $_GET['id'] . '&page=1'; ?>" class="empresas__crear-enlace">
            <i class="fa-solid fa-angle-left"></i>
            volver
        </a>
    </div>
    <div class="empresas__grid">
        <?php if(!empty($bonos)){ ?>
        <div class="empresa">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Concepto</th>
                        <th>Monto USD</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bonos as $bono){ ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <p><?php echo date("d-m-Y", strtotime($bono->fecha)); ?></p>
                        </td>

                        <td class="table__td"><?php echo $bono->empleado->nombres . ' ' . $bono->empleado->apellidos ?? ''; ?></td>

                        <td class="table__td"><?php echo $bono->concepto; ?></td>

                        <td class="table__td">$<?php echo number_format($bono->monto_usd, 2, ',', '.'); ?></td>

                        <td class="table__acciones">
                            <div class="dropdown" id="dropdownReportes">
                                <button type="button" class="dropdown-toggle dropdown-toggle-adjust table__td-accion table__td-accion-pdf">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                                
                                <div class="dropdown-menu dropdown-menu-adjust">
                                    <a target="_blank" rel="noopener noreferrer" href="/admin/bonos/recibo?empresa=<?php echo $_GET['id']; ?>&id=<?php echo $bono->id; ?>">
                                        <i class="fa-solid fa-percent" style="margin-right: 8px; color: #007BFF;"></i> 
                                        Reporte
                                    </a>

                                    <!-- MODAL DE EDICION INICIO -->

                                    <a rel="noopener noreferrer" href="#" class="abrir-modal" data-target="modal-bono-<?php echo $bono->id; ?>">
                                        <i class="fa-solid fa-pen-to-square" style="margin-right: 8px; color: #007BFF;"></i>  Editar
                                    </a>

                                    <div id="modal-bono-<?php echo $bono->id; ?>" class="modal">
                                        <div class="modal__contenido">
                                            <span id="cerrarModal" class="modal__cerrar">&times;</span>
                                            
                                            <h2 class="modal__titulo">Editar Bonos</h2>
                                            
                                            <form class="form form-ajax" data-url="/api/bonos/editar">
                                                <div class="form__camp">
                                                    <label for="periodo" class="form__label">Tipo de periodo:</label>
                                                    <select class="form__select" name="tipo_periodo" id="periodo">
                                                        <?php foreach($TipoPeriodos as $TipoPeriodo){ ?>
                                                            <option class="form__select-option" <?php echo ($bono->tipo_periodo === $TipoPeriodo->id) ? 'selected' : ''; ?> value="<?php echo $TipoPeriodo->id; ?>"><?php echo $TipoPeriodo->tipo; ?></option>
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
                                                    value="<?php echo $bono->fecha; ?>"
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
                                                    value="<?php echo $bono->monto_usd; ?>"
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
                                                    value="<?php echo $bono->tasa; ?>"
                                                    />
                                                </div>
                                                <input type="hidden" name="bono_id" value="<?php echo $bono->id; ?>">
                                                <button type="submit" class="form__submit">Guardar</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- MODAL DE EDICION FIN -->

                                </div>
                            </div>
                            <form action="/admin/bonos/eliminar" method="POST" class="form-eliminar">
                                <input type="hidden" name="id" value="<?php echo $bono->id; ?>">
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