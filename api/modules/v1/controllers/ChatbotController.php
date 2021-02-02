<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use Moip\Auth\Connect;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Chatbot Controller API
 */
class ChatbotController extends ActiveController
{
    public $modelClass = '';
    private $botName = 'Money Maker';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options', 'generate-welcome-message']
        ];

        return $behaviors;
    }

    public function actionGenerateWelcomeMessage()
    {
        $welcomeMessage = array();
        $welcomeMessage['sender'] = $this->botName;
        $welcomeMessage['message'] = 'Welcome user';
        $welcomeMessage['date'] = date('h:i:s');

        return $welcomeMessage;
    }
}
