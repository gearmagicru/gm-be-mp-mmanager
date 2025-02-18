<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Менеджер модулей',
    '{description}' => 'Управление модулями системы',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в модули системы'],
        'view'      => ['Просмотр', 'Просмотр модулей'],
        'read'      => ['Чтение', 'Чтение модулей'],
        'install'   => ['Установка', 'Установка модулей'],
        'uninstall' => ['Удаление', 'Удаление и демонтаж модулей']
    ],

    // Grid: панель инструментов
    'Edit record' => 'Редактировать',
    'Update' => 'Обновить',
    'Update configurations of installed modules' => 'Обновление конфигурации установленных модулей',
    'Module enabled' => 'Доступ к модулю',
    'Module visible' => 'Видимость модуля (только для панели управления)',
    'You need to select a module' => 'Вам нужно выбрать модуль',
    'Download' => 'Скачать',
    'Downloads module package file' => 'Скачивает файла пакета модуля',
    'Upload' => 'Загрузить',
    'Uploads module package file' => 'Загружает файл пакета модуля',
    // Grid: панель инструментов / Установить (install)
    'Install' => 'Установить',
    'Module install' => 'Установка модуля',
    // Grid: панель инструментов / Удалить (uninstall)
    'Uninstall' => 'Удалить',
    'Completely delete an installed module' => 'Полностью удаление установленного модуля',
    'Are you sure you want to completely delete the installed module?' => 'Вы уверены, что хотите полностью удалить установленный модуль (все файлы модуля будут удалены)?',
    // Grid: панель инструментов / Удалить (delete)
    'Delete' => 'Удалить',
    'Delete an uninstalled module from the repository' => 'Удаление не установленного модуля из репозитория',
    'Are you sure you want to delete the uninstalled module from the repository?' => 'Вы уверены, что хотите удалить не установленный модуль из репозитория?',
    // Grid: панель инструментов / Демонтаж (unmount)
    'Unmount' => 'Демонтаж',
    'Delete an installed module without removing it from the repository' => 'Удаление установленного модуля без удаления его из репозитория',
    'Are you sure you want to remove the installed module without removing it from the repository?' 
        => 'Вы уверены, что хотите удалить установленный модуль без удаления его из репозитория?',
    // Grid: фильтр
    'All' => 'Все',
    'Installed' => 'Установленные',
    'None installed' => 'Не установленные',
    // Grid: поля
    'Name' => 'Название',
    'Module id' => 'Идентификатор',
    'Record id' => 'Идентификатор записи',
    'Path' => 'Путь',
    'Enabled' => 'Доступен',
    'Visible' => 'Видимый',
    'Package' => 'Пакет модуля',
    'Route' => 'Маршурт',
    'Author' => 'Автор',
    'Version' => 'Версия',
    'from' => 'от',
    'Description' => 'Описание',
    'Resource' => 'Ресурсы',
    'Use' => 'Назначение',
    'Date' => 'Дата',
    'Go to module' => 'Перейти к модулю',
    'Module settings' => 'Настройка модуля',
    'Module info' => 'Информация о модуле',
    'For append item menu' => 'Вызов модуля в главном меню панели управления',
    'Status' => 'Статус',
    // Grid: значения
    FRONTEND => 'Сайт',
    BACKEND => 'Панель управления',
    'Yes' => 'да',
    'No' => 'нет',
    'installed' => 'установлен',
    'not installed' => 'не установлен',
    'broken' => 'ошибка',
    'unknow' => 'неизвестно',
    // Grid: всплывающие сообщения / заголовок
    'Show' => 'Отображен',
    'Hide' => 'Скрыт',
    'Enabled' => 'Доступен',
    'Disabled' => 'Отключен',
    'Installing' => 'Установка',
    'Updating' => 'Обновление',
    'Unmounting' => 'Демонтаж',
    'Uninstalling' => 'Удаление',
    'Deleting' => 'Удаление',
    'Downloading' => 'Скачивание',
    // Grid: всплывающие сообщения / текст
    'Module {0} - hide' => 'Модуль "<b>{0}</b>" - <b>скрыт</b>.',
    'Module {0} - show' => 'Модуль"<b>{0}</b>" - <b>отображен</b>.',
    'Module {0} - enabled' => 'Модуль "<b>{0}</b>" - <b>доступен</b>.',
    'Module {0} - disabled' => 'Модуль "<b>{0}</b>" - <b>отключен</b>.',
    'Modules configuration files are updated' => 'Файлы конфигурации модулей обновлены!',
    'Updating modules' => 'Обновление модулей',
    'Module installation "{0}" completed successfully' => 'Установка модуля "{0}" завершена успешно.',
    'Update of module "{0}" completed successfully' => 'Обновление модуля "{0}" успешно завершено.',
    'Unmounting of module "{0}" completed successfully' => 'Демонтаж модуля "{0}" успешно завершен.',
    'Uninstalling of module "{0}" completed successfully' => 'Удаление модуля "{0}" успешно завершено.',
    'Deleting of module completed successfully' => 'Удаление модуля выполнено успешно.',
    'The module package will now be loaded' => 'Сейчас будет выполнена загрузка пакета модуля.',
    // Grid: сообщения (ошибки)
    'Module installation configuration file is missing' => 'Отсутствует файл конфигурации установки модуля (.install.php).',
    'It is not possible to remove the module from the repository because it\'s installed' 
        => 'Невозможно выполнить удаление модуля из репозитория, т.к. он установлен.',
    // Grid: аудит записей
    'module {0} with id {1} is hidden' => 'скрытие модуля "<b>{0}</b>" c идентификатором "<b>{1}</b>"',
    'module {0} with id {1} is shown' => 'отображение модуля "<b>{0}</b>" c идентификатором "<b>{1}</b>"',
    'module {0} with id {1} is enabled' => 'предоставление доступа к модулю "<b>{0}</b>" c идентификатором "<b>{1}</b>"',
    'module {0} with id {1} is disabled' => 'отключение доступа к модулю "<b>{0}</b>" c идентификатором "<b>{1}</b>"',

    // Form
    '{form.title}' => 'Редактирование модуля "{title}"',
    '{form.subtitle}' => 'Редактирование базовых настроек модуля',
    // Form: поля
    'Identifier' => 'Идентификатор',
    'Record identifier' => 'Идентификатор записи',
    'Default' => 'По умолчанию',
    'enabled' => 'доступен',
    'visible' => 'видим',

    // Upload
    '{upload.title}' => 'Загрузка файла пакета модуля',
    // Upload: панель инструментов
    'Upload' => 'Загрузить',
    // Upload: поля
    'File name' => 'Имя файла',
    '(more details)' => '(подробнее)',
    'The file(s) will be downloaded according to the parameters for downloading resources to the server {0}' 
        => 'Загрузка файла(ов) будет выполнена согласно <em>"параметрам загрузки ресурсов на сервер"</em>. Только расширение файла ".gpk". {0}',
    // Upload: всплывающие сообщения / заголовок
    'Uploading a file' => 'Загрузка файла',
    // Upload: сообщения
    'File uploading error' => 'Ошибка загрузки файла пакета модуля.',
    'Error creating temporary directory to download module package file' 
        => 'Ошибка создания временного каталога для загрузки файла пакета модуля.',
    'File uploaded successfully' => 'Файл пакета модуля успешно загружен.',
    'The module package file does not contain one of the attributes: id, type' 
        => 'Файл пакета модуля не содержит один из атрибутов: "id" или "type".',
    'Module attribute "{0}" is incorrectly specified' => 'Неправильно указан атрибут "{0}" модуля.',
    'You already have the module "{0}" installed. Please remove it and try again' 
        => 'У Вас уже установлен модуль "{0}". Удалите его и повторите действие заново.',
    'You already have a module with files installed: {0}' 
        => 'У Вас уже установлен модуль со следующими файлами, удалиет их и <br>повторите действие заново: <br><br>{0}<br>...',

    // ShortcodeSettings: сообщения (ошибки)
    'Unable to show module shortcode settings' => 'Невозможно показать настройки шорткода модуля.'
];
