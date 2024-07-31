<?php

declare(strict_types=1);

namespace app\models;

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
            [['phone_number'], 'string', 'max' => 255],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Authors::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
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
            'phone_number' => 'Phone Number',
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
}
