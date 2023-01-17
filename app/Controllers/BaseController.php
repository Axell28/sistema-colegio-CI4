<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
        if (session()->get('loggedIn')) {
            defined('ANIO')        ||  define('ANIO', session()->get('anio'));
            defined('USUARIO')     ||  define('USUARIO', session()->get('usuario'));
            defined('PERFIL')      ||  define('PERFIL', session()->get('perfil'));
            defined('CODIGO')      ||  define('CODIGO', session()->get('codigo'));
            defined('ENTIDAD')     ||  define('ENTIDAD', session()->get('entidad'));
            defined('MODULO')      ||  define('MODULO', session()->get('modulo'));
            defined('MODULO_URL')  ||  define('MODULO_URL', session()->get('modulo_url'));
            defined('MODULO_NAME') ||  define('MODULO_NAME', session()->get('modulo_name'));
            defined('SUPER_ADMIN') ||  define('SUPER_ADMIN', session()->get('supadm'));
        }
    }
}
