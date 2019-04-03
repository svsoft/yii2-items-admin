<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \svsoft\yii\items\entities\Item $item
 * @var \svsoft\yii\items\forms\ItemForm $itemForm
 * @var \svsoft\yii\items\entities\ItemType $itemType
 * @var \svsoft\yii\items\admin\components\ItemRelation $relation
 *
 */


/** @var \yii\web\Controller $controller */
$controller = $this->context;
/** @var $module \svsoft\yii\items\admin\ItemsAdminModule */
$module = $controller->module;

$label = $module->labelManager->getLabel($itemType);

$config  = $module->getItemFormConfig($itemType->getName());

$itemTypeLabel = \yii\helpers\Inflector::camel2words( $itemType->getName(), true);

if ($relation)
    $this->params['breadcrumbs'][] = ['label'=>$relation->getItem(), 'url'=> ['update','id'=>$relation->getItemId()] ];

$this->params['breadcrumbs'][] = ['label' => Yii::t('items', $label->items), 'url' => ['index', 'type'=>$itemType->getName(), 'relation' => (string)$relation]];

$this->params['breadcrumbs'][] = ['label'=>$item];

$this->beginBlock('content-header');
echo Yii::t('items', $label->updateItemPage);
echo Html::tag('small',$item);
$this->endBlock();

$this->title = Yii::t('items', $label->updateItemPage) . '(' . $item . ')';
?>

<?if ($relatedContent = \svsoft\yii\items\widgets\RelatedItemsWidget::widget(['item' => $item, 'labelManager' => $module->labelManager])):?>
<div class="item-index box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Данные</h3>
    </div>
    <div class="box-body">
        <?=$relatedContent?>
    </div>
</div>
<?endif;?>

<?$form = \svsoft\yii\items\widgets\ItemFormWidget::begin(\yii\helpers\ArrayHelper::merge([
    'itemForm' => $itemForm,
    'labels'   => $label->fields,
], $config));
?>

<?=$form->renderBlocks()?>

<? \svsoft\yii\items\widgets\ItemFormWidget::end()?>
