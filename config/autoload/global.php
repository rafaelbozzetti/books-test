<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    // Service Manager    
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=tallerbooks;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    // Acl's
    'acl' => array(
        // Definição de Roles da aplicação
        'roles' => array(
            'guest'   => null,
            'admin' => 'guest'
        ),
        // Declaração de recursos acessiveis
        'resources' => array(
            'Books\Controller\Index.index',
            'Books\Controller\Index.list',
            'Books\Controller\Index.add',
            'Books\Controller\Index.edit',
            'Books\Controller\Index.delete',
            'Admin\Controller\Auth.index',
            'Admin\Controller\Auth.logout',
        ),
        // Acl
        'privilege' => array(
            // usuários não logados
            'guest' => array(
                'allow' => array(                    
                    'Admin\Controller\Auth.index',
                    'Admin\Controller\Auth.logout',
                )
            ),
            // usuários logados
            'admin' => array(
                'allow' => array(
                    'Books\Controller\Index.index',
                    'Books\Controller\Index.list',
                    'Books\Controller\Index.add',
                    'Books\Controller\Index.edit',
                    'Books\Controller\Index.delete',                    
                )
            ),
        )
    )
);