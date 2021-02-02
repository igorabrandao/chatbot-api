<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Wallet;
use api\modules\v1\models\User;
use Yii;
use yii\data\Pagination;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Wallet Controller API
 */
class WalletController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Wallet';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options', 'create', 'get-by-currency', 'view']
        ];

        return $behaviors;
    }

    public function actionGetByCurrency()
    {
        $request = Yii::$app->request;
        $searchCurrency = $request->post('currency');
        $paginationPageSize = $request->post('per-page');
        $paginationPage = $request->post('page');

        if (!$searchCurrency || !$paginationPageSize || !$paginationPage) {
            throw new BadRequestHttpException('Basic input not provided');
        } else {
            if ($searchCurrency === 'farma') {
                $searchCurrencyId = "1";
            } elseif ($searchCurrency === 'pet') {
                $searchCurrencyId = "2";
            } else {
                throw new BadRequestHttpException('Bad request: currency not provided');
            }
        }

        $queryCompanies = Wallet::find()
            ->joinWith('location')
            ->andWhere(['=', 'business_currency', $searchCurrencyId]);

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

    /**
     * Set wallet status to open.
     * @return string Actual store status
     * @throws HttpException
     */
    public function actionSetDefaultWallet()
    {
        $userId = \Yii::$app->user->id;
        $user = User::findOne($userId);
        $wallet = Wallet::findOne($user->wallet_id);

        $wallet->is_open = Wallet::ISDEFAULT;

        if ($wallet->save()) {
            return "true";
        } else {
            throw new ServerErrorHttpException();
        }
    }

    /**
     * Set wallet status to closed.
     * @return string Actual store status
     * @throws HttpException
     */
    public function actionRemoveDefaultWallet()
    {
        $userId = \Yii::$app->user->id;
        $user = User::findOne($userId);
        $wallet = Wallet::findOne($user->wallet_id);

        $wallet->is_open = Wallet::ISNOTDEFAULT;

        if ($wallet->save()) {
            return "false";
        } else {
            throw new ServerErrorHttpException();
        }
    }

    public function actionGetStoreProfile()
    {
        $request = Yii::$app->request;
        $walletId = $request->post('wallet_id');
        $paginationPageSize = $request->post('per-page');
        $paginationPage = $request->post('page');

        if (!$walletId || !$paginationPageSize || !$paginationPage) {
            throw new BadRequestHttpException('Basic input not provided');
        }

        $queryWalletProfile = Wallet::find()
            ->joinWith('location')
            ->joinWith('merchandises.product')
            ->where(['=', 'wallet.id', $walletId]);

        $countQuery = clone $queryWalletProfile;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $paginationPageSize, 'page' => $paginationPage - 1]);
        $models = $queryWalletProfile->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        header('X-Pagination-Total-Count: ' . $pages->totalCount);
        header('X-Pagination-Per-Page: ' . $pages->getPageSize());

        return $models;
    }
}
