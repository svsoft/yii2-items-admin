<?php

namespace svsoft\yii\items\admin\components;

use svsoft\yii\items\entities\ItemType;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class LabelManager extends BaseObject
{
    /**
     * @var Label
     */
    public $defaultLabel;

    /**
     * @var Label[]
     */
    protected $labels;


    /**
     * @var array
     */
    public $fieldCommonLabels = [];


    public function init()
    {
        $this->defaultLabel = [
            'class'          => Label::class,
            'addItemButton'  => 'Добавить',
            'addItemPage'    => 'Добавление',
            'updateItemPage' => 'Редактирование',
            'fields'         => [
                'id' => 'Ид',
            ],
        ];

        $this->fieldCommonLabels = ArrayHelper::merge([
            'id'          => 'Ид',
            'name'        => 'Наименование',
            'sort'        => 'Сортировка',
            'title'       => 'Заголовок',
            'text'        => 'Текст',
            'content'     => 'Контент',
            'link'        => 'Ссылка',
            'image'       => 'Картинка',
            'images'      => 'Картинки',
            'file'        => 'Файл',
            'files'       => 'Файлы',
            'description' => 'Описание',
            'slug'        => 'Код Url',
            'label'       => 'Подпись',
            'code'        => 'Код',
        ], $this->fieldCommonLabels);

        parent::init();
    }
    
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * @param ItemType $itemType
     *
     * @return mixed|Label
     * @throws \yii\base\InvalidConfigException
     */
    public function getLabel(ItemType $itemType)
    {
        $itemTypeName = $itemType->getName();
        $label = ArrayHelper::getValue($this->labels, $itemTypeName);

        if ($label instanceof  Label)
            return $label;

        $labelConfig = $this->getDefaultLabelConfig($itemType);

        foreach($itemType->getFields() as $field)
        {
            $name = $field->getName();

            $text = ArrayHelper::getValue($this->fieldCommonLabels, $name, Inflector::camel2words( $name, true));
            $labelConfig['fields'][$name] = $text;
        }

        if ($label)
            $labelConfig = ArrayHelper::merge($labelConfig, $label);

        $this->labels[$itemTypeName] =  \Yii::createObject($labelConfig);

        return $this->labels[$itemTypeName];
    }


    protected function getDefaultLabelConfig(ItemType $itemType)
    {
        $config['item'] = Inflector::camel2words($itemType->getName(), true);
        $config['items'] = Inflector::pluralize($config['item']);

        return array_merge($config, $this->defaultLabel);

    }

}