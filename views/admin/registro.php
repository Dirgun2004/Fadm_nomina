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
            href="/login-admin"
            rel="noopener"
          >
            Iniciar Sesion
          </a>
        </nav>
      </div>
    </header>

<main id="contenido">
<div class="formulario__contenedor formulario__contenedor-registro">
    <?php 
        include_once __DIR__ . '../../templates/alertas.php';
    ?>
    <form method="POST" class="form form__registro">
        <h3>Registro</h3>

            <div class="form__camp">
                <label for="nombre" class="form__label">Nombre:</label>
                <input 
                type="text" 
                class="form__input form__input-login"
                name="nombre"
                id="nombre"
                placeholder="Ingrese su primer nombre"
                required
                />
            </div>

            <div class="form__camp">
                <label for="apellido" class="form__label">Apellido:</label>
                <input 
                type="text" 
                class="form__input form__input-login"
                name="apellido"
                id="apellido"
                placeholder="Ingrese su primer apellido"
                required
                />
            </div>

            <div class="form__camp">
                <label for="email" class="form__label">Email:</label>
                <input 
                type="email" 
                class="form__input form__input-login"
                name="email"
                id="email"
                placeholder="Ingrese su correo"
                required
                />
            </div>
            <div class="form__camp">
                <label for="password" class="form__label">Contraseña:</label>
                <input 
                type="password" 
                class="form__input form__input-login"
                name="password"
                id="password"
                placeholder="Ingrese su contraseña"
                required
                />
            </div>
            <div class="form__camp">
                <label for="password2" class="form__label">Confirmar contraseña:</label>
                <input 
                type="password" 
                class="form__input form__input-login"
                name="password2"
                id="password2"
                placeholder="Ingrese su contraseña nuevamente"
                required
                />
            </div>

        <button type="submit" class="form__submit">Registrarme</button>
    </form>
</div>
</main>