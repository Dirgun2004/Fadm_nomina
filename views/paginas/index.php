
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
          <a href="#servicios">Servicios</a>
          <a href="#clientes">Clientes</a>
          <a href="#comentarios">Comentarios</a>
          <a href="#nosotros">Nosotros</a>
          <a href="#contacto">Contacto</a>
          <a
            class="landing-btn btn--sm btn--primary nav__cta"
            href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quisiera%20informaci%C3%B3n%20sobre%20sus%20servicios."
            target="_blank"
            rel="noopener"
          >
            Hablar por WhatsApp
          </a>
        </nav>
      </div>
    </header>
    
    <main id="contenido">
      <!-- Hero -->
      <section id="inicio" class="hero">
        <div class="landing-container hero__grid">
          <div class="hero__content">
            <p class="badge">Firma de Contadores en Venezuela • 10+ años</p>
            <h1>
              Contabilidad y asesoría profesional para empresas que necesitan
              control y tranquilidad.
            </h1>
            <p class="lead">
              Gestionamos contabilidad, nómina, beneficios laborales, aportes
              (IVSS/FAOV/INCES) y auditorías, con acompañamiento cercano y
              actualización constante.
            </p>

            <div class="hero__cta">
              <a
                class="landing-btn btn--primary"
                href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quiero%20una%20asesor%C3%ADa%20para%20mi%20empresa."
                target="_blank"
                rel="noopener"
              >
                Solicitar asesoría por WhatsApp
              </a>
              <a class="landing-btn btn--ghost" href="#servicios">Ver servicios</a>
            </div>

            <ul class="hero__trust">
              <li><span class="dot"></span> Discreción & cumplimiento</li>
              <li><span class="dot"></span> Procesos claros</li>
              <li>
                <span class="dot"></span> Atención a pequeñas y grandes empresas
              </li>
            </ul>
          </div>

          <div class="hero__card" role="region" aria-label="Resumen rápido">
            <div class="hero__cardTop">
              <h2>Servicios principales</h2>
              <p>Selecciona un área y te guiamos.</p>
            </div>

            <div class="hero__quick">
              <a class="quick" href="#beneficios">
                <img
                  src="build/img/icons/beneficios.svg"
                  alt=""
                  aria-hidden="true"
                />
                <span>Beneficios laborales</span>
              </a>
              <a class="quick" href="#aportes">
                <img src="build/img/icons/aportes.svg" alt="" aria-hidden="true" />
                <span>Declaraciones y aportes</span>
              </a>
              <a class="quick" href="#personal">
                <img
                  src="build/img/icons/personal.svg"
                  alt=""
                  aria-hidden="true"
                />
                <span>Gestión de personal</span>
              </a>
              <a class="quick" href="#contables">
                <img
                  src="build/img/icons/contables.svg"
                  alt=""
                  aria-hidden="true"
                />
                <span>Servicios contables</span>
              </a>
              <a class="quick" href="#otros">
                <img src="build/img/icons/otros.svg" alt="" aria-hidden="true" />
                <span>Otros servicios</span>
              </a>
            </div>

            <div class="hero__mini">
              <div class="mini">
                <span class="mini__kpi">10+</span>
                <span class="mini__label">años de experiencia</span>
              </div>
              <div class="mini">
                <span class="mini__kpi">1:1</span>
                <span class="mini__label">asesoría cercana</span>
              </div>
              <div class="mini">
                <span class="mini__kpi">✔</span>
                <span class="mini__label">procesos ordenados</span>
              </div>
            </div>
          </div>
        </div>
        <div class="hero__wave" aria-hidden="true"></div>
      </section>

      <!-- Services -->
      <section id="servicios" class="section">
        <div class="landing-container">
          <header class="section__head">
            <h2>Servicios</h2>
            <p>
              Organizamos tu operación contable y laboral con un flujo claro y
              entregables listos.
            </p>
          </header>

          <div class="grid-cards">
            <article id="beneficios" class="card">
              <div class="card__icon">
                <img
                  src="build/img/icons/beneficios.svg"
                  alt=""
                  aria-hidden="true"
                />
              </div>
              <h3>Beneficios Laborales</h3>
              <ul class="list">
                <li>Vacaciones</li>
                <li>Utilidades</li>
                <li>Prestaciones Sociales</li>
                <li>Anticipos y Liquidaciones</li>
                <li>Intereses sobre Prestaciones</li>
              </ul>
            </article>

            <article id="aportes" class="card">
              <div class="card__icon">
                <img src="build/img/icons/aportes.svg" alt="" aria-hidden="true" />
              </div>
              <h3>Declaraciones y Aportes</h3>
              <ul class="list">
                <li>IVSS • FAOV • INCES</li>
                <li>MPPPST • F-99019</li>
              </ul>
              <p class="muted">
                Te ayudamos a mantener tus obligaciones al día con soporte
                profesional.
              </p>
            </article>

            <article id="personal" class="card">
              <div class="card__icon">
                <img
                  src="build/img/icons/personal.svg"
                  alt=""
                  aria-hidden="true"
                />
              </div>
              <h3>Gestión de Personal</h3>
              <ul class="list">
                <li>Ingreso / Egreso de trabajadores</li>
                <li>Cálculo de Nómina</li>
                <li>Recibos de Pago</li>
              </ul>
            </article>

            <article id="contables" class="card">
              <div class="card__icon">
                <img
                  src="build/img/icons/contables.svg"
                  alt=""
                  aria-hidden="true"
                />
              </div>
              <h3>Servicios Contables</h3>
              <ul class="list">
                <li>Contribuyentes Ordinarios</li>
                <li>Contribuyentes Especiales</li>
                <li>Auditorías Contables</li>
              </ul>
            </article>

            <article id="otros" class="card card--wide">
              <div class="card__icon">
                <img src="build/img/icons/otros.svg" alt="" aria-hidden="true" />
              </div>
              <div>
                <h3>Otros Servicios</h3>
                <ul class="list list--cols">
                  <li>Inscripciones: IVSS, RIF, RUPDAE, FAOV, INCES</li>
                  <li>Constitución de empresa</li>
                  <li>Foliación de libros contables</li>
                  <li>Recuperación de usuarios SENIAT / RNC</li>
                </ul>
              </div>
            </article>
          </div>

          <div class="cta-strip">
            <div>
              <h3>¿Necesitas una ruta clara para ponerte al día?</h3>
              <p>Escríbenos y te indicamos los pasos según tu caso.</p>
            </div>
            <a
              class="landing-btn btn--primary"
              href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quiero%20una%20evaluaci%C3%B3n%20de%20mi%20caso."
              target="_blank"
              rel="noopener"
            >
              Evaluar por WhatsApp
            </a>
          </div>
        </div>
      </section>

      <!-- Clients -->
      <section id="clientes" class="section section--alt">
        <div class="landing-container">
          <header class="section__head">
            <h2>Clientes</h2>
            <p>
              Acompañamos a organizaciones de distintos sectores, brindando
              control, cumplimiento y asesoría contable profesional.
            </p>
          </header>

          <div class="clients-grid" aria-label="Logos de clientes">
            <div class="client-tile">
              <img
                src="build/img/clients/KDA.svg"
                alt="Logo KDA"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/camacho.svg"
                alt="Logo camacho"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/rijoparts.svg"
                alt="Logo rijoparts"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/invsuma.png"
                alt="Logo Inv Suma"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/SuministroAlimentos.svg"
                alt="Logo Suministro Alimentos"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/invicta.svg"
                alt="Logo Invicta"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/seikon.svg"
                alt="Logo Seikon"
                loading="lazy"
              />
            </div>
            <div class="client-tile">
              <img
                src="build/img/clients/bin.svg"
                alt="Logo Bin 45"
                loading="lazy"
              />
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials -->
      <section id="comentarios" class="section">
        <div class="landing-container">
          <header class="section__head">
            <h2>Comentarios</h2>
            <p>
              Lo que dicen nuestros clientes sobre nuestro trabajo Comentarios
              reales que reflejan nuestro compromiso y dedicación.
            </p>
          </header>

          <div class="testimonials">
            <article class="tcard">
              <img
                class="tcard__avatar"
                src="build/img/testimonials/persona-2.png"
                alt="Foto de cliente 1"
                loading="lazy"
              />
              <div class="tcard__body">
                <h3 class="tcard__title">Profesionales</h3>
                <p class="tcard__text">
                  Son los mejores, su profesionalismo y ética son impecables!
                </p>
                <p class="tcard__meta">
                  <strong>Yenifer Escalona</strong> • Multiservicios Escalona /
                  Gte. Administrativo
                </p>
              </div>
            </article>

            <article class="tcard">
              <img
                class="tcard__avatar"
                src="build/img/testimonials/persona-1.png"
                alt="Foto de cliente 2"
                loading="lazy"
              />
              <div class="tcard__body">
                <h3 class="tcard__title">Trabajo impecable</h3>
                <p class="tcard__text">
                  Excelentes profesionales que hacen un trabajo impecable,
                  manteniendo todo al día en tu empresa y lo personal. No dudes
                  en Contactarlos.
                </p>
                <p class="tcard__meta">
                  <strong>Pedro Farias</strong> • Seikon 777 / Ceo
                </p>
              </div>
            </article>
            <article class="tcard">
              <img
                class="tcard__avatar"
                src="build/img/testimonials/persona-4.png"
                alt="Foto de cliente 1"
                loading="lazy"
              />
              <div class="tcard__body">
                <h3 class="tcard__title">Recomendados</h3>
                <p class="tcard__text">
                  Los mejores en asesoría contable, recomendados totalmente.
                </p>
                <p class="tcard__meta">
                  <strong>Yessenia Nuñez</strong> • Super cauchos invicta /
                  Administradora
                </p>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- About -->
      <section id="nosotros" class="section section--alt">
        <div class="landing-container about">
          <div class="about__text">
            <h2>Sobre FADM Asesores</h2>
            <p>
              Somos una firma de contadores con más de 10 años en el mercado
              contable venezolano, comprometidos con la discreción, el orden y
              la asesoría profesional para empresas de todos los tamaños.
            </p>
            <div class="about__pill">
              <span class="pill"
                >CEO: <strong>Fay Duran</strong> — Contadora Pública</span
              >
              <span class="pill"
                >Atención: <strong>WhatsApp</strong> y asesorías</span
              >
            </div>

            <ul class="checks">
              <li>Enfoque en control y cumplimiento</li>
              <li>Documentación y entregables claros</li>
              <li>Actualización constante</li>
            </ul>
          </div>

          <div class="about__card">
            <h3>¿Qué recibes al trabajar con nosotros?</h3>
            <ul class="list">
              <li>Diagnóstico inicial y ruta de trabajo</li>
              <li>Gestión y soporte en trámites y obligaciones</li>
              <li>Seguimiento y recomendaciones</li>
            </ul>
            <a
              class="landing-btn btn--primary btn--block"
              href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quiero%20conocer%20su%20propuesta%20de%20servicio."
              target="_blank"
              rel="noopener"
            >
              Pedir propuesta por WhatsApp
            </a>
          </div>
        </div>
      </section>

      <!-- Contact -->
      <section id="contacto" class="section">
        <div class="landing-container">
          <header class="section__head">
            <h2>Contacto</h2>
            <p>Escríbenos y te orientamos según tu caso.</p>
          </header>

          <div class="contact">
            <div class="contact__card">
              <h3>Canales</h3>
              <ul class="contact__list">
                <li>
                  <span>WhatsApp:</span>
                  <a
                    target="_blank"
                    rel="noopener"
                    href="https://wa.me/584241449361"
                    >+58 424 1449361</a
                  >
                </li>
                <li>
                  <span>Correo:</span>
                  <a href="mailto:fadmasesores@gmail.com"
                    >fadmasesores@gmail.com</a
                  >
                </li>
                <li>
                  <span>Instagram:</span>
                  <a
                    target="_blank"
                    rel="noopener"
                    href="https://www.instagram.com/fadmasesores/"
                    >@fadmasesores</a
                  >
                </li>
                <li>
                  <span>Google Maps:</span>
                  <a
                    target="_blank"
                    rel="noopener"
                    href="https://share.google/LDe5OcIYlJ3KgqXa9"
                    >Ver ubicación</a
                  >
                </li>
              </ul>

              <div class="contact__cta">
                <a
                  class="landing-btn btn--primary btn--block"
                  href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quisiera%20agendar%20una%20asesor%C3%ADa."
                  target="_blank"
                  rel="noopener"
                >
                  Agendar por WhatsApp
                </a>
                <p class="muted">Horario y disponibilidad: L - V 10am - 5pm</p>
              </div>
            </div>

            <div
              class="contact__review"
              role="region"
              aria-label="Dejar comentario"
            >
              <div class="review__placeholder">
                <h3>¿Ya trabajaste con FADM Asesores?</h3>
                <p>
                  Tu reseña ayuda a que más empresas confíen en nuestro
                  servicio. Nos encantaría conocer tu experiencia.
                </p>
                <div
                  class="review__stars"
                  aria-label="Calificación 5 estrellas"
                >
                  <!-- 5 estrellas (SVG inline) -->
                  <svg class="star" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M12 17.27l-5.18 3.04 1.39-5.81L3 9.24l5.9-.5L12 3.25l3.1 5.49 5.9.5-5.21 5.22 1.39 5.81L12 17.27z"
                    />
                  </svg>
                  <svg class="star" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M12 17.27l-5.18 3.04 1.39-5.81L3 9.24l5.9-.5L12 3.25l3.1 5.49 5.9.5-5.21 5.22 1.39 5.81L12 17.27z"
                    />
                  </svg>
                  <svg class="star" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M12 17.27l-5.18 3.04 1.39-5.81L3 9.24l5.9-.5L12 3.25l3.1 5.49 5.9.5-5.21 5.22 1.39 5.81L12 17.27z"
                    />
                  </svg>
                  <svg class="star" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M12 17.27l-5.18 3.04 1.39-5.81L3 9.24l5.9-.5L12 3.25l3.1 5.49 5.9.5-5.21 5.22 1.39 5.81L12 17.27z"
                    />
                  </svg>
                  <svg class="star" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M12 17.27l-5.18 3.04 1.39-5.81L3 9.24l5.9-.5L12 3.25l3.1 5.49 5.9.5-5.21 5.22 1.39 5.81L12 17.27z"
                    />
                  </svg>
                </div>

                <div class="review__actions">
                  <a
                    class="landing-btn btn--primary btn--block review__btn"
                    target="_blank"
                    rel="noopener"
                    href="https://g.page/r/CSWaGLuYLB8cEAE/review"
                  >
                    Dejar reseña en Google
                  </a>

                  <a
                    class="landing-btn btn--ghost btn--block"
                    target="_blank"
                    rel="noopener"
                    href="https://wa.me/584241449361?text=Hola%20FADM%20Asesores,%20quiero%20dejar%20un%20comentario%20sobre%20mi%20experiencia."
                  >
                    Enviar comentario por WhatsApp
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

