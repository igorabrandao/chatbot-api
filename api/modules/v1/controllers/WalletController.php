<?php

namespace api\modules\v1\controllers;
use api\modules\v1\controllers\TransactionController;
use api\modules\v1\models\Wallet;
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

        // Convert the currency to uppercase
        $currency = strtoupper($request->post('currency'));

        // Check if the user already have an wallet with the same currency
        if (Wallet::find()->where(['user_id' => $request->post('user_id')])
            ->andWhere(['currency' => $request->post('currency')])->one()) {
            throw new BadRequestHttpException('The user already have one ' . $currency . ' wallet.');
        }

        // Check if the currency is valid
        if (!TransactionController::checkCurrencyExists($currency)) {
            throw new BadRequestHttpException('The currency ' . $currency . ' does not exist.');
        }

        // Set the new wallet attributes
        $wallet = new Wallet();
        $wallet->code = md5(uniqid(rand(), true));
        $wallet->user_id = $request->post('user_id');
        $wallet->currency = $currency;
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
     * Check if the user has a specific wallet
     */
    public function actionCheckWallet()
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
        return Wallet::find()->where(['user_id' => $request->post('user_id')])
        ->andWhere(['currency' => $request->post('currency')])->one();
    }

    /**
     * Check if the user has a default wallet
     */
    public function actionCheckDefaultWallet()
    {
        $request = Yii::$app->request;

        // Check the required info
        if (
            !$request->post('user_id')
        ) {
            throw new BadRequestHttpException('The user ID field must be informed.');
        }

        // Retrieve the wallet info
        return Wallet::find()->where(['user_id' => $request->post('user_id')])
                ->andWhere(['is_default' => Wallet::ISDEFAULT])->one();
    }

    /**
     * Set wallet status to default
     * 
     * @throws HttpException
     */
    public function actionSetDefaultWallet()
    {
        $request = Yii::$app->request;

        // Check the required info
        if (
            !$request->post('code')
        ) {
            throw new BadRequestHttpException('The wallet code field must be informed.');
        }

        // Retrieve the wallet info
        $wallet = Wallet::find()->where(['code' => $request->post('code')])->one();

        if (!isset($wallet)) {
            throw new BadRequestHttpException('The wallet ' . $request->post('code') . ' does not exist.');
        }

        // Set the wallet as default
        $wallet->is_default = Wallet::ISDEFAULT;

        // Save the wallet
        if ($wallet->save()) {
            // Get the user ID
            $userId = $wallet->user_id;

            // Retrieve all the user wallets
            $userWallets = Wallet::find()->where(['user_id' => $userId])
                ->andWhere(['<>','code', $request->post('code')])
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
}
