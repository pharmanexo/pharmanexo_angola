<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    'username' => 'adm_pharmanexo',
    'password' => 'Pisdnm238shdn',
    'database' => 'pharmanexo',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => false,
    // 'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['sintese'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    'username' => 'adm_pharmanexo',
    'password' => 'Pisdnm238shdn',
    'database' => 'cotacoes_sintese',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    //'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['cotacoes'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    'username' => 'adm_pharmanexo',
    'password' => 'Pisdnm238shdn',
    'database' => 'cotacoes_sintese',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    //'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['bionexo'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    #'username' => 'pharmanexo',
    'username' => 'adm_pharmanexo',
    #'password' => '@PharmanexoNext',
    'password' => 'Pisdnm238shdn',
    'database' => 'cotacoes_bionexo',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['apoio'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    #'username' => 'pharmanexo',
    'username' => 'adm_pharmanexo',
    #'password' => '@PharmanexoNext',
    'password' => 'Pisdnm238shdn',
    'database' => 'cotacoes_apoio',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['mix'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.5',
    #'username' => 'pharmanexo',
    'username' => 'adm_pharmanexo',
    #'password' => '@PharmanexoNext',
    'password' => 'Pisdnm238shdn',
    'database' => 'mix',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['teste_pharmanexo'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.4',
    #'username' => 'pharmanexo',
    'username' => 'adm_pharmanexo',
    #'password' => '@PharmanexoNext',
    'password' => 'f}*=p8S1Bo4OnK',
    'database' => 'pharmanexo',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => false,
    // 'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['pharmanexo-antigo'] = array(
	'dsn'	=> '',
    'hostname' => '10.101.70.2',
    #'username' => 'pharmanexo',
    'username' => 'suporte',
    #'password' => '@PharmanexoNext',
    'password' => 'Pharma_TI_2019',
    'database' => 'pharmanexo',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['pharmahmg-antigo'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.2',
    #'username' => 'pharmanexo',
    'username' => 'suporte',
    #'password' => '@PharmanexoNext',
    'password' => 'Pharma_TI_2019',
    'database' => 'pharmanexohmg',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['cotacoes-antigo'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.2',
    #'username' => 'pharmanexo',
    'username' => 'suporte',
    #'password' => '@PharmanexoNext',
    'password' => 'Pharma_TI_2019',
    'database' => 'cotacoes_sintese',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['sintese-antigo'] = array(
    'dsn'	=> '',
    'hostname' => '10.101.70.2',
    #'username' => 'pharmanexo',
    'username' => 'suporte',
    #'password' => '@PharmanexoNext',
    'password' => 'Pharma_TI_2019',
    // 'database' => 'cotacoes-chule',
    'database' => 'cotacoes_sintese',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);