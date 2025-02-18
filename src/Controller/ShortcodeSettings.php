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

/**
 * Контроллер настройки шорткода модуля.
 * 
 * Действия контроллера:
 * - view, вывод интерфейса настроек шорткода модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Controller
 * @since 1.0
 */
class ShortcodeSettings extends FormController
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
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // вывод интерфейса
            case 'view':
                return Gm::t(BACKEND, "{{$this->actionName} settings action}");

            default:
                return parent::translateAction(
                    $params,
                    $default ?: Gm::t(BACKEND, "{{$this->actionName} settings action}")
                );
        }
    }

    /**
     * Возвращает идентификатор выбранного модуля.
     *
     * @return int
     */
    public function getIdentifier(): int
    {
        return (int) Gm::$app->router->get('id');
    }

    /**
     * Действие "view" выводит интерфейс настроек шорткода модуля.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|int $id Идентификатор модуля */
        $id = $this->getIdentifier();
        if (empty($id)) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'Parameter "{0}" not specified', ['id']) :
                    $this->module->t('Unable to show module shortcode settings')
            );
        }

        /** @var null|string $tagName Имя тега */
        $tagName = Gm::$app->request->getQuery('name');
        if (empty($tagName)) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'Parameter "{0}" not specified', ['name']) :
                    $this->module->t('Unable to show module shortcode settingss')
            );
        }

        /** @var null|array $moduleParams Параметры модуля */
        $moduleParams = Gm::$app->modules->getRegistry()->getAt($id);
        if ($moduleParams === null) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'There is no widget with the specified id "{0}"', ['$id']) :
                    $this->module->t('Unable to show module shortcode settings')
            );
        }

        /** @var null|array $install Параметры установки модуля */
        $install = Gm::$app->modules->getRegistry()->getConfigInstall($id);
        // если параметры установки не найдены
        if ($install === null) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'There is no widget with the specified id "{0}"', ['$id']) :
                    $this->module->t('Unable to show module shortcode settings')
            );
        }

        /** @var array|null $shortcode Параметры указанного шорткода модуля */
        $shortcode = $install['editor']['shortcodes'][$tagName] ?? null;
        if (empty($shortcode)) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'Parameter passed incorrectly "{0}"', ['shortcodes[' . $tagName . ']']) :
                    $this->module->t('Unable to show module shortcode settings')
            );
        }

        // если нет настроек шорткода
        if (empty($shortcode['settings'])) {
            return $this->errorResponse(
                GM_MODE_DEV ?
                    Gm::t('app', 'The value for parameter "{0}" is missing', ['shortcodes[settings]']) :
                    $this->module->t('Unable to show module shortcode settings')
            );
        }

        // для доступа к пространству имён объекта
        Gm::$loader->addPsr4($moduleParams['namespace']  . NS, Gm::$app->modulePath . $moduleParams['path'] . DS . 'src');

        $settingsClass = $moduleParams['namespace'] . NS . $shortcode['settings'];
        if (!class_exists($settingsClass)) {
            return $this->errorResponse(
                $this->module->t('Unable to create widget object "{0}"', [$settingsClass])
            );
        }

        // добавляем шаблон локализации модуля (которому принадлежит шорткод)
        $category = Gm::$app->translator->getCategory($this->module->id);
        // ключ шаблона при подключении не имеет значение
        $category->patterns['shortcodeSettings'] = [
            'basePath' => Gm::$app->modulePath . $moduleParams['path'] . DS . 'lang',
            'pattern'  => 'text-%s.php',
        ];
        $this->module->addTranslatePattern('shortcodeSettings');

        /** @var object|Gm\Panel\Widget\ShortcodeSettingsWindow $widget Виджет настроек шорткода */
        $widget = Gm::createObject($settingsClass);
        if ($widget instanceof Gm\Panel\Widget\ShortcodeSettingsWindow) {
            $widget->form->controller = 'gm-mp-mmanager-shortcodesettings';
            $widget
                ->setNamespaceJS('Gm.be.mp.mmanager')
                ->addRequire('Gm.be.mp.mmanager.ShortcodeSettingsController');    
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
