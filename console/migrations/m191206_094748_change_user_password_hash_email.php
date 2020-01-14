<?php

use yii\db\Migration;

/**
 * Class m191206_094748_change_user_password_hash_email
 */
class m191206_094748_change_user_password_hash_email extends Migration
{


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('{{%user}}', 'password_hash', $this->string());
        $this->alterColumn('{{%user}}', 'email', $this->string());
    }

    public function down()
    {
        echo "m191206_094748_change_user_password_hash_email cannot be reverted.\n";
        $this->alterColumn('{{%user}}', 'password_hash', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'email', $this->string()->notNull());
    }

}
