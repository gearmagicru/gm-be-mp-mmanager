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
use Gm\Backend\Marketplace\ModuleManager\Widget\UpdateWindow;

/**
 * Контроллер обновления модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Controller
 * @since 1.0
 */
class Update extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\ModuleManager\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function createWidget(): UpdateWindow
    {
        /** @var UpdateWindow $window Окно обновления модуля (Ext.window.Window Sencha ExtJS) */
        $window = new UpdateWindow();
        $window->title = $this->t('{update.title}');
        // шаги обновления модуля: ['заголовок', выполнен]
        $window->steps->extract  = [$this->t('Extract files from the update package'), true];
        $window->steps->copy     = [$this->t('Copying files to the module repository'), true];
        $window->steps->validate = [$this->t('Checking module files and configuration'), true];
        $window->steps->update   = [$this->t('Update module data'), false];
        $window->steps->register = [$this->t('Module registry update'), false];

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router['route'] = $this->module->route('/update');
        return $window;
    }

    /**
     * Действие "complete" завершает обновление модуля.
     * 
     * @return Response
     */
    public function completeAction(): Response
    {
        // добавляем шаблон локализации для обновления (см. ".extension.php")
        $this->module->addTranslatePattern('update');

        /** @var \Gm\ModuleManager\ModuleManager Менеджер модулей */
        $manager = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $moduleId Идентификатор установленного модуля */
        $moduleId = Gm::$app->request->post('id');
        if (empty($moduleId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var null|array $moduleParams Параметры установленного модуля */
        $moduleParams = $manager->getRegistry()->get($moduleId);
        // модуль с указанным идентификатором не установлен
        if ($moduleParams === null) {
            $response
                ->meta->error(
                    Gm::t('app', 'There is no {0} with the specified id "{1}"', [Gm::t('app', 'Module'), $moduleId])
                );
            return $response;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($moduleParams['path'])) {
            $response
                ->meta->error($this->module->t('The module installer at the specified path "{0}" does not exist', [$moduleParams['path']]));
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller $installer Установщик модуля */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $moduleParams['namespace'],
            'path'      => $moduleParams['path'],
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        // обновляет модуль
        if ($installer->update()) {
            $info = $installer->getModuleInfo();
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Update of module "{0}" completed successfully', [$info ? $info['name'] : SYMBOL_NONAME]),
                        $this->t('Updating'),
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
        // добавляем шаблон локализации для обновления (см. ".extension.php")
        $this->module->addTranslatePattern('update');

        /** @var \Gm\ModuleManager\ModuleManager Менеджер модулей */
        $manager = Gm::$app->modules;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string Идентификатор установленного модуля */
        $moduleId = Gm::$app->request->post('id');
        if (empty($moduleId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var null|array $moduleParams Параметры установленного модуля */
        $moduleParams = $manager->getRegistry()->get($moduleId);
        // модуль с указанным идентификатором не установлен
        if ($moduleParams === null) {
            $response
                ->meta->error(
                    Gm::t('app', 'There is no {0} with the specified id "{1}"', [Gm::t('app', 'Module'), $moduleId])
                );
            return $response;
        }

        // если модуль не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($moduleParams['path'])) {
            $response
                ->meta->error($this->module->t('The module installer at the specified path "{0}" does not exist', [$moduleParams['path']]));
            return $response;
        }

        // каждый модуль обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\ModuleManager\ModuleInstaller $installer Установщик модуля */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $moduleParams['namespace'],
            'path'      => $moduleParams['path']
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create module installer'));
            return $response;
        }

        // проверка конфигурации обновляемого модуля
        if (!$installer->validateUpdate()) {
            $response
                ->meta->error(
                    $this->module->t('Unable to update the module, there were errors in the files of the new version of the module')
                    . '<br>' . $installer->getError()
                );
            return $response;
        }

        /** @var UpdateWindow $widget */
        $widget = $installer->getWidget();
        // если установщик не имеет виджет
        if ($widget === null) {
            $widget = $this->getWidget();
        }
        $widget->info = $installer->getModuleInfo();

        // если была ошибка при формировании виджета
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
