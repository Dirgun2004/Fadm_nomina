<aside class="aside">
        <div class="aside__nav-logo">
            <p>FADM</p>
        </div>

        <div class="aside__nav">

        <nav class="nav">
            <a href="/admin/dashboard" class="nav__enlace <?php echo pagina_actual('/dashboard') ? 'nav__enlace-actual' : ''; ?>">
                <i class="fa-solid fa-clipboard"></i>
                Dashboard
            </a>

            <a href="/admin/empresas" class="nav__enlace <?php echo pagina_actual('/empresas') ? 'nav__enlace-actual' : ''; ?>">
                <i class="fa-solid fa-city"></i>
                Empresas
            </a>

            <a href="#" class="nav__enlace <?php echo pagina_actual('/configuracion') ? 'nav__enlace-actual' : ''; ?>">
                <i class="fa-solid fa-gear"></i>
                Configuración
            </a>

        </nav>
        <div class="aside__form">
            <form action="/logout" method="GET">
                <button type="submit" class="aside__form-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar Session
                </button>
            </form>
        </div>
        </div>

</aside>