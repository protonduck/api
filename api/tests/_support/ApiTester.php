<?php

namespace api\tests;

use api\fixtures\UserFixture;
use api\models\User;

/**
 * Here you can define custom actions
 * all public methods declared in helper class will be available in $I
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    private $authorizationHeader = 'Authorization';

    /**
     * Authorize by user
     *
     * @param int|null $userId
     */
    public function haveAuthorization($userId = null)
    {
        $this->haveFixtures([
            'users' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'users.php',
            ],
        ]);

        $user = User::findOne($userId ?? 1);
        if (!$user) {
            throw new \Exception('User fixture not found');
        }

        $this->haveHttpHeader($this->authorizationHeader, 'Bearer ' . $user->api_key);
    }

    /**
     * Remove any authorization
     */
    public function donNotHaveAuthorization()
    {
        $this->deleteHeader($this->authorizationHeader);
    }

    /**
     * Get model fixture
     *
     * @param string|null $indexKey
     * @param string $name
     *
     * @return \api\models\User
     */
    public function grabUser($indexKey = null, $name = 'users')
    {
        return $this->grabFixture($name, $indexKey);
    }

    /**
     * Get model fixture
     *
     * @param string|null $indexKey
     * @param string $name
     *
     * @return \api\models\Board
     */
    public function grabBoard($indexKey = null, $name = 'boards')
    {
        return $this->grabFixture($name, $indexKey);
    }

    /**
     * Get model fixture
     *
     * @param string|null $indexKey
     * @param string $name
     *
     * @return \api\models\Category
     */
    public function grabCategory($indexKey = null, $name = 'categories')
    {
        return $this->grabFixture($name, $indexKey);
    }

    /**
     * Get model fixture
     *
     * @param string|null $indexKey
     * @param string $name
     *
     * @return \api\models\Link
     */
    public function grabLink($indexKey = null, $name = 'links')
    {
        return $this->grabFixture($name, $indexKey);
    }

    /**
     * Get model fixture
     *
     * @param string|null $indexKey
     * @param string $name
     *
     * @return \api\models\SecureKey
     */
    public function grabSecureKey($indexKey = null, $name = 'secure_keys')
    {
        return $this->grabFixture($name, $indexKey);
    }
}
