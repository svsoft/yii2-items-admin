<?php

namespace svsoft\yii\items\admin;

use svsoft\yii\items\admin\components\LabelManager;
use yii\base\Module;
use yii\helpers\ArrayHelper;

/**
 * Class ItemsAdminModule
 * @package svsoft\yii\items\admin
 *
 * @property LabelManager $labelManager
 */
class ItemsAdminModule extends Module
{
    /**
     * Массив переопределения конфигурации ItemGridView
     * @var array
     */
    public $gridViewConfig = [];


    public function init()
    {
        $labelManagerConfig = [
            'class'=>LabelManager::class,
        ];

        $labelManagerConfig = ArrayHelper::getValue($this->components, 'labelManager', $labelManagerConfig);

        $this->set('labelManager', $labelManagerConfig);

        parent::init();
    }


    public function getGridViewConfig($itemTypeName)
    {
        $config = ArrayHelper::getValue($this->gridViewConfig, $itemTypeName, []);

        return $config;
    }

}
