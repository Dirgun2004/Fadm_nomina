    <a class="skip-link" href="#contenido">Saltar al contenido</a>

    <!-- Header -->
    <header class="landing-header">
      <div class="landing-container header__inner">
        <a class="brand" href="#inicio" aria-label="Ir al inicio">
          <img
            class="brand__logo"
            src="build/img/logo-horizontal.svg"
            alt="FADM Asesores"
          />
        </a>

        <button
          class="nav__toggle"
          type="button"
          aria-expanded="false"
          aria-controls="navMenu"
        >
          <span class="sr-only">Abrir menú</span>
          <span class="nav__bars" aria-hidden="true"></span>
        </button>

        <nav id="navMenu" class="landing-nav" aria-label="Navegación principal">
          <a target="_blank" href="/">Pagina Web</a>
          <a target="_blank" href="/admin/dashboard">Nomina</a>
          <a
            class="landing-btn btn--sm btn--primary nav__cta"
            href="/registro-admin"
            rel="noopener"
          >
            Registrarme
          </a>
        </nav>
      </div>
    </header>

<main id="contenido">
<div class="formulario__contenedor formulario__contenedor-login">
      <?php 
        include_once __DIR__ . '../../templates/alertas.php';
    ?>
    <form method="POST" class="form">
        <h3>Sistema Nomina</h3>

            <div class="form__camp">
                <label for="email" class="form__label">Correo:</label>
                <input 
                type="email" 
                class="form__input form__input-login"
                name="email"
                id="email"
                placeholder="Ingrese su Correo Electronico"
                required
                />
            </div>
            <div class="form__camp">
                <label for="pass" class="form__label">Contraseña:</label>
                <input 
                type="password" 
                class="form__input form__input-login"
                name="password"
                id="password"
                placeholder="Ingrese su contraseña"
                required
                />
            </div>

        <button type="submit" class="form__submit">Iniciar Session</button>
    </form>
</div>
</main>