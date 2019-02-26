<?php
namespace svsoft\yii\items\admin\controllers;


use svsoft\yii\items\repositories\ItemTypeRepository;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class ItemTypeController extends Controller
{
    function actionIndex()
    {
        /** @var ItemTypeRepository $itemTypeRepository */
        $itemTypeRepository = \Yii::$container->get(ItemTypeRepository::class);

        $itemTypes = $itemTypeRepository->getAll();

        $dataProvider = new ArrayDataProvider(['models' => $itemTypes]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ] );
    }
}