<?php

use yii\db\Migration;

/**
 * Class m191127_132855_add_user_email_confirm_token
 */
class m191127_132855_add_user_email_confirm_token extends Migration
{
    /**
     * {@inheritdoc}
     */
//    public function safeUp()
//    {
//
//    }

    /**
     * {@inheritdoc}
     */
//    public function safeDown()
//    {
//        echo "m191127_132855_add_user_email_confirm_token cannot be reverted.\n";
//
//        return false;
//    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%user}}', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    public function down()
    {
        echo "m191127_132855_add_user_email_confirm_token cannot be reverted.\n";
        $this->dropColumn('{{%user}}', 'email_confirm_token');
        return false;
    }

}
