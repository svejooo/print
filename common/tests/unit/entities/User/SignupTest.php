<?php
//namespace shop\tests\unit\entities\User;
namespace common\tests\unit\entities\User;
use Codeception\Test\Unit;
use common\entities\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        //$user = User::requestSignup(

        $user = User::signup(
            $username = 'username',
            $email = 'email@site.com',
            //$phone = '70000000000',
            $password = 'password'
        );
        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        //$this->assertEquals($phone, $user->phone);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);

        //$this->assertNotEquals(User::STATUS_ACTIVE, $user->status);
        // Меняем на свой метод
        $this->assertTrue($user->isActive());


        //$this->assertFalse($user->isActive());
        //$this->assertTrue($user->isWait());
    }
}