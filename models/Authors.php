<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic_name
 *
 * @property AuthorsNotification[] $authorsNotifications
 * @property BooksToAuthor[] $booksToAuthors
 */
class Authors extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'patronymic_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'              => 'ID',
            'first_name'      => 'Имя',
            'last_name'       => 'Фамилия',
            'patronymic_name' => 'Отчество',
        ];
    }

    /**
     * Gets query for [[AuthorsNotifications]].
     *
     * @return ActiveQuery
     */
    public function getAuthorsNotifications(): ActiveQuery
    {
        return $this->hasMany(AuthorsNotification::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[BooksToAuthors]].
     *
     * @return ActiveQuery
     */
    public function getBooksToAuthors(): ActiveQuery
    {
        return $this->hasMany(BooksToAuthor::class, ['author_id' => 'id']);
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->patronymic_name;
    }
}
