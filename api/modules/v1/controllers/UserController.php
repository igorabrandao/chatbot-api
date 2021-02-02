<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\IngressStorage;
use api\modules\v1\models\User;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * User Controller API
 */
class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => [
                'create',
                'login',
                'register-client',
                'login-by-code',
                'check-ingress-code',
                'options',
                'recover-password',
                'check-reset-password-token',
                'reset-password',
                'register-delivery-request']
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
            'query' => User::find(),
            'pagination' => false
        ]);
        return $activeData;
    }

    /**
     * Logs in the user and return it's model
     * @return User
     * @throws UnauthorizedHttpException
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;
        $email = $request->post('email');
        $password = $request->post('password');

        /** @var User $user */
        $user = User::findOne(['email' => $email]);

        if (empty($email) || empty($password) || empty($user)) {
            throw new UnauthorizedHttpException('Wrong email and/or password.');
        }

        if (!empty($user)) {
            $hasInvalidPassword = (!Yii::$app->getSecurity()->validatePassword($password, $user->encrypted_password));

            if ($hasInvalidPassword) {
                throw new UnauthorizedHttpException('Wrong email and/or password.');
            }
        }

        if ($user->save()) {
            $this->generateAdminIngress($user);
            Yii::$app->user->login($user);
        }
        
        return $user;
    }

    public function generateAdminIngress($user)
    {
        $ingress_storage = new IngressStorage();

        $ingress_storage->ingress = uniqid();
        $ingress_storage->user_id = $user->id;
        $ingress_storage->request_from = Yii::$app->request->getUserIP();

        if ($ingress_storage->save()) {
            Yii::$app->mailer->compose()
                ->setSubject(Yii::$app->params['messages']['productName'] . ' - Token de acesso administrador')
                ->setFrom(Yii::$app->params['sender']['email'])
                ->setTo($user->email)
                ->setHtmlBody("
                <p>Olá!</p>
                <p>Você solicitou um novo acesso ao painel a partir de uma conta de administrador.</p>
                <p>Seu código de verificação para login é: <b>{$ingress_storage->ingress}</b></p>
                <p>Atenciosamente,<br/>Equipe " . Yii::$app->params['messages']['productName'] . "</p>
            ")->send();

            Yii::$app->response->setStatusCode(202);
            return [
                'status' => 202
            ];
        }
    }

    public function actionRecoverPassword()
    {
        $request = Yii::$app->request;

        $user = User::findOne(['email' => $request->post('email')]);

        if (!$request->post('email')) {
            throw new BadRequestHttpException('No email was given.');
        }
        if (!$user) {
            Yii::$app->response->setStatusCode(200);
            return [
                'status' => 200
            ];
        }

        if (empty($user->expiration_date_reset_token) || (strtotime('now') > strtotime($user->expiration_date_reset_token))) {
            $user->password_reset_token = Yii::$app->getSecurity()->generateRandomString();
            $user->expiration_date_reset_token = date('Y-m-d H:i:s', strtotime('+1 day'));
            $user->save(false, ['password_reset_token', 'expiration_date_reset_token']);
        }

        $resetPasswordUrl = Yii::$app->params['resetPasswordBaseUrl'] . $user->password_reset_token;

        Yii::$app->mailer->compose()
            ->setSubject(Yii::$app->params['messages']['productName'] . ' - Recuperação de senha')
            ->setFrom(Yii::$app->params['sender']['email'])
            ->setTo($user->email)
            ->setHtmlBody("
                <p>Olá!</p>
                <p>Você solicitou a recuperação de senha da sua conta.</p>
                <p>Clique neste link para cadastrar uma nova senha:<br><a href='{$resetPasswordUrl}'>{$resetPasswordUrl}</a></p>
                <p>Você tem até 24 horas para utilizar este link.</p>
                <p>Atenciosamente,<br/>Equipe " . Yii::$app->params['messages']['productName'] . "</p>
            ")
            ->send();

        Yii::$app->response->setStatusCode(200);
        return [
            'status' => 200
        ];

    }

    public function actionCheckResetPasswordToken()
    {
        $request = Yii::$app->request;
        $token = $request->post('password-reset-token');
        if (empty($token)) {
            throw new BadRequestHttpException('O "token de recuperação" está inválido.');
        }
        $user = User::findOne(['password_reset_token' => $request->post('password-reset-token')]);
        if (empty($user)) {
            throw new NotFoundHttpException('Usuário não encontrado.');
        } else if (strtotime('now') > strtotime($user->expiration_date_reset_token)) {
            throw new NotFoundHttpException('O Token expirou');
        } else {
            return $user;
        }
    }

    public function actionResetPassword()
    {
        $request = Yii::$app->request;

        $user = User::findOne(['password_reset_token' => $request->post('password-reset-token')]);

        if (!$request->post('password-reset-token') || !$user) {
            throw new BadRequestHttpException('Invalid reset token.');
        }

        if (!$request->post('password')) {
            throw new BadRequestHttpException('Invalid Password');
        }

        $user->encrypted_password = Yii::$app->getSecurity()->generatePasswordHash($request->post('password'));
        $user->expiration_date_reset_token = null;

        if ($user->save()) {
            Yii::$app->mailer->compose()
                ->setSubject(Yii::$app->params['messages']['productName'] . 'Sua senha foi alterada')
                ->setFrom(Yii::$app->params['sender']['email'])
                ->setTo($user->email)
                ->setHtmlBody("
                <p>Olá!</p>
                <p>Informamos que sua senha no serviço " . Yii::$app->params['messages']['productName'] . " foi alterada com sucesso.</p>
                <p>Caso essa alteração não tenha sido feita por você, entre em contato conosco, pois é possível que outra pessoa esteja usando sua conta.</p>
                <p> Atenciosamente,<br/> Equipe " . Yii::$app->params['messages']['productName'] . " </p>
            ")
                ->send();

            Yii::$app->response->setStatusCode(200);
            return [
                'status' => 200
            ];
        }
    }

    public function actionRegisterClient()
    {
        $request = Yii::$app->request;

        if (!$request->post('name') ||
            !$request->post('email') ||
            !$request->post('cpf') ||
            !$request->post('password') ||
            !$request->post('phone_area_code') ||
            !$request->post('birth_date') ||
            !$request->post('phone_number')) {
            throw new BadRequestHttpException('Campos obrigatórios precisam ser preenchidos.');
        }

        if (User::find()->where(['email' => $request->post('email')])->one()) {
            throw new BadRequestHttpException('O e-mail informado já foi cadastrado.');
        }

        /** @var User $user */
        $user = new User();
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->cpf = $request->post('cpf');
        $user->phone_area_code = $request->post('phone_area_code');
        $user->phone_number = $request->post('phone_number');
        $user->birth_date = $request->post('birth_date');
        $user->is_active = 1;
        $user->access_level = User::client;

        if (!$user->save()) {
            return $user->getFirstErrors();
        }

        return User::find()->where(['email' => $user->email])->one();
    }

    public function actionRegisterDeliveryRequest()
    {
        $request = Yii::$app->request;
        $name = $request->post('name');
        $phone = $request->post('phone');
        $email = $request->post('email');
        $address = $request->post('address');

        $adminEmails = [];
        foreach (User::find()->all() as $user) {
            if ($user->access_level == User::sysAdmin) {
                $adminEmails[] = $user->email;
            }
        }

        $subject = "Tenho interesse em ser Entregador - Contato";

        $body = '<h3>De: ' . $name . '</h3>
            <h4>Telefone: ' . $phone . '</h4>
            <h4>Email: ' . $email . '</h4>
            <hr>
            <h3>Endereço:</h3>
            <p>' . $address . '</p>';


        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['sender']['email'])
            ->setTo($adminEmails)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }
}
