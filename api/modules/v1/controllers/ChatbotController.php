<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use api\modules\v1\controllers\UserController;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use api\helpers\StringHelper;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Chatbot Controller API
 */
class ChatbotController extends ActiveController
{
    // ***************************************************
    // ** Controller attributes
    // ***************************************************

    // Basic attributes
    public $modelClass = '';

    // Chatbot specific attributes
    private $botName = 'boot';

    // Chatbot intents
    private $loginIntent = [
        'login', 'signin', 'sign-in', 'sign in', 'logon', 'log-on', 'enter', 'access', 'enter', 'open', 
        'connect', 'auth', 'connection', 'enroll', 'authenticate', 'subscribe', 'in'
    ];

    private $logoutIntent = [
        'logout', 'signout', 'sign-out', 'sign out', 'log-out', 'exit', 'quit', 'away', 'close', 
        'disconnect', 'disconnection', 'unroll', 'unauthenticate', 'unsubscribe', 'out'
    ];

    private $registerIntent = [
        'register', 'signup', 'sign-up', 'sign up', 'create account', 'account', 'roll', 'record', 'set account',
        'submit', 'engage', 'recruit', 'engage', 'take on', 'admit', 'lay on', 'employ', 'make', 'assign', 
        'instal', 'install', 'establish', 'constitute', 'hire', 'invest in', 'start', 'begin', 'init', 'initialize'
    ];

    private $quotationIntent = [
        'convert', 'quotation', 'quote', 'exchange', 'change', 'money', 'cash', 'paper', 
        'bill', 'coin', 'specie', 'dollar', 'euro', 'real', 'usd', 'eur', 'brl'
    ];

    private $setCurrencyIntent = [
        'set currency', 'currency', 'default', 'current', 'set', 'wallet', 'create wallet'
    ];

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
            'except' => ['options', 'generate-welcome-message', 'receive-message']
        ];

        return $behaviors;
    }

    /**
     * Function to send messages to the user
     * 
     * @param message_ Message to the user
     */
    private function sendMessage(string $message_) {
        // Set the return message to the user
        $message = array();
        $message['sender'] = $this->botName;
        $message['message'] = $message_;
        return $message;
    }

    /**
     * Function to generate the welcome message
     */
    public function actionGenerateWelcomeMessage()
    {
        $welcomeMessage = array();
        $welcomeMessage['sender'] = $this->botName;
        $welcomeMessage['message'] = 'Hey there, we are ready to rumble!';
        return $welcomeMessage;
    }

    /**
     * Function to receive message
     */
    public function actionReceiveMessage()
    {
        // Recevei the POST params
        $request = Yii::$app->request;
        $message = $request->post('message');
        $data = $request->post('data');

        // Check the user message
        if (empty($message)) {
            throw new BadRequestHttpException('The message cannot be empty.');
        } else {
            $actionToPerform = '';

            // Prepare the message
            $message = strtolower($message);

            // Perform the message screening
            $words = preg_split('/ +/', $message);

            // Check in which intent the message matches
            foreach ($words as $item) {
                // Login
                if (in_array($item, $this->loginIntent)) {
                    $actionToPerform = 'login';
                    break;
                }

                if (in_array($item, $this->registerIntent)) {
                    $actionToPerform = 'register';
                    break;
                }

                // Logout
                if (in_array($item, $this->logoutIntent)) {
                    $actionToPerform = 'logout';
                    break;
                }

                // Set currency
                if (in_array($item, $this->setCurrencyIntent)) {
                    $actionToPerform = 'setCurrency';
                    break;
                }

                // Quotation
                if (in_array($item, $this->quotationIntent)) {
                    $actionToPerform = 'quotation';
                    break;
                }
            }
        }

        // Send a message to the user
        return $this->sendMessage($actionToPerform);
    }
}
