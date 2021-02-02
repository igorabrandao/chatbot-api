<?php

namespace api\modules\v1\models;

use api\modules\v1\components\PushNotificationAndroid;
use api\modules\v1\components\PushNotificationIOSAPI;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $cpf
 * @property string $encrypted_password
 * @property string $access_token
 * @property string $password_reset_token
 * @property string $expiration_date_reset_token
 * @property int $is_active
 * @property string $birth_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Wallet[] $wallets
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'cpf', 'is_active'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'unique'],
            [['email', 'name', 'encrypted_password', 'access_token',
            'password_reset_token', 'expiration_date_reset_token',
            'birth_date'], 'string', 'max' => 255],
            [['cpf'], 'string', 'max' => 14],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'cpf' => 'Cpf',
            'encrypted_password' => 'Encrypted Password',
            'access_token' => 'Access Token',
            'password_reset_token' => 'Password Reset Token',
            'expiration_date_reset_token' => 'Expiration Date Reset Token',
            'is_active' => 'Is Active',
            'birth_date' => 'Birth Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $password = Yii::$app->request->post('password');
        $email = Yii::$app->request->post('email');
        $user = User::findOne(['email' => $email]);
        if ($insert) {
            if (empty($user) || !empty($password)) {
                $this->encrypted_password = Yii::$app->getSecurity()->generatePasswordHash($password);
            }
        } else {
            if (!empty($password)) {
                $this->encrypted_password = Yii::$app->getSecurity()->generatePasswordHash($password);
            }
        }

        if (empty($this->access_token)) {
            $this->access_token = Yii::$app->getSecurity()->generateRandomString();
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            if (isset($changedAttributes['is_active']) && $this->isDelivery()) {
                $this->sendChangedStatusEmail();
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["wallet"] = "wallet";
        return $extraFields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Wallet::className(), ['user_id' => 'id']);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled. The returned key will be stored on the
     * client side as a cookie and will be used to authenticate user even if PHP session has been expired.
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
}
