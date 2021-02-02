<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Transaction;
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
     * Function to call an external API
     */
    function callAPI($method, $url, $data = false)
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
    public function actionConvertCurrency()
    {
        // Recevei the POST params
        $request = Yii::$app->request;
        $from_currency = $request->post('from_currency');
        $to_currency = $request->post('to_currency');
        $amount = $request->post('amount');

        $result = null;

        // Check the user message
        if (empty($from_currency)) {
            throw new BadRequestHttpException('The origin currency cannot be empty.');
        } else if (empty($to_currency)) {
            throw new BadRequestHttpException('The destiny currency cannot be empty.');
        } else if (empty($amount)) {
            throw new BadRequestHttpException('The amount cannot be empty.');
        } else {
            // Set the opration
            $operation = "latest";

            // Prepare the request data
            $conversionData = array();
            $conversionData['base'] = $from_currency;
            $conversionData['symbols'] = $to_currency;

            // Call the external API
            $result = $this->callAPI('GET', $this->BASE_URL . $operation, $conversionData);

            if (isset($result)) {
                // Decode the json to array
                $result = json_decode($result, true);
                $result['original_amount'] = $amount;
                $result['converted_amount'] = $amount * $result["rates"][$to_currency];
            } else {
                throw new HttpException('It was not possible to contact the currency exchange server.');
            }
        }

        return $result;
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

        header('X-Pagination-Total-Count: ' . $pages->totalCount);
        header('X-Pagination-Per-Page: ' . $pages->getPageSize());

        return $models;
    }
}
