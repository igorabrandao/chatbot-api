<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Transaction;
use api\modules\v1\models\Wallet;
use GuzzleHttp\Exception\ServerException;
use Yii;
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
    // ***************************************************
    // ** Controller attributes
    // ***************************************************

    // Basic attributes
    public $modelClass = 'api\modules\v1\models\Transaction';

    // Fixer API
    private $BASE_URL = "https://api.exchangeratesapi.io/";
    private $API_KEY = "";

    // ***************************************************
    // ** Controller functions
    // ***************************************************

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options', 'create', 'convert-currency']
        ];

        return $behaviors;
    }

    /**
     * Function to log the transaction
     */
    private function logTransaction(
        $type_,
        $amount_,
        $converted_amount_,
        $origin_wallet_,
        $destiny_wallet_,
        $from_currency_,
        $to_currency_,
        $status_
    ) {
        // Set the new transaction log attributes
        $transaction = new Transaction();
        $transaction->type = $type_;
        $transaction->amount = $amount_;
        $transaction->converted_amount = $converted_amount_;
        $transaction->origin_wallet = $origin_wallet_;
        $transaction->destiny_wallet = $destiny_wallet_;
        $transaction->from_currency = $from_currency_;
        $transaction->to_currency = $to_currency_;
        $transaction->status = $status_;
        
        if (!$transaction->save()) {
            return $transaction->getFirstErrors();
        }

        return $transaction;
    }

    /**
     * Function to call an external API
     */
    public static function callAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * Function to receive message
     */
    public static function checkCurrencyExists($currency_)
    {
        $BASE_URL = "https://api.exchangeratesapi.io/";

        // Check the user message
        if (empty($currency_)) {
            throw new BadRequestHttpException('The currency cannot be empty.');
        } else {
            // Set the opration
            $operation = "latest";

            // Prepare the input
            $currency_ = strtoupper($currency_);

            // Prepare the request data
            $currencyData = array();
            $currencyData['base'] = $currency_;

            // Call the external API
            $result = self::callAPI('GET', $BASE_URL . $operation, $currencyData);

            if (isset($result)) {
                // Decode the json to array
                $result = json_decode($result, true);

                if (isset($result['error'])) {
                    // The currency does not exist
                    return false;
                } else {
                    // The currency exists
                    return true;
                }
            } else {
                throw new HttpException('It was not possible to contact the currency exchange server.');
            }
        }
    }

    /**
     * Function to receive message
     */
    public function actionConvertCurrency($from_currency_ = '', $to_currency_ = '', $amount_ = 0)
    {
        // Recevei the POST params
        $request = Yii::$app->request;

        if (strcmp($from_currency_, '') != 0 && strcmp($to_currency_, '') != 0 && $amount_ != 0) {
            $from_currency = $from_currency_;
            $to_currency = $to_currency_;
            $amount = $amount_;
        } else {
            $from_currency = $request->post('from_currency');
            $to_currency = $request->post('to_currency');
            $amount = $request->post('amount');
        }

        $result = null;

        // Check the mandatory fields
        if (empty($from_currency)) {
            throw new BadRequestHttpException('The origin currency cannot be empty.');
        } else if (empty($to_currency)) {
            throw new BadRequestHttpException('The destiny currency cannot be empty.');
        } else if (empty($amount)) {
            throw new BadRequestHttpException('The amount cannot be empty.');
        } else {
            // Set the opration
            $operation = "latest";

            // Prepare the input
            $from_currency = strtoupper($from_currency);
            $to_currency = strtoupper($to_currency);

            // Check if the both currency are valid
            if (!self::checkCurrencyExists($from_currency)) {
                throw new BadRequestHttpException('The currency ' . $from_currency . ' does not exist.');
            }

            if (!self::checkCurrencyExists($to_currency)) {
                throw new BadRequestHttpException('The currency ' . $to_currency . ' does not exist.');
            }

            // Prepare the request data
            $conversionData = array();
            $conversionData['base'] = $from_currency;
            $conversionData['symbols'] = $to_currency;

            // Call the external API
            $result = self::callAPI('GET', $this->BASE_URL . $operation, $conversionData);

            if (isset($result)) {
                // Decode the json to array
                $result = json_decode($result, true);
                $result['amount'] = $amount;
                $result['converted_amount'] = $amount * $result["rates"][$to_currency];
                $result['from_currency'] = $from_currency;
                $result['to_currency'] = $to_currency;

                // Log the transaction as complete
                $this->logTransaction(Transaction::CONVERSION, $result['amount'], $result['converted_amount'], '', '',
                $result['from_currency'], $result['to_currency'], Transaction::COMPLETE);
            } else {
                // Log the transaction as incomplete
                $this->logTransaction(Transaction::CONVERSION, $amount, 0, '', '', $from_currency, $to_currency, 
                Transaction::INCOMPLETE);

                throw new HttpException('It was not possible to contact the currency exchange server.');
            }
        }

        return $result;
    }

    /**
     * Function to deposit money into a certain wallet
     */
    public function actionDepositMoney()
    {
        // Recevei the POST params
        $request = Yii::$app->request;
        $user_id = $request->post('user_id');
        $amount_currency = $request->post('amount_currency');
        $wallet_currency = $request->post('wallet_currency');
        $amount = $request->post('amount');

        // Check the mandatory fields
        if (empty($user_id)) {
            throw new BadRequestHttpException('The user ID must be informed.');
        } else if (empty($amount_currency)) {
            throw new BadRequestHttpException('The amount currency must be informed.');
        } else if (empty($wallet_currency)) {
            throw new BadRequestHttpException('The wallet currency must be informed.');
        } else if (empty($amount)) {
            throw new BadRequestHttpException('The amount must be informed.');
        } else {
            // Prepare the input
            $amount = number_format(floatval($amount), 2);
            $amount_currency = strtoupper($amount_currency);
            $wallet_currency = strtoupper($wallet_currency);

            // Try to retrieve the wallet info
            $wallet = Wallet::find()->where(['user_id' => $user_id])->andWhere(['currency' => $wallet_currency])->one();

            // Check if the wallet exists
            if (isset($wallet) && !empty($wallet)) {
                // Check if needs to convert
                if (strcmp($amount_currency, $wallet_currency) != 0) {
                    // Gets the new currency quotation
                    $result = $this->actionConvertCurrency($amount_currency, $wallet_currency, $amount);

                    // Check if the conversion happend succesfully
                    if (isset($result) && !empty($result)) {
                        // Set the new wallet balance with conversion
                        $wallet->balance += $result['converted_amount'];
                    } else {
                        throw new HttpException('It was not possible to contact the currency exchange server.');
                    }
                } else {
                    // Set the new wallet balance without conversion
                    $wallet->balance += $amount;
                }

                // Update the the wallet balance
                if ($wallet->save()) {
                    // Log the transaction as complete
                    if (isset($result) && !empty($result)) {
                        $this->logTransaction(Transaction::DEPOSIT, $amount, $result['converted_amount'], '', $wallet->code,
                        $amount_currency, $wallet_currency, Transaction::COMPLETE);
                    } else {
                        $this->logTransaction(Transaction::DEPOSIT, $amount, 0, '', $wallet->code,
                        $amount_currency, $wallet_currency, Transaction::COMPLETE);
                    }

                    // Return the wallet updated
                    return $wallet;
                } else {
                    // Log the transaction as incomplete
                    if (isset($result) && !empty($result)) {
                        $this->logTransaction(Transaction::DEPOSIT, $amount, $result['converted_amount'], '', $wallet->code,
                        $amount_currency, $wallet_currency, Transaction::INCOMPLETE);
                    } else {
                        $this->logTransaction(Transaction::DEPOSIT, $amount, 0, '', $wallet->code,
                        $amount_currency, $wallet_currency, Transaction::INCOMPLETE);
                    }

                    throw new ServerErrorHttpException("It wasn't possible to complete the deposit");
                }
            } else {
                throw new BadRequestHttpException('The wallet ' . $wallet_currency . ' was not found.');
            }
        }
    }

    /**
     * Function to deposit money into a certain wallet
     */
    public function actionWithdrawMoney()
    {
        // Recevei the POST params
        $request = Yii::$app->request;
        $user_id = $request->post('user_id');
        $amount_currency = $request->post('amount_currency');
        $wallet_currency = $request->post('wallet_currency');
        $amount = $request->post('amount');

        // Check the mandatory fields
        if (empty($user_id)) {
            throw new BadRequestHttpException('The user ID must be informed.');
        } else if (empty($amount_currency)) {
            throw new BadRequestHttpException('The amount currency must be informed.');
        } else if (empty($wallet_currency)) {
            throw new BadRequestHttpException('The wallet currency must be informed.');
        } else if (empty($amount)) {
            throw new BadRequestHttpException('The amount must be informed.');
        } else {
            // Prepare the input
            $amount = number_format(floatval($amount), 2);
            $amount_currency = strtoupper($amount_currency);
            $wallet_currency = strtoupper($wallet_currency);

            // Try to retrieve the wallet info
            $wallet = Wallet::find()->where(['user_id' => $user_id])->andWhere(['currency' => $wallet_currency])->one();

            // Check if the wallet exists
            if (isset($wallet) && !empty($wallet)) {
                // Check if needs to convert
                if (strcmp($amount_currency, $wallet_currency) != 0) {
                    // Gets the new currency quotation
                    $result = $this->actionConvertCurrency($amount_currency, $wallet_currency, $amount);

                    // Check if the conversion happend succesfully
                    if (isset($result) && !empty($result)) {
                        // Check if the wallet have enough fund
                        if ($wallet->balance >= $result['converted_amount']) {
                            // Perform the balance withdraw with conversion
                            $wallet->balance -= $result['converted_amount'];
                        } else {
                            // Log the transaction as complete
                            $this->logTransaction(Transaction::WITHDRAW, $amount, $result['converted_amount'], $wallet->code, '',
                            $amount_currency, $wallet_currency, Transaction::INCOMPLETE);

                            throw new BadRequestHttpException('The wallet ' . $wallet_currency . ' does not have enough fund for this operation.');
                        }
                    } else {
                        throw new HttpException('It was not possible to contact the currency exchange server.');
                    }
                } else {
                    // Check if the wallet have enough fund
                    if ($wallet->balance >= $amount) {
                        // Perform the balance withdraw without conversion
                        $wallet->balance -= $amount;
                    } else {
                        // Log the transaction as incomplete
                        $this->logTransaction(Transaction::WITHDRAW, $amount, 0, $wallet->code, '',
                        $amount_currency, $wallet_currency, Transaction::INCOMPLETE);

                        throw new BadRequestHttpException('The wallet ' . $wallet_currency . ' does not have enough fund for this operation.');
                    }
                }

                // Update the the wallet balance
                if ($wallet->save()) {
                    // Log the transaction as complete
                    if (isset($result) && !empty($result)) {
                        $this->logTransaction(Transaction::WITHDRAW, $amount, $result['converted_amount'], $wallet->code, '',
                        $amount_currency, $wallet_currency, Transaction::COMPLETE);
                    } else {
                        $this->logTransaction(Transaction::WITHDRAW, $amount, 0, $wallet->code, '',
                        $amount_currency, $wallet_currency, Transaction::COMPLETE);
                    }

                    // Return the wallet updated
                    return $wallet;
                } else {
                    // Log the transaction as incomplete
                    if (isset($result) && !empty($result)) {
                        $this->logTransaction(Transaction::WITHDRAW, $amount, $result['converted_amount'], $wallet->code, '',
                        $amount_currency, $wallet_currency, Transaction::INCOMPLETE);
                    } else {
                        $this->logTransaction(Transaction::WITHDRAW, $amount, 0, $wallet->code, '',
                        $amount_currency, $wallet_currency, Transaction::INCOMPLETE);
                    }

                    throw new ServerErrorHttpException("It wasn't possible to complete the withdraw");
                }
            } else {
                throw new BadRequestHttpException('The wallet ' . $wallet_currency . ' was not found.');
            }
        }
    }

    /**
     * Function to deposit money into a certain wallet
     */
    public function actionShowWalletBalance()
    {
        // Recevei the POST params
        $request = Yii::$app->request;
        $user_id = $request->post('user_id');
        $currency = $request->post('currency');

        // Check the mandatory fields
        if (empty($user_id)) {
            throw new BadRequestHttpException('The user ID must be informed.');
        } else if (empty($currency)) {
            throw new BadRequestHttpException('The wallet currency must be informed.');
        } else {
            // Prepare the input
            $currency = strtoupper($currency);

            // Set the wallet object
            $wallet = null;

            // Check whether retrieve all wallets or a specific one
            if (strcmp($currency, 'ALL') == 0) {
                // Try to retrieve all wallets info
                $wallet = Wallet::find()->where(['user_id' => $user_id])->all();

                // Log the transaction as complete
                $this->logTransaction(Transaction::SHOW_BALANCE, 0, 0, 'ALL', '', '', '', Transaction::COMPLETE);
            } else {
                // Try to retrieve a specific wallet info
                $wallet = Wallet::find()->where(['user_id' => $user_id])->andWhere(['currency' => $currency])->one();

                // Log the transaction as complete
                if (isset($wallet) && !empty($wallet)) {
                    $this->logTransaction(Transaction::SHOW_BALANCE, 0, 0, $wallet->code, '', $currency, '', Transaction::COMPLETE);
                } else {
                    $this->logTransaction(Transaction::SHOW_BALANCE, 0, 0, '', '', $currency, '', Transaction::COMPLETE);
                }
            }

            return $wallet;
        }
    }
}
