<?php

namespace api\tests\api;

use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use Codeception\Util\Shared\Asserts;
use common\fixtures\UserFixture;
use common\models\User;

/**
 * User Controller test
 *
 * @group uc
 */
class UserCest
{
    use Asserts;

    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     *
     * @return array
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @see \Codeception\Module\Yii2::_before()
     */
    public function _fixtures()
    {
        return [
            'users' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'users.php',
            ],
        ];
    }

    public function checkUnauthorizedAccess(ApiTester $I)
    {
        $I->wantTo('Test Unauthorized Profile');
        $I->sendGET('boards');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkRegister(ApiTester $I)
    {
        $I->wantTo('Test Register New User');

        $email = 'john@gmail.com';
        $I->sendPOST('user/signup', [
            'name' => 'New User John',
            'email' => $email,
            'password' => '123456',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseJsonMatchesXpath('//id');
        $I->seeResponseJsonMatchesXpath('//api_key');

        $user = User::findByUsername($email);
        $this->assertNotNull($user, 'New user registered');
    }
}
