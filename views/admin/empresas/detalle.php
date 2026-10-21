<div class="empresas">
    <div class="empresas__volver">
    <a href="/admin/empresas?page=1" class="empresas__crear-enlace">
        <i class="fa-solid fa-angle-left"></i>
        volver
    </a>
    </div>
    <div class="detalle__grid">

        <div class="detalle__empleados">
            <a href="/admin/empleados?id=<?php echo $empresa->id; ?>" class="detalles-empleado">
                <div class="detalles-empleado__icono" ><i class="fa-solid fa-user"></i></i></div>
                <h3>Empleados Activos:</h3>
                <p><?php echo $empleadosActivos; ?></p>
            </a>

            <a href="/admin/empleados?id=<?php echo $empresa->id; ?>" class="detalles-empleado">
                <div class="detalles-empleado__icono" ><i class="fa-solid fa-user"></i></i></div>
                <h3>En Vacaciones:</h3>
                <p><?php echo $empleadosVacaciones; ?></p>
            </a>

            <a href="#" class="detalles-empleado">
                <div class="detalles-empleado__icono" ><i class="fa-solid fa-user"></i></i></div>
                <h3>Empleados Inactivos:</h3>
                <p><?php echo $empleadosInactivos; ?></p>
            </a>
        </div>

        <div class="detalle__reportes">
                <a href="/admin/nomina/empresa?id=<?php echo $empresa->id ;?>" class="detalles">
                    
                    <div class="detalles__icono" ><i class="fa-solid fa-file-lines"></i></div>
                    <h3>Nomina</h3>
                    <p><?php echo $nominas; ?></p>
                </a>

                <a href="/admin/cestaticket/empresa?id=<?php echo $empresa->id ;?>" class="detalles">
                    <div class="detalles__icono"><i class="fa-solid fa-credit-card"></i></div> 
                    <h3>Cestaticket</h3>
                    <p><?php echo $cestaticket; ?></p>
                </a>

                <a href="/admin/bonos/empresa?id=<?php echo $empresa->id ;?>" class="detalles">
                    <div class="detalles__icono"><i class="fa-solid fa-money-bill"></i></div>
                    <h3>Bonos</h3>
                    <p><?php echo $bonos; ?></p>
                </a>

                <a href="/admin/vacaciones?id=<?php echo $empresa->id ;?>" class="detalles">
                    <div class="detalles__icono"><i class="fa-solid fa-umbrella-beach"></i></div>
                    <h3>Vacaciones</h3>
                    <p><?php echo $vacaciones; ?></p>
                </a>
        </div>

    </div>
</div>