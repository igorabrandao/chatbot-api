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

    /**
     * Function to register a new wallet
     */
    public function actionRegisterWallet()
    {
        $request = Yii::$app->request;

        // Check the required info
        if (
            !$request->post('user_id') ||
            !$request->post('currency')
        ) {
            throw new BadRequestHttpException('Mandatory fields must be filled.');
        }

        // Check if the user already have an wallet with the same currency
        if (Wallet::find()->where(['user_id' => $request->post('user_id')])
            ->andWhere(['currency' => $request->post('currency')])->one()) {
            throw new BadRequestHttpException('The user already have one ' . strtoupper($request->post('currency')) . ' wallet.');
        }

        // Set the new wallet attributes
        $wallet = new Wallet();
        $wallet->code = md5(uniqid(rand(), true));
        $wallet->user_id = $request->post('user_id');
        $wallet->currency = strtoupper($request->post('currency'));
        $wallet->balance = 0.00;
        
        // Check if the user already have a default wallet
        if (Wallet::find()->where(['user_id' => $request->post('user_id')])
            ->andWhere(['is_default' => 1])->one()) {
            $wallet->is_default = Wallet::ISNOTDEFAULT;
        } else {
            $wallet->is_default = Wallet::ISDEFAULT;
        }

        if (!$wallet->save()) {
            return $wallet->getFirstErrors();
        }

        return $wallet;
    }

    /**
     * Set wallet status to default
     * 
     * @return string Actual store status
     * @throws HttpException
     */
    public function actionSetDefaultWallet($wallet_code_)
    {
        // Retrieve the wallet info
        $wallet = Wallet::find()->where(['code' => $wallet_code_])->one();

        if (!isset($wallet)) {
            throw new BadRequestHttpException('The wallet ' . $wallet_code_ . ' does not exist.');
        }

        // Set the wallet as default
        $wallet->is_default = Wallet::ISDEFAULT;

        // Save the wallet
        if ($wallet->save()) {
            // Get the user ID
            $userId = $wallet->user_id;

            // Retrieve all the user wallets
            $userWallets = Wallet::find()->where(['user_id' => $userId])
                ->andWhere(['<>','code', $wallet_code_])
                ->all();

            if (isset($userWallets)) {
                // Unset all user wallets except the current setted
                foreach ($userWallets as $item) {
                    $this->actionRemoveDefaultWallet($item->code);
                }
            }

            return true;
        } else {
            throw new ServerErrorHttpException();
        }
    }

    /**
     * Remove the wallet status as default
     * 
     * @return string Actual store status
     * @throws HttpException
     */
    private function actionRemoveDefaultWallet($wallet_code_)
    {
        // Retrieve the wallet info
        $wallet = Wallet::find()->where(['code' => $wallet_code_])->one();

        if (!isset($wallet)) {
            throw new BadRequestHttpException('The wallet ' . $wallet_code_ . ' does not exist.');
        }

        // Remove the default status
        $wallet->is_default = Wallet::ISNOTDEFAULT;

        // Save the operation
        if ($wallet->save()) {
            return true;
        } else {
            throw new ServerErrorHttpException();
        }
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

    
}
