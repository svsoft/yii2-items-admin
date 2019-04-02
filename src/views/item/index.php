<?php

/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 * @var \svsoft\yii\items\entities\ItemType $itemType
 * @var \svsoft\yii\items\admin\components\ItemRelation $relation
 */


/** @var \yii\web\Controller $controller */
$controller = $this->context;
/** @var $module \svsoft\yii\items\admin\ItemsAdminModule */
$module = $controller->module;

$config  = $module->getGridViewConfig($itemType->getName());

$labels = $module->labelManager->getLabel($itemType);

$itemTypeLabel = \yii\helpers\Inflector::camel2words( $itemType->getName(), true);

if ($relation)
    $this->params['breadcrumbs'][] = ['label'=>$relation->getItem(), 'url'=> ['update', 'id'=>$relation->getItemId()] ];

$this->params['breadcrumbs'][] = ['label' => Yii::t('items', $labels->items)];

$this->title = ($relation ? $relation->getItem() . ' / ' : '') . ' ' . Yii::t('items', $labels->items);
?>

<div class="item-index box box-primary">
    <div class="box-header with-border">
        <?=\yii\helpers\Html::a($labels->addItemButton, ['create', 'type'=>$itemType->getName(),'relation' => (string)$relation],['class'=>'btn btn-success'])?>
    </div>
    <div class="box-body">

        <?=\svsoft\yii\items\widgets\ItemGridView::widget(\yii\helpers\ArrayHelper::merge([
            'id' =>'item-grid',
            'class'=>\svsoft\yii\items\widgets\ItemGridView::class,
            'dataProvider' => $dataProvider,
            'itemType' => $itemType,
            'labels' => $labels->fields,
            'rowOptions' => function ($model, $key) use ($relation) {
                                return [
                                        'data'=>[
                                                'href'=> \yii\helpers\Url::to(['item/update', 'id' => $key, 'relation' => (string)$relation]),
                                        ]
                                ];
                            }
            ,
            'columns' => [
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'contentOptions' => ['class' => 'text-center', 'style'=>'white-space: nowrap; width:1%;'],
                    'options' => ['class'=>'options'],
                    'visibleButtons' => [
                        'view' => false,
                    ],
                    'buttonOptions' => ['class'=>['btn btn-default btn-sm']],
                    'urlCreator' => function ($action, $model, $key) use ($relation) {
                        return \yii\helpers\Url::to([$action, 'id' => $key, 'relation' => (string)$relation]);
                    }
                ]
            ],
        ], $config));?>
    </div>
</div>

<?php
$js = <<< JS

$('#item-grid > table > tbody tr').dblclick(function() {
    window.location = $(this).data('href');
});

JS;
$this->registerJs($js);
?>