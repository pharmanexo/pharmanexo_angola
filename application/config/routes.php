<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';

$route['quem-somos'] = 'marketplace/informativos/quem_somos';
$route['politica-de-seguranca'] = 'marketplace/informativos/seguranca';
$route['termos-de-uso'] = 'marketplace/informativos/termos_uso';
$route['fale-conosco'] = 'marketplace/informativos/fale_conosco';
$route['linhas/hospitalar'] = 'marketplace/catalogo';
$route['Pedido'] = 'marketplace/pedidos';
$route['busca-ativa'] = 'marketplace/busca_ativa';
$route['faq'] = 'marketplace/FAQ';
$route['integracoes'] = 'admin/relatorios/integracoes';
$route['mix'] = 'admin/relatorios/mix';
$route['home'] = 'home';
$route['adesao'] = 'adesao';
$route['web'] = 'home';

$route['fornecedor/estoque/produtos_vencer/(:num)/(:num)'] = 'fornecedor/estoque/produtos_vencer/index/$1/$2';


$route['admin/fornecedores'] = 'admin/fornecedores/fornecedores';
$route['admin/configuracoes'] = 'admin/configuracoes/configuracoes';
$route['admin/representantes'] = 'admin/representantes/representantes';
$route['admin/notificacoes'] = 'admin/notificacoes/notificacoes';
$route['fornecedor/notificacoes'] = 'fornecedor/notificacoes/notificacoes';

$route['404_override'] = 'errors/error_404';
$route['translate_uri_dashes'] = FALSE;
