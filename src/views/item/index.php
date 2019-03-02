<?php

/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 * @var \svsoft\yii\items\entities\ItemType $itemType
 * @var \svsoft\yii\items\admin\components\ItemRelation $relation
 */

$itemTypeLabel = \yii\helpers\Inflector::camel2words( $itemType->getName(), true);

if ($relation)
    $this->params['breadcrumbs'][] = ['label'=>$relation->getItem(), 'url'=> ['update', 'id'=>$relation->getItemId()] ];

$this->params['breadcrumbs'][] = ['label' => Yii::t('items', $itemTypeLabel . ' items')];

$this->title = ($relation ? $relation->getItem() : '') . ' ' . Yii::t('items', $itemTypeLabel . ' items');


/** @var \yii\web\Controller $controller */
$controller = $this->context;
/** @var $module \svsoft\yii\items\admin\ItemsAdminModule */
$module = $controller->module;

$config  = \yii\helpers\ArrayHelper::getValue($module->gridViewConfig, $itemType->getName(), []);
?>

<div class="item-index box box-primary">
    <div class="box-header with-border">
        <?=\yii\helpers\Html::a('Добавить', ['create', 'type'=>$itemType->getName(),'relation' => (string)$relation],['class'=>'btn btn-success'])?>
    </div>
    <div class="box-body">

        <?=\svsoft\yii\items\widgets\ItemGridView::widget(\yii\helpers\ArrayHelper::merge([
            'dataProvider' => $dataProvider,
            'itemType' => $itemType,
            'columns' => [
                [
                    'class'=>\yii\grid\ActionColumn::class,
                    'urlCreator' => function ($action, $model, $key, $index, \yii\grid\ActionColumn $column) use ($relation) {
                        return \yii\helpers\Url::to([$action, 'id' => $key, 'relation' => (string)$relation]);
                    }
                ]
            ],
        ], $config));?>
    </div>
</div>