<?php

namespace svsoft\yii\items\admin\components;

use yii\base\BaseObject;

class Label extends BaseObject
{
    public $item;
    public $items;
    public $addItemPage;
    public $addItemButton;
    public $updateItemPage;
    public $fields = [];


    public function toArray()
    {
        return (array)$this;
    }


}