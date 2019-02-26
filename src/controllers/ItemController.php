<?php

namespace svsoft\yii\items\admin\controllers;

use svsoft\yii\items\exceptions\ItemNotFoundException;
use svsoft\yii\items\exceptions\ItemTypeNotFoundException;
use svsoft\yii\items\exceptions\ValidationErrorException;
use svsoft\yii\items\repositories\ItemRepository;
use svsoft\yii\items\repositories\ItemTypeRepository;
use svsoft\yii\items\services\ItemManager;
use svsoft\yii\items\services\ItemTypeManager;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemController extends Controller
{

    /**
     * @var ItemManager
     */
    protected $itemManager;

//    /**
//     * @var Items
//     */
//    protected $items;

    public function init()
    {
//        /** @var Items $items */
//        $this->items = Yii::$container->get(Items::class);


        $this->itemManager = Yii::$container->get(ItemManager::class);


        parent::init();
    }

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


    public function actionIndex($type)
    {
        $itemType = $this->getItemType($type);

        $query = $this->itemManager->createQuery($itemType);
        $query->indexBy('id');

        $dataProvider = new ArrayDataProvider([
            'models' => $query->all(),
            'totalCount' => $query->count(),
        ]);

        return $this->render('index', ['dataProvider'=>$dataProvider, 'itemType'=>$itemType] );
    }

    public function actionCreate($type)
    {
        $itemType = $this->getItemType($type);

        $itemForm = $this->itemManager->createForm($itemType);

        if ($itemForm->load(Yii::$app->request->post()))
        {
            $itemForm->loadFiles($_FILES);

            try
            {
                $this->itemManager->create($itemForm);
                return $this->refresh();
            }
            catch(ValidationErrorException $exception)
            {

            }
        }

        return $this->render('create',['itemForm'=>$itemForm, 'itemType'=>$itemType]);
    }

    public function actionUpdate($id)
    {
        $item = $this->getItem($id);

//        /** @var Decorator $decorator */
//        $decorator = Yii::$container->get(Decorator::class);
//        /** @var Cacher $cacher */
//        $cacher = Yii::$container->get(Cacher::class);
//
//        $cacher->cleanByItemType($item->getItemTypeId());


        /** @var ItemTypeRepository $itemTypeRepository */
        $itemTypeRepository = Yii::$container->get(ItemTypeRepository::class);
        $itemType = $itemTypeRepository->get($item->getItemTypeId());

        $itemForm = $this->itemManager->createForm($itemType);
        $itemForm->setItem($item);

        if ($itemForm->load(Yii::$app->request->post()))
        {
            $itemForm->loadFiles($_FILES);

            try
            {
                $this->itemManager->update($itemForm);
                return $this->refresh();
            }
            catch(ValidationErrorException $exception)
            {

            }
        }

        return $this->render('update', ['itemForm'=>$itemForm, 'itemType'=>$itemType] );
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
