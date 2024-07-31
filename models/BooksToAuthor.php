<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "books_to_author".
 *
 * @property int $id
 * @property int $book_id
 * @property int $author_id
 *
 * @property Authors $author
 * @property Books $book
 */
class BooksToAuthor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books_to_author';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['book_id', 'author_id'], 'integer'],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Authors::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
            [
                ['book_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Books::class,
                'targetAttribute' => ['book_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'        => 'ID',
            'book_id'   => 'Book ID',
            'author_id' => 'Author ID',
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

    /**
     * Gets query for [[Book]].
     *
     * @return ActiveQuery
     */
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
