<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wallet".
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property string $currency
 * @property string $balance
 * @property int $is_default
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Wallet extends \yii\db\ActiveRecord
{
    const ISDEFAULT = 1;
    const ISNOTDEFAULT = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'user_id', 'currency', 'balance', 'is_default'], 'required'],
            [['balance'], 'number'],
            [['code', 'currency'], 'string'],
            [['user_id', 'is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'code' => 'Wallet Code',
            'user_id' => 'User ID',
            'currency' => 'Wallet Currency',
            'balance' => 'Wallet Balance',
            'is_default' => 'Is Default Wallet',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["user"] = "user";
        return $extraFields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['wallet_id' => 'id']);
    }

    /**
     * @return boolean
     */
    public function is_default()
    {
        return $this->is_default == self::ISDEFAULT;
    }

    /**
     * @return boolean
     */
    public function isNotDefault()
    {
        return $this->is_default == self::ISNOTDEFAULT;
    }
}
