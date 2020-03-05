<?php

use yii\db\Migration;

/**
 * Class m200305_145753_add_shop_product_description_field
 */
class m200305_145753_add_shop_product_description_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'description', $this->text()->after('name'));
    }

    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'description');
    }
}
