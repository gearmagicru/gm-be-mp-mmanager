<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\ModuleManager\Controller;

use Gm;
use Gm\Panel\Http\Response;
use Gm\Filesystem\Filesystem;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Controller\BaseController;

/**
 * Контроллер удаления и демонтажа модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Controller
 * @since 1.0
 */
class Module extends BaseController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\ModuleManager\Extension
     */
    public BaseModule $module;

    /**
     * Действие "unmount" выполняет удаление установленного модуля без удаления его 
     * из репозитория.
     * 
     * @return Response
     */
    public function unmountAction(): Response
    {
        /** @var \Gm\ModuleManager\ModuleManager */
        $modules = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        // идентификатор модуля в базе данных
        $moduleId = $request->getPost('id', null, 'int');
        if (empty($moduleId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var null|array Конфигурация установленного модуля */
        $moduleConfig = Gm::$app->modules->getRegistry()->getInfo($moduleId, true);
        if ($moduleConfig === null) {
            $response
                ->meta->error($this->module->t('Module with specified id "{0}" not found', [$moduleId]));
            return $response;
        }

        // локализация модуля
        $localization = $modules->selectName($moduleConfig['rowId']);
        if ($localization) {
            $name = $localization['name'] ?? SYMBOL_NONAME;
        } else {
            $name = $moduleConfig['name'] ?? SYMBOL_NONAME;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$modules->installerExists($moduleConfig['path'])) {
            $response
                ->meta->error(
                    $this->module->t('The module installer at the specified path "{0}" does not exist', [$moduleConfig['path']])
                );
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller $installer Установщик модуля */
        $installer = $modules->getInstaller([
            'response'  => $response,
            'module'    => $this->module,
            'namespace' => $moduleConfig['namespace'],
            'path'      => $moduleConfig['path'],
            'moduleId'  => $moduleId
        ]);

        // если не получилось создать установщик
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        // демонтируем модуль
        if ($installer->unmount()) {
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Unmounting of module "{0}" completed successfully', [$name]), 
                        $this->t('Unmounting'), 
                        'accept'
                    )
                    ->cmdReloadGrid($this->module->viewId('grid'));
        } else {
            $response
                ->meta->error($installer->getError());
        }
        return $response;
    }

    /**
     * Действие "uninstall" полностью выполняет удаление установленного модуля.
     * 
     * @return Response
     */
    public function uninstallAction(): Response
    {
        /** @var \Gm\ModuleManager\ModuleManager */
        $modules = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        // идентификатор модуля в базе данных
        $moduleRowId = $request->getPost('id', null, 'int');
        if (empty($moduleRowId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var null|array Конфигурация установленного модуля */
        $moduleConfig = $modules->getRegistry()->getInfo($moduleRowId, true);
        if ($moduleConfig === null) {
            $response
                ->meta->error($this->module->t('Module with specified id "{0}" not found', [$moduleRowId]));
            return $response;
        }

        // локализация модуля
        $localization = $modules->selectName($moduleConfig['rowId']);
        if ($localization) {
            $name = $localization['name'] ?? SYMBOL_NONAME;
        } else {
            $name = $moduleConfig['name'] ?? SYMBOL_NONAME;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$modules->installerExists($moduleConfig['path'])) {
            $response
                ->meta->error(
                    $this->module->t('The module installer at the specified path "{0}" does not exist', [$moduleConfig['path']])
                );
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller $installer Установщик модуля */
        $installer = $modules->getInstaller([
            'response'  => $response,
            'module'    => $this->module,
            'namespace' => $moduleConfig['namespace'],
            'path'      => $moduleConfig['path'],
            'moduleId'  => $moduleConfig['id']
        ]);

        // если не получилось создать установщик
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        // удаление модуля
        if ($installer->uninstall()) {
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Uninstalling of module "{0}" completed successfully', [$name]), 
                        $this->t('Uninstalling'), 
                        'accept'
                    )
                    ->cmdReloadGrid($this->module->viewId('grid'));
        } else {
            $response
                ->meta->error($installer->getError());
        }
        return $response;
    }

    /**
     * Действие "update" обновляет конфигурации установленных модулей.
     * 
     * @return Response
     */
    public function updateAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        // обновляет конфигурацию установленных модулей
        Gm::$app->modules->update();
        Gm::$app->extensions->update();
        $response
            ->meta->success(
                $this->t('Modules configuration files are updated'), 
                $this->t('Updating modules'), 
                'custom', 
                $this->module->getAssetsUrl() . '/images/icon-update-config.svg'
            );
        return $response;
    }

    /**
     * Действие "delete" выполняет удаление не установленного модуля из репозитория.
     * 
     * @return Response
     */
    public function deleteAction(): Response
    {
        /** @var \Gm\ModuleManager\ModuleManager */
        $modules = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string Идентификатор установки модуля */
        $installId = Gm::$app->request->post('installId');

        /** @var string|array Расшифровка идентификатора установки модуля */
        $decrypt = $modules->decryptInstallId($installId);
        if (is_string($decrypt)) {
            $response
                ->meta->error($decrypt);
            return $response;
        }

        /** @var null|array Параметры конфигурации установки модуля */
        $installConfig = $modules->getConfigInstall($decrypt['path']);
        if (empty($installConfig)) {
            $response
                ->meta->error(
                    $this->module->t('Module installation configuration file is missing')
                );
            return $response;
        }

        // если модуль установлен
        if ($modules->getRegistry()->has($installConfig['id'])) {
            $response
                ->meta->error(
                    $this->module->t('It is not possible to remove the module from the repository because it\'s installed')
                );
            return $response;
        }

        // попытка удаления всех файлов модуля
        if (Filesystem::deleteDirectory(Gm::$app->modulePath . $decrypt['path'])) {
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->t('Deleting of module completed successfully'), 
                        $this->t('Deleting'), 
                        'accept'
                    )
                    ->cmdReloadGrid($this->module->viewId('grid'));
        } else {
            $response
                ->meta->error(
                    Gm::t('app', 'Could not perform directory deletion "{0}"', [Gm::$app->modulePath . $decrypt['path']])
                );
        }
        return $response;
    }
}
