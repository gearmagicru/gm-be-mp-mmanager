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
        'id'       => 'id',
        'moduleId' => 'module_id'
    ];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{module_locale}}',
            'primaryKey' => 'module_id',
            'searchBy'   => 'name',
            'order'      => ['name' => 'ASC'],
            'fields'     => [
                ['name', 'direct' => 'modl.name'],
                ['description']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function selectAll(string $tableName = null): array
    {
        /** @var \Gm\Db\Sql\Select $select */
        $select = $this->builder()->select();
        $select
            ->columns(['id', 'module_id', 'name', 'description'])
            ->quantifier(new Sql\Expression('SQL_CALC_FOUND_ROWS'))
            ->from(['mod' => '{{module}}'])
            ->join(
                ['modl' => '{{module_locale}}'],
                'modl.module_id = mod.id AND modl.language_id = ' . (int) Gm::$app->language->code,
                ['loName' => 'name', 'loDescription' => 'description'],
                $select::JOIN_LEFT
            );

        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $this->buildQuery($select);
        $rows = $this->fetchRows($command);
        $rows = $this->afterFetchRows($rows);
        return $this->afterSelect($rows, $command);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFetchRow(array $row, array &$rows): void
    {
        if ($row['loName']) {
            $row['name'] = $row['loName'];
        }
        if ($row['loDescription']) {
            $row['description'] = $row['loDescription'];
        }
        $rows[] = [$row[$this->key], $row['name'], $row['description']];
    }
}
