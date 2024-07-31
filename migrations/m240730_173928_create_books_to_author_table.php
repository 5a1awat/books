<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books_to_author}}`.
 */
class m240730_173928_create_books_to_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_to_author}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-books_to_author-book_id',
            '{{%books_to_author}}',
            'book_id',
            'books',
            'id',
        );

        $this->addForeignKey(
            'fk-books_to_author-author_id',
            '{{%books_to_author}}',
            'author_id',
            'authors',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_to_author}}');
    }
}
