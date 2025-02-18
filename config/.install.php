<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки расширения.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'priority'    => 1,
    'id'          => 'gm.be.mp.mmanager',
    'moduleId'    => 'gm.be.mp',
    'name'        => 'Module Manager',
    'description' => 'Management of system modules',
    'namespace'   => 'Gm\Backend\Marketplace\ModuleManager',
    'path'        => '/gm/gm.be.mp.mmanager',
    'route'       => 'mmanager',
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'install', 'uninstall', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
        ['module', 'id' => 'gm.be.mp']
    ]
];
