<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@shop', dirname(dirname(__DIR__)) . '/shop');
//Yii::setAlias('@static', dirname(dirname(__DIR__)) . '/static');  // так не верно - пишем config/main отдельно бекенд и фронтенд
