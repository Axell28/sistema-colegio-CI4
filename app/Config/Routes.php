<?php

namespace Config;

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('MainController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/', 'MainController::index');

$routes->get('ver-perfil', 'MainController::verPerfil');

$routes->group('auth', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->addRedirect('', 'auth/login');
    $routes->get('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
    $routes->post('authenticate', 'AuthController::authenticate');
});

$routes->group('academico', ['namespace' => 'App\Controllers\Academico', 'filter' => 'Auth_filter'], static function ($routes) {

    $routes->get('', 'IndexController::index');

    $routes->group('mantenimiento-alumno', static function ($routes) {
        $routes->get('', 'MantenimientoAlumnoController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'MantenimientoAlumnoController::json/$1');
    });

    $routes->group('mantenimiento-familia', static function ($routes) {
        $routes->get('', 'MantenimientoFamiliaController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'MantenimientoFamiliaController::json/$1');
    });

    $routes->group('mantenimiento-empleado', static function ($routes) {
        $routes->get('', 'MantenimientoEmpleadoController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'MantenimientoEmpleadoController::json/$1');
    });

    $routes->group('nivel-academico', static function ($routes) {
        $routes->get('', 'NivelAcademicoController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'NivelAcademicoController::json/$1');
    });

    $routes->group('cursos', static function ($routes) {
        $routes->get('', 'CursosController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'CursosController::json/$1');
    });

    $routes->group('anio-academico', static function ($routes) {
        $routes->get('', 'AnioController::index');
        $routes->post('periodo', 'AnioController::periodo');
        $routes->match(['get', 'post'], 'json/(:any)', 'AnioController::json/$1');
    });

    $routes->group('matricula', static function ($routes) {
        $routes->get('', 'MatriculaController::index');
        $routes->get('registro', 'MatriculaController::registro');
        $routes->match(['get', 'post'], 'json/(:any)', 'MatriculaController::json/$1');
    });

    $routes->group('salones', static function ($routes) {
        $routes->get('', 'SalonesController::index');
        $routes->post('registro', 'SalonesController::registro');
        $routes->match(['get', 'post'], 'json/(:any)', 'SalonesController::json/$1');
    });

    $routes->group('plan-curricular', static function ($routes) {
        $routes->get('', 'CurriculaController::index');
        $routes->post('asignacion', 'CurriculaController::asignacion');
        $routes->match(['get', 'post'], 'json/(:any)', 'CurriculaController::json/$1');
    });

    $routes->group('asignacion-curso', static function ($routes) {
        $routes->get('', 'AsignacionCursoController::index');
        $routes->post('asignacion', 'AsignacionCursoController::asignacion');
        $routes->match(['get', 'post'], 'json/(:any)', 'AsignacionCursoController::json/$1');
    });

    $routes->group('reporte', static function ($routes) {
        $routes->post('generate', 'ReporteController::generate');
    });

    $routes->group('registro-documentos', static function ($routes) {
        $routes->get('', 'RegistroDocumentosController::index');
        $routes->post('documento', 'RegistroDocumentosController::documento');
        $routes->match(['get', 'post'], 'json/(:any)', 'RegistroDocumentosController::json/$1');
    });
});

$routes->group('intranet', ['namespace' => 'App\Controllers\Intranet', 'filter' => 'Auth_filter'], static function ($routes) {
    $routes->get('', 'IndexController::index');
    $routes->get('aula-virtual', 'AulaVirtualController::index');
    $routes->get('documentos', 'DocumentosController::index');

    $routes->group('publicaciones', static function ($routes) {
        $routes->get('', 'PublicacionesController::index');
        $routes->get('editor', 'PublicacionesController::editor');
        $routes->get('editor/(:num)', 'PublicacionesController::editor/$1');
        $routes->match(['get', 'post'], 'json/(:any)', 'PublicacionesController::json/$1');
    });

    $routes->group('calificaciones', static function ($routes) {
        $routes->get('', 'CalificacionesController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'CalificacionesController::json/$1');
    });

    $routes->group('cursos', static function ($routes) {
        $routes->get('', 'CursosController::index');
        $routes->post('auv-grupo', 'CursosController::auvgrupo');
        $routes->post('auv-grupo-items', 'CursosController::auvGrupoItems');
        $routes->post('auv-grupo-editor', 'CursosController::auvEditor');
        $routes->get('classroom/(:alphanum)/(:alphanum)', 'CursosController::classroom/$1/$2');
        $routes->match(['get', 'post'], 'json/(:any)', 'CursosController::json/$1');
    });
});

$routes->group('configuracion', ['namespace' => 'App\Controllers\Configuracion', 'filter' => 'Auth_filter'], static function ($routes) {
    $routes->get('', 'IndexController::index');

    $routes->group('reporte', ['namespace' => 'App\Controllers\Academico'], static function ($routes) {
        $routes->post('generate', 'ReporteController::generate');
    });

    $routes->group('mantenimiento-usuario', static function ($routes) {
        $routes->get('', 'MantenimientoUsuarioController::index');
        $routes->post('usuario', 'MantenimientoUsuarioController::usuario');
        $routes->match(['get', 'post'], 'json/(:any)', 'MantenimientoUsuarioController::json/$1');
    });

    $routes->group('perfiles', static function ($routes) {
        $routes->get('', 'PerfilesController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'PerfilesController::json/$1');
    });

    $routes->group('institucion', static function ($routes) {
        $routes->get('', 'InstitucionController::index');
        $routes->match(['get', 'post'], 'json/(:any)', 'InstitucionController::json/$1');
    });
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
