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
use Gm\Panel\Data\Model\FormModel;
use Gm\Panel\Controller\GridController;
use Gm\Backend\Marketplace\ModuleManager\Widget\TabGrid;

/**
 * Контроллер вывода сетки установленных и устанавливаемых модулей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // изменение записи по указанному идентификатору
            case 'update':
                /** @var FormModel $model */
                $model = $this->lastDataModel;
                if ($model instanceof FormModel) {
                    $event   = $model->getEvents()->getLastEvent(true);
                    $columns = $event['columns'];
                    // если изменение видимости модуля
                    if (isset($columns['visible'])) {
                        $visible = (int) $columns['visible'];
                        return $this->module->t(
                            'module {0} with id {1} is ' . ($visible > 0 ? 'shown' : 'hidden'), [$model->moduleName, $model->getIdentifier()]
                        );
                    }
                    // если изменение доступности модуля
                    if (isset($columns['enabled'])) {
                        $enabled = (int) $columns['enabled'];
                        return $this->module->t(
                            'module {0} with id {1} is ' . ($enabled > 0 ? 'enabled' : 'disabled'), [$model->moduleName, $model->getIdentifier()]
                        );
                    }
                }

            default:
                return parent::translateAction($params, $default);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        return new TabGrid();
    }

   /**
     * Действие "view" выводит представление.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Panel\Widget\TabGrid $widget */
        $widget = $this->getWidget();
        // если была ошибка при формировании представления
        if ($widget === false) {
            return $response;
        }

        /** @var \Gm\Panel\Data\Model\GridModel $model модель данных*/
        $model = $this->getModel($this->defaultModel);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel]));
            return $response;
        }

        // сброс "dropdown" фильтра таблицы
        $store = $this->module->getStorage();
        $store->directFilter = null; 

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
