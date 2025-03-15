<?php
/**
 * Этот файл является частью пакета GM Panel.
 * 
 * @link https://gearmagic.ru/framework/
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\ModuleManager\Model;

use Gm;
use Gm\Db\Sql;
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных элементов выпадающего списка установленных модулей 
 * (реализуемых представленим с использованием компонента Gm.form.Combo ExtJS).
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\ModuleManager\Model
 * @since 1.0
 */
class ModuleCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    protected array $allowedKeys = [
        'id'    => 'id',
        'rowId' => 'rowId'
    ];

    /**
     * {@inheritdoc}
     * 
     * Для определения порядкового номера сортировки.
     */
    protected array $sort = [
        'id'    => 0,
        'rowId' => 0,
        'name'  => 1,
        'desc'  => 2
    ];

    /**
     * Порядковый номер сортировки.
     * 
     * @var int
     */
    protected int $sortIndex = 1;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        // добавление записи "без выбора"
        $noneRow = $request->getQuery('noneRow', null);
        if ($noneRow !== null) {
            $this->useNoneRow = $noneRow == 1;
        }
        // определение порядкового номера сортировки
        $sort = $request->getQuery('sort', 'name');
        $this->sortIndex = $this->sort[$sort] ?? $this->sortIndex;
        // уникальный ключ записи
        $key = Gm::$app->request->getQuery($this->keyParam);
        $this->key = $this->allowedKeys[$key] ?? 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function selectAll(string $tableName = null): array
    {
        $rows = [];
        if ($this->useNoneRow) {
            $rows[] = $this->noneRow();
        }

        /** @var \Gm\ModuleManager\ModuleRegistry $registry */
        $registry = Gm::$app->modules->getRegistry();
        /** @var array $list */
        $list = $registry->getListInfo();
        if ($list) {
            foreach ($list as $row) {
                $rows[] = [
                    $row[$this->key],
                    $row['name'], 
                    $row['description'], 
                    $row['smallIcon']
                ];
            }
        }

        usort($rows, function (array $a, array $b) {
            return $a[$this->sortIndex] <=> $b[$this->sortIndex];
        });

        return [
            'total' => sizeof($rows),
            'rows'  => $rows
        ];
    }
}
