<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ingress_storage".
 *
 * @property int $id
 * @property string $ingress
 * @property int $validated
 * @property string $request_from
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class IngressStorage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingress_storage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ingress', 'request_from', 'user_id'], 'required'],
            [['validated', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ingress', 'request_from'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'ingress' => 'Ingress',
            'validated' => 'Validated',
            'request_from' => 'Request From',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
