<?php
namespace shop\forms;

use Yii;
use yii\base\Model;
use shop\entities\User;

/**
 * Login form
 */
class LoginForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $rememberMe = true;

    //private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }

}