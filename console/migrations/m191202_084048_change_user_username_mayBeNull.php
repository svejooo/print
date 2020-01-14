<?php

use yii\db\Migration;

/**
 * Class m191202_084048_change_user_username_mayBeNull
 */
class m191202_084048_change_user_username_mayBeNull extends Migration
{
//    /**
//     * {@inheritdoc}
//     */
//    public function safeUp()
//    {
//        $this->alterColumn('user', 'email', $this->string(255)->null());
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function safeDown()
//    {
//        echo "m191202_084048_change_user_username_mayBeNull cannot be reverted.\n";
//
//        return false;
//    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // remove the unique index
        $this->dropIndex('username', 'user');
        $this->alterColumn('user', 'username', $this->string(255)->null());
    }

    public function down()
    {
        $this->alterColumn('user', 'username', $this->string()->notNull());
        $this->createIndex('username', 'user', 'username', $unique = true );
       // $this->alterColumn('user','username', $this->string()->notNull);

    }

}
