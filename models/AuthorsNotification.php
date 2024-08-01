<?php

declare(strict_types=1);

namespace app\models;

use floor12\phone\PhoneValidator;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "authors_notification".
 *
 * @property int $id
 * @property int $author_id
 * @property string $phone_number
 * @property int $status
 *
 * @property Authors $author
 */
class AuthorsNotification extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_COMPLETE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'authors_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['author_id'], 'integer'],
            [['phone_number'], PhoneValidator::class],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Authors::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
            [['status'], 'default', 'value' => self::STATUS_NEW],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => 'ID',
            'author_id'    => 'Author ID',
            'phone_number' => 'Номер телефона',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }

    public function sentNotification(): void
    {
        $this->status = self::STATUS_COMPLETE;
    }
}
