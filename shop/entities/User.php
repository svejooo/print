<?php
namespace shop\entities;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use shop\entities\Network;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $email_confirm_token
 * @property string $networks
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const STATUS_WAIT = 5;

    // public static function create (string $username, string $email, string $password): self
    //public function __construct( string $username, string $email, string $password)

   // public $email_confirm_token;

    public static function requestSignup(string $username, string $email, string $password): self
    {
        // Здесь помимо стандартной валидации можно зебашить свои проверки
        // если пустой емейл, телефолн и тд.

        // $user = new User(); - Пидарас!!!
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->status = self::STATUS_WAIT;
        $user->created_at = time();
        $user->generateAuthKey();
        //$user->generateEmailVerificationToken();

        return $user;

    }

//    public function confirmSignup(): void
//    {
//        if (!$this->isWait()) {
//            throw new \DomainException('User is already active.');
//        }
//        $this->status = self::STATUS_ACTIVE;
//        $this->email_confirm_token = null;
//        $this->recordEvent(new UserSignUpConfirmed($this));
//    }

    /*ДЛя создания пользователя из админ панели*/
    public static function create(string $username, string $email, string $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString() );
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString() ;

        return $user;

    }


    public function edit(string $email, string $username): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->updated_at = time();
        $this->password = $this->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString() );
        //$this->password =
        // здесь можно пистьа какую нибуь историю
    }

    public static function signupByNetwork($network, $identity): self
    {
        $user = new User();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        //$user->networks = [User::create($network,$identity)];
        $user->networks = [Network::create($network,$identity)];
        return $user;
    }

    public function attachNetwork($network,$identity): void
    {

        $networks = $this->networks;
        foreach ($networks as $current){
            if($current->isFor($network,$identity))
                   throw new \DomainException('Эта сеть уже подключена!');
        }
        $networks[] = Network::create($network,$identity);
        $this->$networks = $networks;


    }

    public function confirmSignup(): void
    {
        if (!$this->isWait() )
            throw new \DomainException("Вы как уголь - уже активированы");

        $this->status = self::STATUS_ACTIVE;
        //$this->removePasswordResetToken();
        $this->removePasswordResetToken();

    }


    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }


    public function getNetworks(): ActiveQuery
    {
        /* TODO -  что передаем сюда??? */
        return $this->hasMany(  Network::className(),  ['user_id' => 'id']);
    }

        public static function signup( string $username, string $email, string $password): self
    {

        // Здесь помимо стандартной валидации можно заебашить свои проверки
        // если пустой емейл, телефолн и тд.
        $user = new static();
        $user->username = $username;
        $user->email = $email;

        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;

        $user->generateAuthKey();
        return $user;

//        $this->username = $username;
//        $this->email = $email;
//        $this->setPassword($password);
//        $this->created_at = time();
//        $this->status = self::STATUS_ACTIVE;
//        $this->generateAuthKey();
        //$this->generateEmailVerificationToken();
        //parent::__construct();


    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }



    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks'],
            ]

        ];
    }

    public function transactions()
    {
        return [self::SCENARIO_DEFAULT=> self::OP_ALL ];
    }





    public static function findByNetworkIdentity($network,$identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
         return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        //return static::findOne(['username' => $username]);
    }

     public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }




    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function get($id): User
    {
        //return $this->getBy(['id' => $id]);
        if (!$user = User::find()->andWhere(['id' => $id])->limit(1)->one()) {
            throw new \DomainException('User not found.');
        }
        return $user;
    }

    public function getBy(array $condition): User
    {
        if (!$user = $this->find()->andWhere($condition)->limit(1)->one()) {
            throw new \DomainException('User not found.');
        }
        return $user;
    }
}
