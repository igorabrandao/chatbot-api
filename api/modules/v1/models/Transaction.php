<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $type
 * @property string $amount
 * @property int $origin_wallet
 * @property int $destiny_wallet
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    // Transaction type consts
    const DEPOSIT = 'D';
    const WITHDRAW = 'W';
    const SHOW_BALANCE = 'SB';

    // Transaction status consts
    const COMPLETE = 1;
    const INCOMPLETE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'amount', 'origin_wallet', 'status'], 'required'],
            [['amount'], 'number'],
            [['origin_wallet', 'destiny_wallet', 'status'], 'integer'],
            [['type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['origin_wallet'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_id' => 'id']],
            [['destiny_wallet'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_id' => 'id']],
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
            'type' => 'Transaction Type',
            'amount' => 'Transaction Amount',
            'origin_wallet' => 'Transaction Origin Wallet',
            'destiny_wallet' => 'Transaction Destiny Wallet',
            'status' => 'Transaction Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields["origin_wallet"] = "origin_wallet";
        $extraFields["destiny_wallet"] = "destiny_wallet";
        return $extraFields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginWallet()
    {
        return $this->hasOne(Wallet::className(), ['origin_wallet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinyWallet()
    {
        return $this->hasOne(Wallet::className(), ['destiny_wallet_id' => 'id']);
    }

    /**
     * @return boolean
     */
    public function isDeposit()
    {
        return $this->type == self::DEPOSIT;
    }

    /**
     * @return boolean
     */
    public function isWithdraw()
    {
        return $this->type == self::WITHDRAW;
    }

    /**
     * @return boolean
     */
    public function isShowBalance()
    {
        return $this->type == self::SHOW_BALANCE;
    }

    /**
     * @return boolean
     */
    public function isComplete()
    {
        return $this->status == self::COMPLETE;
    }

    /**
     * @return boolean
     */
    public function isIncomplete()
    {
        return $this->status == self::INCOMPLETE;
    }
}
