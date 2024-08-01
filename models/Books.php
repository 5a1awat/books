<?php

declare(strict_types=1);

namespace app\models;

use Yii;
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
    public array $authors = [];

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'year', 'isbn', 'authors'], 'required'],
            [['year'], 'integer'],
            [['isbn'], 'string', 'length' => 13],
            [['description'], 'string'],
            [['name', 'isbn', 'photo'], 'string', 'max' => 255],
            [['authors'], 'safe'],
            [['isbn'], 'unique'],
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
            'authors'     => 'Авторы',
        ];
    }

    public function saveAuthors(): bool
    {
        Yii::$app->db->createCommand()->delete(BooksToAuthor::tableName(), ['book_id' => $this->id])->execute();

        foreach ($this->authors as $authorId) {
            Yii::$app->db->createCommand()->insert(BooksToAuthor::tableName(), [
                'book_id'   => $this->id,
                'author_id' => $authorId,
            ])->execute();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books';
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

    public function getAuthors(): array
    {
        return array_map(function ($author) {
            return $author->author;
        }, $this->booksToAuthors);
    }
}
