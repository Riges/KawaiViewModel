<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;

$serviceContainer = new ContainerBuilder();

$serviceContainer
    ->register('database', 'Zend_Db_Adapter_Mysqli')
    ->addArgument($g_options['mysql']['user'])
    ->addMethodCall('setFetchMode', array(Zend_Db::FETCH_OBJ))
    ->addMethodCall('query', array("SET NAMES utf8"))
;

$serviceContainer->register('connectedUser', 'Knb_ConnectedUser')
    ->addArgument($serviceContainer->get('database_user'));