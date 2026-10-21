<?php 

// Ocultar advertencias de código obsoleto (Deprecated)
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

if(file_exists(__DIR__ . '/../includes/app.php')){
    require_once __DIR__ . '/../includes/app.php';
}else{
    require_once __DIR__ . '/../repositories/Fadm_nomina/includes/app.php';
}


use Controllers\AdminController;
use Controllers\APICargoController;
use Controllers\APIDeduccionController;
use Controllers\APIDepartamentoController;
use Controllers\APIFeriadoController;
use Controllers\APIVacaciones;
use Controllers\BonoPdfController;
use Controllers\BonosController;
use Controllers\CestaticketController;
use Controllers\DashboardController;
use Controllers\EmpleadosController;
use Controllers\EmpresasController;
use Controllers\NominaController;
use Controllers\PaginasController;
use Controllers\PDFController;
use Controllers\TicketPdfController;
use Controllers\VacacionesController;
use Controllers\VacacionesPdfController;
use MVC\Router;


$router = new Router();


// Login
$router->get('/', [PaginasController::class, 'index']);
$router->get('/logout', [AdminController::class, 'logout']);
$router->get('/login-admin', [AdminController::class, 'login']);
$router->post('/login-admin', [AdminController::class, 'login']);
$router->get('/registro-admin', [AdminController::class, 'registro']);
$router->post('/registro-admin', [AdminController::class, 'registro']);


// PANEL
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/404', [PaginasController::class, 'error']);

// Cestaticket

$router->get('/admin/cestaticket/empresa', [CestaticketController::class, 'empresa']);
$router->get('/admin/cestaticket/crear', [CestaticketController::class, 'crear']);
$router->post('/admin/cestaticket/crear', [CestaticketController::class, 'crear']);
$router->post('/admin/cestaticket/eliminar', [CestaticketController::class, 'eliminar']);

// Nomina

$router->get('/admin/nomina/empresa', [NominaController::class, 'empresa']);
$router->get('/admin/nomina-variable/crear', [NominaController::class, 'crearVariable']);
$router->post('/admin/nomina-variable/crear', [NominaController::class, 'crearVariable']);
$router->get('/admin/nomina/crear', [NominaController::class, 'crear']);
$router->post('/admin/nomina/crear', [NominaController::class, 'crear']);
$router->post('/admin/nomina/eliminar', [NominaController::class, 'eliminar']);

// Bonos

$router->get('/admin/bonos/empresa', [BonosController::class, 'empresa']);
$router->get('/admin/bonos/detalle', [BonosController::class, 'detalle']);
$router->get('/admin/bonos/crear', [BonosController::class, 'crear']);
$router->post('/admin/bonos/crear', [BonosController::class, 'crear']);
$router->post('/admin/bonos/eliminar', [BonosController::class, 'eliminar']);

// Vacaciones
$router->get('/admin/vacaciones', [VacacionesController::class, 'index']);
$router->get('/admin/vacaciones/crear', [VacacionesController::class, 'crear']);
$router->post('/admin/vacaciones/crear', [VacacionesController::class, 'crear']);
$router->post('/admin/vacaciones/eliminar', [VacacionesController::class, 'eliminar']);
$router->get('/admin/vacaciones/crear-personalizado', [VacacionesController::class, 'personalizado']);
$router->post('/admin/vacaciones/crear-personalizado', [VacacionesController::class, 'personalizado']);


// Empresas
$router->get('/admin/empresas', [EmpresasController::class, 'index']);
$router->get('/admin/empresas/crear', [EmpresasController::class, 'crear']);
$router->post('/admin/empresas/crear', [EmpresasController::class, 'crear']);
$router->get('/admin/empresas/editar', [EmpresasController::class, 'editar']);
$router->post('/admin/empresas/editar', [EmpresasController::class, 'editar']);
$router->post('/admin/empresas/eliminar', [EmpresasController::class, 'eliminar']);
$router->get('/admin/empresas/detalle', [EmpresasController::class, 'detalle']);


// Empleados
$router->get('/admin/empleados', [EmpleadosController::class, 'index']);
$router->get('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->post('/admin/empleados/crear', [EmpleadosController::class, 'crear']);
$router->get('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/editar', [EmpleadosController::class, 'editar']);
$router->post('/admin/empleados/eliminar', [EmpleadosController::class, 'eliminar']);

// APIS
$router->post('/api/feriado/crear', [APIFeriadoController::class, 'crear']);
$router->get('/api/feriado/crear', [APIFeriadoController::class, 'crear']);
$router->post('/api/cargo/crear', [APICargoController::class, 'crear']);
$router->post('/api/departamento/crear', [APIDepartamentoController::class, 'crear']);

$router->post('/api/vacaciones/crear', [APIVacaciones::class, 'crear']);
$router->get('/api/vacaciones/crear', [APIVacaciones::class, 'crear']);
$router->get('/api/empleado-vacaciones', [APIVacaciones::class, 'obtenerDiasEmpleado']);

$router->post('/api/deducciones/crear', [APIDeduccionController::class, 'crear']);

$router->post('/api/bonos/editar', [BonosController::class, 'editar']);

// PDF
$router->get('/admin/nomina/empresa/reporte', [PDFController::class, 'generarPdf']);
$router->get('/admin/nomina/empresa/reporte-recibos', [PDFController::class, 'reciboPdf']);
$router->get('/admin/nomina/empresa/reporte-resumen', [PDFController::class, 'resumenPdf']);
$router->get('/admin/nomina/empresa/reporte-banco', [PDFController::class, 'bancoNominaPdf']);

$router->get('/admin/cestaticket/empresa/reporte', [TicketPdfController::class, 'reporteGeneral']);
$router->get('/admin/cestaticket/empresa/reporte-recibos', [TicketPdfController::class, 'recibos']);
$router->get('/admin/cestaticket/empresa/reporte-banco', [TicketPdfController::class, 'banco']);


$router->get('/admin/bonos/empresa/listado-general', [BonoPdfController::class, 'listadoPeriodo']);
$router->get('/admin/bonos/recibo', [BonoPdfController::class, 'recibo']);
$router->get('/admin/bonos/banco', [BonoPdfController::class, 'banco']);


$router->get('/admin/vacaciones/reporte', [VacacionesPdfController::class, 'reporte']);


$router->comprobarRutas();