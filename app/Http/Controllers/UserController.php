<?php

namespace App\Http\Controllers;

use App\Lib\RestRequest;
use Illuminate\Http\Request;

class UserController extends Controller {

    private $rest;

    public function __construct(RestRequest $rest = null) {
        if ($rest) {
            $this->rest = $rest;
        } else {
            $this->rest = new RestRequest();
        }
    }

    public function getIndex() {
        return view('user/index');
    }

    public function register(Request $request) {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $gender = $request->input('gender');
        $email = $request->input('email');
        $phoneNumber = $request->input('phoneNumber');
        $birthDate = $request->input('birthDate');

        $requestBody = [
            "first_name" => $firstName,
            "last_name" => $lastName,
            "birth_date" => $birthDate ? $birthDate : "",
            "email" => $email,
            "phone_number" => $phoneNumber,
            "gender" => $gender ? $gender : ""
        ];
        $result = [];
        try {
            $this->validatePhone($phoneNumber);
            $this->validateEmail($email);

            $apiResponse = $this->rest->post("/v1/users/register", $requestBody);
            if ($apiResponse) {
                $result = json_decode($apiResponse, true);
            } else {
                $result = [
                    "status" => 500,
                    "message" => "Oops, something were wrong",
                    "error" => "Oops, something were wrong"
                ];
            }
        } catch (\Exception $ex) {
            $result = [
                "status" => 500,
                "message" => $ex->getMessage(),
            ];
        }
        return $result;
    }

    private function validatePhone($phoneNumber) {
        try {
            $areaCode = $this->phoneAreaCode();
            $isAreaTrue = false;
            $phone = '';
            foreach ($areaCode as $key => $val) {
                if (strpos($phoneNumber, $key) !== false) {
                    $isAreaTrue = true;
                    $expl = explode($key, $phoneNumber);
                    $phone = $expl[1];
                    break;
                }
            }
            if (!$isAreaTrue) {
                throw new \Exception("Phone number area should be Indonesian");
            }

            if (!preg_match("/^\d+$/", $phone)) {
                throw new \Exception("Phone number should be numeric");
            }

            $apiResponse = $this->rest->post("/v1/users/check/phone", ["phone_number" => $phoneNumber]);
            if ($apiResponse) {
                $result = json_decode($apiResponse, true);
                if ($result["status"] == 404) {
                    return true;
                }

                if ($result["status"] == 200) {
                    throw new \Exception("Phone number is unavailable, please use another phone number");
                } else {
                    throw new \Exception("Oops, something were wrong");
                }
            } else {
                throw new \Exception("Oops, something were wrong");
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function validateEmail($email) {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Invalid email format");
            }

            $apiResponse = $this->rest->post("/v1/users/check/email", ["email" => $email]);
            if ($apiResponse) {
                $result = json_decode($apiResponse, true);
                if ($result["status"] == 404) {
                    return true;
                }

                if ($result["status"] == 200) {
                    throw new \Exception("Email is unavailable, please use another email");
                } else {
                    throw new \Exception("Oops, something were wrong");
                }
            } else {
                throw new \Exception("Oops, something were wrong");
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function phoneAreaCode() {
        return [
            "+62" => "Indonesia"
        ];
    }

}
