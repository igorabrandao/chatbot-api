<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wallet".
 *
 * @property int $id
 * @property int $number
 * @property int $user_id
 * @property string $currency
 * @property string $balance
 * @property int $isDefault
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
            [['number', 'user_id', 'currency', 'balance', 'isDefault'], 'required'],
            [['balance'], 'number'],
            [['currency'], 'string'],
            [['number', 'user_id', 'isDefault'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['wallet_id' => 'id']],
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
            'number' => 'Wallet Number',
            'user_id' => 'User ID',
            'currency' => 'Wallet Currency',
            'balance' => 'Wallet Balance',
            'isDefault' => 'Is Default Wallet',
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
    public function getLocation()
    {
        return $this->hasOne(User::className(), ['wallet_id' => 'id']);
    }

    /**
     * @return boolean
     */
    public function isDefault()
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
