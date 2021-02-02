<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Transaction;
use api\modules\v1\models\User;
use Yii;
use yii\data\Pagination;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Transaction Controller API
 */
class TransactionController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Transaction';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options', 'create', 'get-by-type', 'view']
        ];

        return $behaviors;
    }

    public function actionGetByType()
    {
        $request = Yii::$app->request;
        $searchType = $request->post('type');
        $paginationPageSize = $request->post('per-page');
        $paginationPage = $request->post('page');

        if (!$searchType || !$paginationPageSize || !$paginationPage) {
            throw new BadRequestHttpException('Basic input not provided');
        } else {
            if ($searchType === 'farma') {
                $searchTypeId = "1";
            } elseif ($searchType === 'pet') {
                $searchTypeId = "2";
            } else {
                throw new BadRequestHttpException('Bad request: type not provided');
            }
        }

        $queryCompanies = Transaction::find()
            ->joinWith('location')
            ->andWhere(['=', 'business_type', $searchTypeId]);

        $countQuery = clone $queryCompanies;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $paginationPageSize, 'page' => $paginationPage - 1]);
        $models = $queryCompanies->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        header('X-Pagination-Total-Count: '. $pages->totalCount);
        header('X-Pagination-Per-Page: '. $pages->getPageSize());

        return $models;
    }
}
