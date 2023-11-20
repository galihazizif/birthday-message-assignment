<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_type".
 *
 * @property int $id
 * @property string $name
 * @property string $template
 */
class MessageType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'template'], 'required'],
            [['template'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'template' => 'Template',
        ];
    }
}
