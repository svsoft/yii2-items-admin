<?php

namespace svsoft\yii\items\admin\components;

use svsoft\yii\items\entities\Item;
use svsoft\yii\items\repositories\ItemRepository;

class ItemRelation
{
    public $itemId;

    public $fieldName;

    function __construct($fieldName, $itemId)
    {
        $this->fieldName = $fieldName;
        $this->itemId = $itemId;
    }

    function __toString()
    {
        return $this->fieldName.'-'.$this->itemId;
    }

    function getItemId()
    {
        return $this->itemId;
    }

    function getFieldName()
    {
        return $this->fieldName;
    }

    function toArray()
    {
        return [$this->fieldName=>$this->getItemId()];
    }

    /**
     * @return Item
     * @throws \svsoft\yii\items\exceptions\ItemNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    function getItem()
    {
        /** @var ItemRepository $repository */
        $repository = \Yii::$container->get(ItemRepository::class);
        $item = $repository->get($this->itemId);

        return $item;
    }
}
