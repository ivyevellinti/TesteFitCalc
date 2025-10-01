<?php

use PHPUnit\Framework\TestCase;

use Controller\UserController;

use Model\User;

class UserTest extends TestCase
{
    private $userController;
    private $mockUserModel;

    public function setUp(): void
    {
        $this->mockUserModel = $this->createMock(User::class);

        $this->userController = new UserController($this->mockUserModel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_should_be_able_to_create_user() {
        $userResult = $this->userController->createUser('Ana Luisa Santos', 'ana@example.com', '123456');

        $this->assertTrue($userResult);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_should_be_able_to_sign_in() {
        $this->mockUserModel->method('getUserByEmail')->willReturn([
            "id" => 1,
            "user_fullname" => "Ana Luisa Santos",
            "email" => "ana@example.com",
            "password" => password_hash("123456", PASSWORD_DEFAULT)
        ]);

        $userResult = $this->userController->login("ana@example.com", "123456");

        $this->assertTrue($userResult);
        $this->assertEquals(1, $_SESSION['id']);
        $this->assertEquals('Ana Luisa Santos', $_SESSION['user_fullname']);
        $this->assertEquals('ana@example.com', $_SESSION['email']);
}

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_shouldnt_login_with_invalid_credentials(){
        $this->mockUserModel->method('getUserByEmail')->willReturn([
            "id" => 1,
            "user_fullname" => "Ana Luisa Santos",
            "email" => "ana@example.com",
            "password" => password_hash("123456", PASSWORD_DEFAULT)
        ]);

        $userResult = $this->userController->login("ana@example.com", "12345");

        $this->assertFalse($userResult);

    }

}