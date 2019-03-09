<?php

/**
 * @var \yii\web\View $this
 * @var \svsoft\yii\items\entities\Item $item
 * @var \svsoft\yii\items\forms\ItemForm $itemForm
 * @var \svsoft\yii\items\entities\ItemType $itemType
 * @var \svsoft\yii\items\admin\components\ItemRelation $relation
 */

/** @var \yii\web\Controller $controller */
$controller = $this->context;
/** @var $module \svsoft\yii\items\admin\ItemsAdminModule */
$module = $controller->module;
$label = $module->labelManager->getLabel($itemType);

$itemTypeLabel = \yii\helpers\Inflector::camel2words( $itemType->getName(), true);

if ($relation)
    $this->params['breadcrumbs'][] = ['label'=>$relation->getItem(), 'url'=> ['update','id'=>$relation->getItemId()] ];

$this->params['breadcrumbs'][] = ['label' => Yii::t('items', $label->items), 'url' => ['index', 'type'=>$itemType->getName(), 'relation' => (string)$relation]];

$this->params['breadcrumbs'][] = ['label'=>'Добавление'];

$this->beginBlock('content-header');
echo Yii::t('items', $label->addItemPage);
$this->endBlock();

$this->title = $label->addItemPage;

?>

<div class="item-index box box-primary">
    <div class="box-header with-border">
    </div>
    <div class="box-body">

        <?$form = \svsoft\yii\items\widgets\ItemFormWidget::begin([
            'itemForm' => $itemForm,
            'labels' => $label->fields,
        ]);
        ?>

        <?foreach($form->fields() as $field):?>
            <?=$field?>
        <?endforeach;?>

        <?=\yii\helpers\Html::submitButton('Сохранить')?>

        <? \svsoft\yii\items\widgets\ItemFormWidget::end()?>

    </div>
</div>