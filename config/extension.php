<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации расширения.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'text-%s.php'
            ],
            // установка модуля
            'install' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'install-%s.php'
            ],
            // обновление модуля
            'update' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'update-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей панели управления
        [ // разрешение "Полный доступ" (any: view, read, install, uninstall)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Grid'     => ['data', 'view', 'update', 'filter'],
                'Form'     => ['data', 'view', 'update'],
                'Install'  => ['complete', 'view'],
                'Update'   => ['complete', 'view'],
                'Download' => ['index', 'file'],
                'Upload'   => ['view', 'perfom'],
                'Module'   => ['unmount', 'uninstall', 'update', 'delete'],
                'Trigger'  => ['combo'],
                'Search'   => ['data', 'view'],
                'ShortcodeSettings' => ['view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Grid'    => ['data', 'view', 'filter'],
                'Form'    => ['data', 'view'],
                'Trigger' => ['combo'],
                'Search'  => ['data', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Grid'    => ['data'],
                'Form'    => ['data'],
                'Search'  => ['data'],
                'Trigger' => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Установка, обновление" (install)
            'allow',
            'permission'  => 'install',
            'controllers' => [
                'Install' => ['complete', 'view'],
                'Update'  => ['complete', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление, демонтаж" (uninstall)
            'allow',
            'permission'  => 'uninstall',
            'controllers' => [
                'Module' => ['unmount', 'uninstall', 'delete']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о расширении" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-mp-mmanager-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информация о расширении
            'info' => [
                'viewFile'      => '//backend/extension-info.phtml', 
                'forceLocalize' => true
            ],
            'form'      => '/form.json',
            'form-lock' => '/form-lock.json'
        ]
    ]
];
