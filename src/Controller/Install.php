<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\ModuleManager\Controller;

use Gm;
use Gm\Panel\Http\Response;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Controller\FormController;
use Gm\Backend\Marketplace\ModuleManager\Widget\InstallWindow;

/**
 * Контроллер установки модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Controller
 * @since 1.0
 */
class Install extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\ModuleManager\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     * 
     * @return InstallWindow
     */
    public function createWidget(): InstallWindow
    {
        return new InstallWindow();
    }

    /**
     * Действие "complete" завершает установку модуля.
     * 
     * @return Response
     */
    public function completeAction(): Response
    {
        // добавляем шаблон локализации для установки (см. ".extension.php")
        $this->module->addTranslatePattern('install');

        /** @var \Gm\ModuleManager\ModuleManager $manager Менеджер модулей */
        $manager = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $installId Идентификатор установки модуля */
        $installId = Gm::$app->request->post('installId');

        /** @var string|array $decrypt Расшифровка идентификатора установки модуля */
        $decrypt = $manager->decryptInstallId($installId);
        if (is_string($decrypt)) {
            $response
                ->meta->error($decrypt);
            return $response;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($decrypt['path'])) {
            $response
                ->meta->error($this->module->t('The module installer at the specified path "{0}" does not exist', [$decrypt['path']]));
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller $installer Установщик модуля */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $decrypt['namespace'],
            'path'      => $decrypt['path'], 
            'installId' => $installId
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        // устанавливает модуль
        if ($installer->install()) {
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Module installation "{0}" completed successfully', [$installer->info['name']]),
                        $this->t('Installing'),
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
     * Действие "view" выводит интерфейс установщика модуля.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        // добавляем шаблон локализации для установки (см. ".extension.php")
        $this->module->addTranslatePattern('install');

        /** @var \Gm\ModuleManager\ModuleManager Менеджер модулей */
        $manager = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $installId Идентификатор установки модуля */
        $installId = Gm::$app->request->post('installId');

        /** @var string|array $decrypt Расшифровка идентификатора установки модуля */
        $decrypt = $manager->decryptInstallId($installId);
        if (is_string($decrypt)) {
            $response
                ->meta->error($decrypt);
            return $response;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($decrypt['path'])) {
            $response
                ->meta->error($this->module->t('The module installer at the specified path "{0}" does not exist', [$decrypt['path']]));
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller|null $installer Установщик модуля */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $decrypt['namespace'],
            'path'      => $decrypt['path'], 
            'installId' => $installId
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        /** @var null|\Gm\Panel\Widget\BaseWidget|\Gm\View\Widget $widget */
        $widget = $installer->getWidget();
        // если установщик не имеет модуль
        if ($widget === null) {
            /** @var InstallWindow $widget */
            $widget = $this->getWidget();
        }
        $widget->info = $installer->getModuleInfo();

        // проверка конфигурации устанавливаемого модуля
        if (!$installer->validateInstall()) {
            $widget->notice = $installer->getError();
        }

        // если была ошибка при формировании модуля
        if ($widget === false) {
            return $response;
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
