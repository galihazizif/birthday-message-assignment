<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail_schedule".
 *
 * @property int $id
 * @property int $message_type
 * @property string $subject
 * @property string $recipient
 * @property string $body
 * @property string $to_sent_at
 * @property int $status
 */
class MailSchedule extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    const STATUS_FAILED = 2;
    const STATUS_SENT = 3;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_type', 'subject', 'recipient', 'body', 'to_sent_at', 'status'], 'required'],
            [['message_type', 'status'], 'integer'],
            [['body'], 'string'],
            [['to_sent_at'], 'safe'],
            [['subject', 'recipient'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_type' => 'Message Type',
            'subject' => 'Subject',
            'recipient' => 'Recipient',
            'body' => 'Body',
            'to_sent_at' => 'To Sent At',
            'status' => 'Status',
        ];
    }
}
