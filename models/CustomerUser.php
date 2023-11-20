<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_user".
 *
 * @property int $id
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $location
 * @property int $time_offset
 */
class CustomerUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'firstname', 'lastname', 'birthday','time_offset'], 'required'],
            ['email', 'unique'],
            [['birthday','location','time_offset'], 'safe'],
            [['time_offset'], 'integer'],
            [['email', 'firstname', 'lastname', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'email',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'birthday' => 'Birthday',
            'location' => 'Location',
            'time_offset' => 'Time Offset',
        ];
    }
}
