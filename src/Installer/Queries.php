<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{module}}', '{{module_locale}}', '{{module_permissions}}'],
    'create' => [
        '{{module}}' => function () {
            return "CREATE TABLE `{{module}}` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `module_id` varchar(100) DEFAULT NULL,
                `module_use` varchar(100) DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `description` varchar(255) DEFAULT NULL,
                `namespace` varchar(255) DEFAULT NULL,
                `path` varchar(255) DEFAULT NULL,
                `route` varchar(255) DEFAULT NULL,
                `route_append` varchar(255) DEFAULT NULL,
                `enabled` int(1) unsigned DEFAULT '1',
                `visible` int(1) unsigned DEFAULT '1',
                `append` tinyint(1) unsigned DEFAULT '0',
                `expandable` tinyint(1) unsigned DEFAULT '0',
                `has_info` tinyint(1) unsigned DEFAULT '0',
                `has_settings` tinyint(1) unsigned DEFAULT '0',
                `permissions` text,
                `version` varchar(50) DEFAULT '1.0',
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },

        '{{module_locale}}' => function () {
            return "CREATE TABLE `{{module_locale}}` (
                `module_id` int(11) unsigned NOT NULL,
                `language_id` int(11) unsigned NOT NULL,
                `name` varchar(255) DEFAULT NULL,
                `description` varchar(255) DEFAULT '',
                `permissions` text,
                PRIMARY KEY (`module_id`,`language_id`),
                KEY `language` (`language_id`),
                KEY `module_and_language` (`module_id`,`language_id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },

        '{{module_permissions}}' => function () {
            return "CREATE TABLE `{{module_permissions}}` (
                `module_id` int(11) unsigned NOT NULL,
                `role_id` int(11) unsigned NOT NULL,
                `permissions` text,
                PRIMARY KEY (`role_id`,`module_id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];