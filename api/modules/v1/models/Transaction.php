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
 * @property string $converted_amount
 * @property string $origin_wallet
 * @property string $destiny_wallet
 * @property string $from_currency
 * @property string $to_currency
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    // Transaction type consts
    const DEPOSIT = 'Deposit';
    const WITHDRAW = 'Withdraw';
    const SHOW_BALANCE = 'Show_balance';
    const CONVERSION = 'Conversion';

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
            [['type', 'amount', 'status'], 'required'],
            [['amount', 'converted_amount'], 'number'],
            [['status'], 'integer'],
            [['type', 'origin_wallet', 'destiny_wallet', 'from_currency', 'to_currency'], 'string'],
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
            'type' => 'Transaction Type',
            'amount' => 'Transaction Amount',
            'converted_amount' => 'Transaction Converted Amount',
            'origin_wallet' => 'Transaction Origin Wallet',
            'destiny_wallet' => 'Transaction Destiny Wallet',
            'from_currency' => 'From Currency',
            'to_currency' => 'To Currency',
            'status' => 'Transaction Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        return $extraFields;
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
    public function isCurrencyConversion()
    {
        return $this->type == self::CONVERSION;
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
