<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%authors_notification}}`.
 */
class m240730_174526_create_authors_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authors_notification}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone_number' => $this->string()->notNull(),
            'status' => $this->tinyInteger(),
        ]);

        $this->addForeignKey(
            'fk-authors_notification-author_id',
            '{{%authors_notification}}',
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
        $this->dropTable('{{%authors_notification}}');
    }
}
