<?php
use yii\grid\GridView;
/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 */

?>
<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                return $itemType->getId();
                },
            'label' => 'Ид'
        ],
        [
            'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                return $itemType->getName();
            },
            'label' => 'Название'
        ],
        [
            'value' => function(\svsoft\yii\items\entities\ItemType $itemType) {
                return \yii\helpers\Html::a('Элементы',['item/index','type'=>$itemType->getName()],['class'=>'btn btn-info']);
            },
            'format' => 'html',
        ],
    ],
]);?>
