<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\UserController;

/**
 * Description of UserTest
 *
 * @author Anggarda
 */
class UserTest extends TestCase {

    private function mockExternalRest() {
        $mock = \Mockery::mock('\App\Lib\RestRequest');
        return $mock;
    }

    private function mockRequest() {
        $mock = \Mockery::mock('\Illuminate\Http\Request');
        $mock->shouldReceive('input')->with('firstName')->once()->andReturn('Anggarda');
        $mock->shouldReceive('input')->with('lastName')->once()->andReturn('Gasta');
        $mock->shouldReceive('input')->with('gender')->once()->andReturn('m');
        $mock->shouldReceive('input')->with('email')->once()->andReturn('anggarda@email.com');
        $mock->shouldReceive('input')->with('phoneNumber')->once()->andReturn('+6285123456789');
        $mock->shouldReceive('input')->with('birthDate')->once()->andReturn('2000-01-01');
        return $mock;
    }

    public function testSuccessRegister() {
        $mockRest = $this->mockExternalRest();
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 404]));
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 404]));
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 200]));

        $userController = new UserController($mockRest);
        $actual = $userController->register($this->mockRequest());
        $this->assertEquals(['status' => 200], $actual);
    }

    public function testFailedRegisterCauseByPhoneNumberIsAlreadyRegistered() {
        $mockRest = $this->mockExternalRest();
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 200]));

        $userController = new UserController($mockRest);
        $actual = $userController->register($this->mockRequest());
        $this->assertEquals(['status' => 500, 'message' => 'Phone number is unavailable, please use another phone number'], $actual);
    }

    public function testFailedRegisterCauseByEmailIsAlreadyRegistered() {
        $mockRest = $this->mockExternalRest();
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 404]));
        $mockRest->shouldReceive('post')->once()->andReturn(json_encode(['status' => 200]));

        $userController = new UserController($mockRest);
        $actual = $userController->register($this->mockRequest());
        $this->assertEquals(['status' => 500, 'message' => 'Email is unavailable, please use another email'], $actual);
    }

}
