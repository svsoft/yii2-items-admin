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
                    'value' => function(\svsoft\yii\items\entities\Field $field) {
                        return $field->getId();
                    },
                    'label' => 'Ид',

                ],
                [
                    'value' => function(\svsoft\yii\items\entities\Field $field) {
                        return $field->getName();
                    },
                    'label' => 'Название'
                ],
                [
                    'value' => function(\svsoft\yii\items\entities\Field $field) {
                        return $field->getType()->getId();
                    },
                    'label' => 'Тип'
                ],
                [
                    'value' => function(\svsoft\yii\items\entities\Field $field) {
                        return http_build_query($field->getType()->getParams(), null, '; ');
                    },
                    'label' => 'Параметры'
                ],
            ],
        ]);?>
    </div>
</div>
