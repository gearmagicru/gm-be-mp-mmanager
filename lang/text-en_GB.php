<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Module Manager',
    '{description}' => 'Management of system modules',
    '{permissions}' => [
        'any'       => ['Full access', 'View and make changes to system modules'],
        'view'      => ['View', 'View modules'],
        'read'      => ['Read', 'Reading modules'],
        'install'   => ['Install', 'Installing modules'],
        'uninstall' => ['Uninstall', 'Uninstalling modules'],
    ],

    // Grid: панель инструментов
    'Edit record' => 'Edit record',
    'Update' => 'Update',
    'Update configurations of installed modules' => 'Update configurations of installed modules',
    'Module enabled' => 'Module enabled',
    'Module visible' => 'Module visible',
    'You need to select a module' => 'You need to select a module',
    'Download' => 'Download',
    'Downloads module package file' => 'Downloads module package file',
    'Upload' => 'Upload',
    'Uploads module package file' => 'Uploads module package file',
    // Grid: панель инструментов / Установить (install)
    'Install' => 'Install',
    'Module install' => 'Module install',
    // Grid: панель инструментов / Удалить (uninstall)
    'Uninstall' => 'Uninstall',
    'Completely delete an installed module' => 'Completely delete an installed module',
    'Are you sure you want to completely delete the installed module?' => 'Are you sure you want to completely delete the installed module?',
    // Grid: панель инструментов / Удалить (delete)
    'Delete' => 'Delete',
    'Delete an uninstalled module from the repository' => 'Delete an uninstalled module from the repository',
    'Are you sure you want to delete the uninstalled module from the repository?' => 'Are you sure you want to delete the uninstalled module from the repository?',
    // Grid: панель инструментов / Демонтаж (unmount)
    'Unmount' => 'Unmount',
    'Delete an installed module without removing it from the repository' => 'Delete an installed module without removing it from the repository',
    'Are you sure you want to remove the installed module without removing it from the repository?' 
        => 'Are you sure you want to remove the installed module without removing it from the repository?',
    // Grid: фильтр
    'All' => 'All',
    'Installed' => 'Installed',
    'None installed' => 'None installed',
    // Grid: поля
    'Name' => 'Name',
    'Module id' => 'Module id',
    'Record id' => 'Record id',
    'Path' => 'Path',
    'Enabled' => 'Enabled',
    'Visible' => 'Visible',
    'Package' => 'Package',
    'Route' => 'Route',
    'Author' => 'Author',
    'Version' => 'Version',
    'from' => 'from',
    'Description' => 'Description',
    'Resource' => 'Resource',
    'Use' => 'Use',
    'Date' => 'Date',
    'Go to module' => 'Go to module',
    'Module settings' => 'Module settings',
    'Module info' => 'Module info',
    'For append item menu' => 'For append item menu',
    'Status' => 'Status',
    // Grid: значения
    FRONTEND => 'Site',
    BACKEND => 'Control panel',
    'Yes' => 'yes',
    'No' => 'no',
    'installed' => 'installed',
    'not installed' => 'not installed',
    'broken' => 'broken',
    'unknow' => 'unknow',
    // Grid: всплывающие сообщения / заголовок
    'Show' => 'Show',
    'Hide' => 'Hide',
    'Enabled' => 'Enabled',
    'Disabled' => 'Disabled',
    'Installing' => 'Installing',
    'Updating' => 'Updating',
    'Unmounting' => 'Unmounting',
    'Uninstalling' => 'Uninstalling',
    'Deleting' => 'Deleting',
    'Downloading' => 'Downloading',
    // Grid: всплывающие сообщения / текст
    'Module {0} - hide' => 'Module "<b>{0}</b>" - <b>hide</b>.',
    'Module {0} - show' => 'Module"<b>{0}</b>" - <b>show</b>.',
    'Module {0} - enabled' => 'Module "<b>{0}</b>" - <b>enabled</b>.',
    'Module {0} - disabled' => 'Module "<b>{0}</b>" - <disabled>отключен</b>.',
    'Modules configuration files are updated' => 'Modules configuration files are updated!',
    'Updating modules' => 'Updating modules',
    'Module installation "{0}" completed successfully' => 'Module installation "{0}" completed successfully.',
    'Update of module "{0}" completed successfully' => 'Update of module "{0}" completed successfully.',
    'Unmounting of module "{0}" completed successfully' => 'Unmounting of module "{0}" completed successfully.',
    'Uninstalling of module "{0}" completed successfully' => 'Uninstalling of module "{0}" completed successfully.',
    'Deleting of module completed successfully' => 'Deleting of module completed successfully.',
    'The module package will now be loaded' => 'The module package will now be loaded.',
    // Grid: сообщения (ошибки)
    'Module installation configuration file is missing' => 'Module installation configuration file is missing (.install.php).',
    'It is not possible to remove the module from the repository because it\'s installed' 
        => 'It is not possible to remove the module from the repository because it\'s installed.',
    // Grid: аудит записей
    'module {0} with id {1} is hidden' => 'module "<b>{0}</b>" with id "<b>{1}</b>" is hidden',
    'module {0} with id {1} is shown' => 'module "<b>{0}</b>" with id "<b>{1}</b>" is shown',
    'module {0} with id {1} is enabled' => 'module "<b>{0}</b>" with id "<b>{1}</b>" is enabled',
    'module {0} with id {1} is disabled' => 'module "<b>{0}</b>" with id "<b>{1}</b>" is disabled',

    // Form
    '{form.title}' => 'Module editing "{title}"',
    '{form.subtitle}' => 'Editing basic module settings',
    // Form: поля
    'Identifier' => 'Identifier',
    'Record identifier' => 'Record identifier',
    'Default' => 'Default',
    'enabled' => 'enabled',
    'visible' => 'visible',

    // Upload
    '{upload.title}' => 'Loading module package file',
    // Upload: панель инструментов
    'Upload' => 'Upload',
    // Upload: поля
    'File name' => 'File name',
    '(more details)' => '(more details)',
    'The file(s) will be downloaded according to the parameters for downloading resources to the server {0}' 
        => 'The file(s) will be downloaded according to the parameters for downloading resources to the server. File extension only ".gpk". {0}',
    // Upload: всплывающие сообщения / заголовок
    'Uploading a file' => 'Uploading a file',
    // Upload: сообщения
    'File uploading error' => 'Error loading module package file.',
    'Error creating temporary directory to download module package file' 
        => 'Error creating temporary directory to download module package file.',
    'File uploaded successfully' => 'File uploaded successfully.',
    'The module package file does not contain one of the attributes: id, type' 
        => 'The module package file does not contain one of the attributes: id, type.',
    'Module attribute "{0}" is incorrectly specified' => 'Module attribute "{0}" is incorrectly specified.',
    'You already have the module "{0}" installed. Please remove it and try again' 
        => 'You already have the module "{0}" installed. Please remove it and try again.',
    'You already have a module with files installed: {0}' 
        => 'You already have a module with files installed: <br><br>{0}<br>...',

    // ShortcodeSettings: сообщения (ошибки)
    'Unable to show module shortcode settings' => 'Unable to show module shortcode settings.'
];
