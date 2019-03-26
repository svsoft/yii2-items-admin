<?php
use yii\grid\GridView;
/**
 * @var $this \yii\web\View
 * @var \yii\data\ArrayDataProvider $dataProvider
 */

$this->title = 'Сисок типов элементов';
?>

<div class="item-type-index box box-primary">
    <div class="box-body">

        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                        return $itemType->getId();
                    },
                    'label' => 'Ид',

                ],
                [
                    'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                        return \yii\helpers\Html::a($itemType->getName(), ['view','id'=>$itemType->getId()]);
                    },
                    'label' => 'Название',
                    'format' => 'html',
                ],
                [
                    'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                        return \yii\helpers\Html::a('Элементы',['item/index','type'=>$itemType->getName()],['class'=>'btn btn-info']);
                    },
                    'format' => 'html',
                ],
            ],
        ]);?>
    </div>
</div>
