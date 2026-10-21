<header class="dashboard__header">
    <h2 class="dashboard__titulo"><?php echo $titulo ?? ''; ?></h2>
    <div class="dashboard__header-descripcion"><p>Bienvenido:</p><span><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?></span></div>
</header>