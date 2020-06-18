<?php

namespace console\controllers;

use common\enums\UserRole;
use common\enums\UserStatus;
use common\models\User;
use console\traits\ConsoleOutput;
use yii\console\Controller;
use yii\validators\EmailValidator;

class UserController extends Controller
{
    use ConsoleOutput;

    /**
     * Register new user
     */
    public function actionCreate()
    {
        $user = new User();

        // Name
        $user->name = $this->prompt('Enter Name:', [
            'required' => true,
            'validator' => static function ($input, &$error) {
                if (strlen($input) < 1) {
                    $error = 'Name must be at least 1 characters long';

                    return false;
                }

                return true;
            },
        ]);

        // Email
        $user->email = $this->prompt('Enter email:', [
            'required' => true,
            'validator' => static function ($input, &$error) {
                $emailValidator = new EmailValidator();

                // Email format validation
                if (!$emailValidator->validate($input, $error)) {
                    return false;
                }

                // User already exists validation
                $userExists = User::find()->where(['email' => $input])->exists();
                if ($userExists) {
                    $error = "User [$input] already exists";

                    return false;
                }

                return true;
            },
        ]);
        // Password
        $password = $this->prompt('Enter password:', [
            'required' => true,
            'validator' => static function ($input, &$error) {
                if (strlen($input) < 6) {
                    $error = 'Password should contain at least 6 characters';

                    return false;
                }

                return true;
            },
        ]);
        $user->setPassword($password);

        // Role
        $roles = [];
        $rolesHint = [];
        $i = 0;
        foreach (UserRole::getList() as $key => $label) {
            $roles[$i] = $key;
            $rolesHint[] = "  [$i] $label";
            $i++;
        }
        $roleNum = $this->prompt("\nAvailable roles:\n" . implode("\n", $rolesHint) . "\n\n  Select role:", [
            'required' => true,
            'validator' => static function ($input, &$error) use ($roles) {
                if (!array_key_exists($input, $roles)) {
                    $error = 'Incorrect role';

                    return false;
                }

                return true;
            },
        ]);
        $user->role = $roles[$roleNum];

        // By default user has confirmed email
        $user->status = UserStatus::ACTIVE;
        $user->generateAuthKey();
        $user->generateApiKey();

        // Save user
        if (!$user->save()) {
            $this->modelErrors($user);

            return;
        }

        $this->success("New user successfully registered!\n\n" .
            "Summary:\nname:\t{$user->name}\nemail:\t{$user->email}\npass:\t{$password}\nrole:\t" .
            UserRole::getLabel($user->role) . "\nAPI key:\t{$user->api_key}");
    }
}
