<?php
/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 * @var \svsoft\yii\items\entities\ItemType $itemType
 */
$this->params['breadcrumbs'][] = ['label' => 'Типы', 'url' => ['item-type/index']];
$this->params['breadcrumbs'][] = ['label' => 'Элементы'];
?>

<div class="item-index box box-primary">
    <div class="box-header with-border">
        <?=\yii\helpers\Html::a('Добавить',['create', 'type'=>$itemType->getName()],['class'=>'btn btn-success'])?>
    </div>
    <div class="box-body">

        <?=\svsoft\yii\items\widgets\ItemGridView::widget([
            'dataProvider' => $dataProvider,
            'itemType' => $itemType,
            'columns' => [
                [
                    'class'=>\yii\grid\ActionColumn::class,
                    //'template' => ''
                ]
            ],
        ]);?>
    </div>
</div>