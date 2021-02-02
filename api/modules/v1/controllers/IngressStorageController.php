<?php

namespace api\modules\v1\controllers;

use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Ingress Storage Controller API
 */
class IngressStorageController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\IngressStorage';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options']
        ];

        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() {
        $activeData = new ActiveDataProvider([
            'query' => \api\modules\v1\models\IngressStorage::find()->where(['user_id' => \Yii::$app->user->identity->id]),
            'pagination' => false
        ]);
        return $activeData;
    }
}
