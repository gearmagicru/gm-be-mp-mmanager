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
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных изменения модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Model
 * @since 1.0
 */
class Form extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public array $localizerParams = [
        'tableName'  => '{{module_locale}}',
        'foreignKey' => 'module_id',
        'modelName'  => 'Gm\ModuleManager\Model\ModuleLocale',
    ];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{module}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['name'],
                ['description'],
                [
                    'module_id',
                    'alias' => 'moduleId'
                ],
                [
                    'enabled', 
                    'title' => 'Enabled'
                ],
                [
                    'visible', 
                    'title' => 'Visible'
                ],
                /**
                 * поля добавленные динамически:
                 * - title, имя модуля (для заголовка окна)
                 */
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['enabled', 'visible'], 'logic']
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
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                // если всё успешно
                if ($result) {
                    /** @var \Gm\ModuleManager\ModuleRegistry $installed */
                    $installed = Gm::$app->modules->getRegistry();
                    $module = $installed->get($this->moduleId);
                    if ($module) {
                        $lock = (bool) ($module['lock'] ?? false);
                        // если модуль не системный
                        if (!$lock) {
                            // обвновление конфигурации установленных модулей
                            $installed->set($this->moduleId, [
                                'visible'     => (bool) $this->visible,
                                'enabled'     => (bool) $this->enabled,
                                'name'        => $this->name,
                                'description' => $this->description
                            ], true);
                        }
                    }
                }
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                // обвновление конфигурации установленных модулей
                Gm::$app->modules->update();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // для формирования загаловка по атрибутам
        $locale = $this->getLocalizer()->getModel();
        if ($locale) {
            $this->title = $locale->name ?: '';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            if (!Gm::$app->modules->getRegistry()->has($this->moduleId)) {
                $this->setError(
                    Gm::t('app', 'There is no {0} with the specified id "{1}"', [Gm::t('app', 'Module'), $this->moduleId])
                );
                return false;
            }
        }
        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function getActionTitle():string
    {
        return isset($this->title) ? $this->title : parent::getActionTitle();
    }
}
