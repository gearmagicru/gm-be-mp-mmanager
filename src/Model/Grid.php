<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\ModuleManager\Model;

use Gm;
use Gm\ModuleManager\ModuleManager;
use Gm\Panel\Data\Model\ArrayGridModel;

/**
 * Модель данных вывода сетки установленных и устанавливаемых модулей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Model
 * @since 1.0
 */
class Grid extends ArrayGridModel
{
    /**
     * Менеджер модулей.
     * 
     * @see Grid::buildQuery()
     * 
     * @var ModuleManager
     */
    protected ModuleManager $modules;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'fields' => [
                ['id'], // идентификатор модуля в базе данных
                ['lock'],
                ['moduleId'], // идентификатор модуля
                ['moduleUse'], // назначение
                ['path'], // путь (директория модуля)
                ['route'], // маршрут
                ['icon'], // значок
                ['enabled'], // доступ к модулю
                ['visible'], // видимость для панели управления
                ['name'], // название
                ['namespace'], // пространство имён
                ['description'], // описание
                ['version'], // номер версии
                ['versionAuthor'], // автор версии
                ['versionDate'], // дата версии
                ['details'], // название и описание
                ['infoUrl'], // URL-адрес для информации о модуле
                ['settingsUrl'], // URL-адрес для настроек модуля
                ['moduleUrl'],
                ['status'], // статус модуля
                ['clsCellLock'], // CSS-класс строки таблицы блокировки модуля
                ['rowCls'],
                ['installId'], // для установки модуля
            ],
            'filter' => [
                'type' => ['operator' => '='],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // обновление конфигурации установленных модулей
                Gm::$app->modules->update();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function buildQuery($builder): array
    {
        $this->modules = Gm::$app->modules;

        /** @var \Gm\ModuleManager\ModuleRegistry $installed Установленные модули */
        $installed = $this->modules->getRegistry();
        /** @var \Gm\ModuleManager\ModuleRepository $repository Репозиторий модулей */
        $repository = $this->modules->getRepository();

        // вид фильтра
        $type = $this->directFilter ? $this->directFilter['type']['value'] ?? '' : 'installed';
        switch($type) {
            // все модули (установленные + не установленные)
            case 'all':
                return array_merge(
                    $installed->getListInfo(true, false, 'rowId', ['icon' => true, 'version' => true]),
                    $repository->find('Module', 'nonInstalled', ['icon' => true, 'version' => true, 'name' => true])
                );

            // установленные модули
            case 'installed':
                return $installed->getListInfo(true, false, 'rowId', ['icon' => true, 'version' => true]);

            // не установленные модули
            case 'nonInstalled':
                return $repository->find('Module', 'nonInstalled', ['icon' => true, 'version' => true, 'name' => true]);
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRow(mixed $row, int|string $rowKey): ?array
    {
        // идентификатор модуля в базе данных
        $rowId = $row['rowId'] ?? 0;
        // доступ к модулю
        $enabled = (int) ($row['enabled'] ?? -1);
        // видимость модуля (только для панели управления)
        $visible = (int) ($row['visible'] ?? -1);
        // доступ к изменению модуля (модуль является системным)
        $lock = $row['lock'] ?? false;
        // CSS-класс строки таблицы блокировки модуля (см. $lock)
        $clsLock = '';
        // назначение модуля: BACKEND, FRONTEND
        $use = $row['use'] ?? '';
        // маршрут
        $route = $row['route'] ?? '';
        // локальный путь к модулю
        $path = $row['path'] ?? '';
        // пространство имён модуля
        $namespace = $row['namespace'] ?? '';
        // статус модуля: установлен (1), не установлен (0), повреждён (2)
        $status = $rowId ? 1 : 0;
        // только для не установленных модулей ($path,$namespace)
        $installId = '';
        // версия модуля
        $version = $row['version'];
        $details = '';
        if ($version['version']) {
            $details = $version['version'];
            if ($version['versionDate']) {
                $details = $details . ' / ' . Gm::$app->formatter->toDate($version['versionDate']);
            }
        } else {
            if ($version['versionDate'])
                $details = $this->t('from') . ' ' . Gm::$app->formatter->toDate($version['versionDate']);
            else
                $details = $this->t('unknow');
        }

        // доступ к элементам контекстного меню записи
        $popupMenuItems = [];
        // если модуль не установлен
        if ($status === 0) {
            $visible = -1;
            $enabled = -1;
            $moduleUrl = '::disabled';
            $installId = $this->modules->encryptInstallId($path, $namespace);
            $popupMenuItems[] =  [0, 'disabled'];
        } else {
            // если модуль системный
            if ($lock) {
                $clsLock = 'g-cell-lock';
                $visible = -1;
                $enabled = -1;
                $moduleUrl = '::disabled';
            } else {
                $visible = $use === FRONTEND ? -1 : $visible;
                if ($use === FRONTEND) {
                    $moduleUrl = '::disabled';
                } else {
                    $moduleUrl =  $route ? '@backend/' . $route : '::disabled';
                }
            }
        }
        // если нет информации
        if (!isset($row['hasInfo']) || !$row['hasInfo']) {
            $popupMenuItems[] =  [3, 'disabled'];
        }
        // если нет настроек
        if (!isset($row['hasSettings']) || !$row['hasSettings']) {
            $popupMenuItems[] =  [2, 'disabled'];
        }
        return [
            'id'             => $status === 1 ? $rowId : uniqid(), // идентификатор модуля в базе данных
            'lock'           => $lock,
            'moduleId'       => $row['id'] ?? '', // идентификатор модуля
            'moduleUse'      => $use ? $this->t($use) : $this->t('unknow'), // назначение
            'path'           => $path, // путь (директория модуля)
            'route'          => $route, // маршрут
            'icon'           => $row['icon'], // значок
            'enabled'        => $enabled, // доступ к модулю
            'visible'        => $visible, // видимость для панели управления
            'name'           => $row['name'], // название
            'namespace'      => $namespace, // пространство имён (например '\Frontend\Application')
            'description'    => $row['description'], // описание
            'version'        => $version['version'], // номер версии
            'versionAuthor'  => $version['author'], // автор версии
            'versionDate'    => $version['versionDate'], // дата версии
            'details'        => $details, // название и описание
            'infoUrl'        => $row['infoUrl'] ?? '::disabled', // URL-адрес для информации о модуле
            'settingsUrl'    => $row['settingsUrl'] ?? '::disabled', // URL-адрес для настроек модуля
            'moduleUrl'      => $moduleUrl,
            'status'         => $status, // статус модуля: установлен (1), не установлен (0), повреждён (2)
            'clsCellLock'    => $clsLock, // CSS-класс строки таблицы блокировки модуля
            'popupMenuTitle' => $row['name'], // заголовок контекстного меню записи,
            'popupMenuItems' => $popupMenuItems, // доступ к элементам контекстного меню записи,
            'rowCls'         => 'gm-mp-mmanager-grid-row_' . ($status === 0 ? 'notinstalled' : 'installed'),
            'installId'      => $installId // для установки модуля ($path,$namespace)
        ];
    }
}
