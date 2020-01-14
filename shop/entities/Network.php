<?php
namespace shop\entities;

use yii\db\ActiveRecord;

/*
 * Network model

* @property integer $user_id
* @property string $identity
* @property string $network
 *
 */

class Network extends ActiveRecord
{
    private $network;
    private $identity;

    public static function create($network, $identity): self
    {
         $item = new static();
         $item->network = $network;
         $item->identity = $identity;
         return $item;
        
    }

    public function isFor($network, $identity):bool
    {
        return $this->network === $network && $this->identity === $identity ;
    }

    public static function tableName()
    {
        return '{{%user_networks}}';
    }

}


