<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $photo
 *
 * @property BooksToAuthor[] $booksToAuthors
 */
class Books extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
            [['isbn'], 'string', 'length' => 13],
            [['description'], 'string'],
            [['name', 'isbn', 'photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Название',
            'year'        => 'Год',
            'description' => 'Описание',
            'isbn'        => 'ISBN',
            'photo'       => 'Фото',
        ];
    }

    /**
     * Gets query for [[BooksToAuthors]].
     *
     * @return ActiveQuery
     */
    public function getBooksToAuthors(): ActiveQuery
    {
        return $this->hasMany(BooksToAuthor::class, ['book_id' => 'id']);
    }
}
