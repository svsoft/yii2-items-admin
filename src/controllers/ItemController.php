<?php

namespace svsoft\yii\items\admin\controllers;

use svsoft\yii\items\admin\components\ItemRelation;
use svsoft\yii\items\admin\ItemsAdminModule;
use svsoft\yii\items\entities\Field;
use svsoft\yii\items\entities\ItemType;
use svsoft\yii\items\exceptions\FieldNotFoundException;
use svsoft\yii\items\exceptions\ItemNotFoundException;
use svsoft\yii\items\exceptions\ItemTypeNotFoundException;
use svsoft\yii\items\exceptions\ValidationErrorException;
use svsoft\yii\items\repositories\ItemRepository;
use svsoft\yii\items\repositories\ItemTypeRepository;
use svsoft\yii\items\services\ItemManager;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ItemController
 * @package svsoft\yii\items\admin\controllers
 * @property ItemsAdminModule $module
 */
class ItemController extends Controller
{
    /**
     * @var ItemManager
     */
    protected $itemManager;

    public function init()
    {
        $this->itemManager = Yii::$container->get(ItemManager::class);

        parent::init();
    }

    /**
     * @param $name
     *
     * @return ItemType
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function getItemType($name)
    {
        /** @var ItemTypeRepository $itemTypeRepository */
        $itemTypeRepository = Yii::$container->get(ItemTypeRepository::class);

        try
        {
            $type = $itemTypeRepository->getByName($name);
        }
        catch(ItemTypeNotFoundException $exception)
        {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $type;
    }

    /**
     * @param $id
     *
     * @return \svsoft\yii\items\entities\Item
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    protected function getItem($id)
    {
        /** @var ItemRepository $itemRepository */
        $itemRepository = Yii::$container->get(ItemRepository::class);

        try
        {
            $item = $itemRepository->get($id);
        }
        catch(ItemNotFoundException $exception)
        {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $item;
    }

    /**
     * @param ItemType $itemType
     * @param $relation
     *
     * @return ItemRelation
     * @throws NotFoundHttpException
     */
    protected function parseRelation(ItemType $itemType, $relation)
    {
        if (!$relation)
            return null;

        $ar = explode('-',$relation);

        $fieldName = ArrayHelper::getValue($ar, 0);
        $id = ArrayHelper::getValue($ar, 1);

        try
        {
            $field = $itemType->getFieldByName($fieldName);
        }
        catch(FieldNotFoundException $exception)
        {
            throw new NotFoundHttpException('Страница не найдена');
        }

        if ($field->getType()->getId() != Field::TYPE_ITEM)
            throw new NotFoundHttpException('Страница не найдена');

        return new ItemRelation($fieldName, $id);
    }

    public function actionIndex($type, $relation = '')
    {
        $itemType = $this->getItemType($type);

        $query = $this->itemManager->createQuery($itemType);
        $query->indexBy('id');

        if ($relation)
        {
            $relation = $this->parseRelation($itemType, $relation);
            $query->andWhere($relation->toArray());
        }

        $dataProvider = new ArrayDataProvider([
            'models' => $query->all(),
            'totalCount' => $query->count(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'itemType'     => $itemType,
            'relation'     => $relation,
        ]);
    }

    public function actionCreate($type, $relation = '')
    {
        $itemType = $this->getItemType($type);

        $itemForm = $this->itemManager->createSaveModel($itemType);
//        var_dump($itemForm);die();

        if ($relation = $this->parseRelation($itemType, $relation))
        {
            $itemForm->{$relation->fieldName} = $relation->itemId;
        }

        if ($itemForm->load(Yii::$app->request->post()))
        {
            $itemForm->loadFiles($_FILES);

            try
            {
                $itemForm->save();

                return $this->redirect(['item/index','type'=>$type, 'relation'=>(string)$relation]);
            }
            catch(ValidationErrorException $exception)
            {

            }
        }

        return $this->render('create',['itemForm'=>$itemForm, 'itemType'=>$itemType, 'relation'=>$relation]);
    }

    public function actionUpdate($id, $relation = '')
    {
        $item = $this->getItem($id);

        /** @var ItemTypeRepository $itemTypeRepository */
        $itemTypeRepository = Yii::$container->get(ItemTypeRepository::class);
        $itemType = $itemTypeRepository->get($item->getItemTypeId());

        $relation = $this->parseRelation($itemType, $relation);

        $itemForm = $this->itemManager->createSaveModel($itemType);

        $itemForm->setItem($item);

        if ($itemForm->load(Yii::$app->request->post()))
        {
            $itemForm->loadFiles($_FILES);

            try
            {
                $itemForm->save();

                return $this->refresh();
            }
            catch(ValidationErrorException $exception)
            {

            }
        }

        return $this->render('update', ['itemForm'=>$itemForm, 'itemType'=>$itemType,'item'=>$item, 'relation'=>$relation ] );
    }

    public function actionDelete($id)
    {
        $item = $this->getItem($id);

        $this->itemManager->delete($item);

        /** @var ItemTypeRepository $itemTypeRepository */
        $itemTypeRepository = Yii::$container->get(ItemTypeRepository::class);
        $itemType = $itemTypeRepository->get($item->getItemTypeId());

        return $this->redirect(['item/index','type'=>$itemType->getName()]);
    }
}
