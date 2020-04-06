<?php


namespace shop\services;

class TransactionManager
{
    public function wrap(callable $function): void
    {
          \Yii::$app->db->transaction($function);

        // Так должно быть
//        $tr = \Yii::$app->db->beginTransaction();
//        try {
//            function($db);
//            $tr->commit();
//        } catch (\Exception $e) {
//            $tr->rollBack();
//            throw $e;
//        }
    }
}