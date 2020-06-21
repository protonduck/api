<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\tests\api;

use api\helpers\TimeHelper;
use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use Codeception\Util\Shared\Asserts;
use common\enums\Language;
use common\fixtures\UserFixture;
use common\models\SecureKey;
use common\models\User;

/**
 * User Controller test
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
        $I->wantTo('Test Unauthorized Access');
        $I->donNotHaveAuthorization();

        // view profile
        $I->sendGET('user/profile');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // update profile
        $I->sendPUT('user/profile', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkBannedAccess(ApiTester $I)
    {
        $I->wantTo('Test Banned User Access');

        // login as banned user
        $user = $I->grabUser('banned');
        $I->haveAuthorization($user->id);

        // view profile
        $I->sendGET('user/profile');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // update profile
        $I->sendPUT('user/profile', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkSignUpValidationErrors(ApiTester $I)
    {
        $I->wantTo('Check validation errors');

        // Invalid name, email and password
        $I->sendPOST('user/signup', [
            'name' => ['array' => ['k' => 'v']],
            'email' => 'juststring',
            'password' => '123',
            'language' => 'zz',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'name']);
        $I->seeResponseContainsJson(['field' => 'email']);
        $I->seeResponseContainsJson(['field' => 'password']);
        $I->seeResponseContainsJson(['field' => 'language']);

        // Email is unique
        $user = $I->grabUser('simple');
        $I->sendPOST('user/signup', [
            'email' => $user->email,
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'email']);
    }

    public function checkSignUp(ApiTester $I)
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

    public function checkProfile(ApiTester $I)
    {
        $I->wantTo('Test Profile Info');

        // login as normal user
        $user = $I->grabUser('simple');
        $I->haveAuthorization($user->id);

        $I->sendGET('user/profile');
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'id' => $user->id,
            'name' => $user->name,
            'language' => $user->language,
            'premium_until' => TimeHelper::dateTimeToUnix('premium_until')($user),
        ]);
    }

    public function checkProfileUpdate(ApiTester $I)
    {
        $I->wantTo('Test Profile Updating');

        // login as normal user
        $user = $I->grabUser('simple');
        $I->haveAuthorization($user->id);

        $params = [
            'name' => 'New Name',
            'email' => 'new_email@example.com',
            'password' => 'newpassword',
            'language' => Language::RU,
        ];
        // Send update profile request
        $I->sendPUT('user/profile', $params);
        $I->seeResponseCodeIs(HttpCode::OK);

        $user->refresh(); // refresh model data
        $I->seeResponseContainsJson([
            'id' => $user->id,
            'name' => $params['name'],
            'language' => $params['language'],
        ]);

        // Check email changing
        $this->assertEquals($params['email'], $user->new_email, 'User have new email value in the tmp field');
        // Secure key should exist
        $secureCode = SecureKey::findOne(['user_id' => $user->id, 'type' => SecureKey::TYPE_CHANGE_EMAIL, 'status' => SecureKey::STATUS_NEW]);
        $this->assertNotNull($secureCode, 'Secure Key for email changing created');
        $secureCode->activate(); // do change email address
        // Secure key should be used
        $secureCode = SecureKey::findOne(['user_id' => $user->id, 'type' => SecureKey::TYPE_CHANGE_EMAIL, 'status' => SecureKey::STATUS_NEW]);
        $this->assertNull($secureCode, 'Secure code used');
        $user->refresh(); // refresh user
        $this->assertNull($user->new_email, 'User new_email field should be empty');
        $this->assertEquals($params['email'], $user->email, 'User have new email value in the primary field');

        // Check new password
        $I->sendPOST('user/login', [
            'email' => $params['email'],
            'password' => $params['password'],
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function checkLogin(ApiTester $I)
    {
        $I->wantTo('Test Login');

        // login as normal user
        $user = $I->grabUser('simple');

        $I->sendPOST('user/login', [
            'email' => $user->email,
            'password' => '11111111',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseContainsJson([
            'id' => $user->id,
            'name' => $user->name,
            'language' => $user->language,
            'premium_until' => TimeHelper::dateTimeToUnix('premium_until')($user),
            'api_key' => $user->api_key,
        ]);

        // Bad password
        $I->sendPOST('user/login', [
            'email' => $user->email,
            'password' => 'not_a_valid_password',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
}
