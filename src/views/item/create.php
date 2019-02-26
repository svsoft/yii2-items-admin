<?php

/**
 * @var \svsoft\yii\items\entities\Item $item
 * @var \svsoft\yii\items\forms\ItemForm $itemForm
 * @var \svsoft\yii\items\entities\ItemType $itemType
 *
 */
$this->params['breadcrumbs'][] = ['label' => 'Типы', 'url' => ['types']];
$this->params['breadcrumbs'][] = ['label' => 'Элементы', 'url' => ['index', 'type'=>$itemType->getName()]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="item-index box box-primary">
    <div class="box-header with-border">
    </div>
    <div class="box-body">

        <?$form = \svsoft\yii\items\widgets\ItemFormWidget::begin([
            'itemForm' => $itemForm,
        ]);
        ?>

        <?foreach($form->fields() as $field):?>
            <?=$field?>
        <?endforeach;?>

        <?=\yii\helpers\Html::submitButton('Сохранить')?>

        <? \svsoft\yii\items\widgets\ItemFormWidget::end()?>

    </div>
</div>