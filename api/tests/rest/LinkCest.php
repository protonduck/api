<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\tests\api;

use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use Codeception\Util\Shared\Asserts;
use api\enums\LinkTarget;
use common\fixtures\BoardFixture;
use common\fixtures\CategoryFixture;
use common\fixtures\DomainFixture;
use common\fixtures\LinkFixture;
use common\fixtures\UserFixture;

/**
 * Link Controller test
 */
class LinkCest
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
            'boards' => [
                'class' => BoardFixture::class,
                'dataFile' => codecept_data_dir() . 'boards.php',
            ],
            'categories' => [
                'class' => CategoryFixture::class,
                'dataFile' => codecept_data_dir() . 'categories.php',
            ],
            'links' => [
                'class' => LinkFixture::class,
                'dataFile' => codecept_data_dir() . 'links.php',
            ],
            'domains' => [
                'class' => DomainFixture::class,
                'dataFile' => codecept_data_dir() . 'domains.php',
            ],
        ];
    }

    public function checkUnauthorizedAccess(ApiTester $I)
    {
        $I->wantTo('Test Unauthorized Access');
        $I->donNotHaveAuthorization();

        // all models
        $I->sendGET('links');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // create
        $I->sendPOST('links', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // update
        $I->sendPUT('links/1', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // delete
        $I->sendDELETE('links/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkValidationErrors(ApiTester $I)
    {
        $I->wantTo('Check Validation Errors');
        $I->haveAuthorization(1);

        $I->sendPOST('links', [
            'url' => '',
            'category_id' => 0,
            'title' => str_repeat('a', 256),
            'description' => str_repeat('b', 1001),
            'is_favorite' => 'abc',
            'target' => 105,
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'url']);
        $I->seeResponseContainsJson(['field' => 'category_id']);
        $I->seeResponseContainsJson(['field' => 'title']);
        $I->seeResponseContainsJson(['field' => 'description']);
        $I->seeResponseContainsJson(['field' => 'is_favorite']);
        $I->seeResponseContainsJson(['field' => 'target']);
    }

    public function checkIndex(ApiTester $I)
    {
        $I->wantTo('Get All Records');
        $I->haveAuthorization(1);

        $I->sendGET('links');

        $I->seeResponseContainsJson([
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ]);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'url' => 'string',
            'category_id' => 'integer',
            'title' => 'string',
            'description' => 'string',
            'is_favorite' => 'boolean',
            'favicon' => 'string|null',
            'target' => 'integer',
            'hits' => 'integer',
            'http_status_code' => 'integer|null',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
        ]);
    }

    public function checkCreate(ApiTester $I)
    {
        $I->wantTo('Create New Record');
        $I->haveAuthorization(1);
        $userCategory = $I->grabCategory('b1_c1');
        $otherCategory = $I->grabCategory('b4_c4');

        $params = [
            'url' => 'https://gmail.com',
            'category_id' => $otherCategory->id,
            'title' => 'Gmail',
            'description' => 'Gmail mailbox',
            'is_favorite' => false,
            'target' => LinkTarget::BLANK,
        ];

        // category_id does not belong user 1
        $I->sendPOST('links', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'category_id']);

        // category_id does not exist
        $params['category_id'] = 98745785;
        $I->sendPOST('links', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'category_id']);

        // correct request
        $params['category_id'] = $userCategory->id;
        $I->sendPOST('links', $params);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseContainsJson($params);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'url' => 'string',
            'category_id' => 'integer',
            'title' => 'string',
            'description' => 'string',
            'is_favorite' => 'boolean',
            'favicon' => 'string|null',
            'target' => 'integer',
            'hits' => 'integer',
            'http_status_code' => 'integer|null',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
        ]);
    }

    public function checkUpdate(ApiTester $I)
    {
        $I->wantTo('Update Existing Record');
        $I->haveAuthorization(1);
        $link = $I->grabLink('b1_c1_l1');
        $anotherCategory = $I->grabCategory('b4_c4');

        $params = [
            'url' => 'https://safari.com',
            'title' => 'Safari',
            'description' => 'Safari browser',
            'is_favorite' => true,
            'target' => LinkTarget::BLANK,
        ];
        $I->sendPUT('links/' . $link->id, $params);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($params);
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'category_id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
        ]);

        // bad 'category_id'
        $params['category_id'] = 98745785;
        $I->sendPOST('links', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'category_id']);
        // category_id does not belong user 1
        $params['category_id'] = $anotherCategory->id;
        $I->sendPOST('links', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'category_id']);
    }

    public function checkDelete(ApiTester $I)
    {
        $I->wantTo('Delete Record');
        $I->haveAuthorization(1);
        $link = $I->grabLink('b1_c1_l1');
        $anotherLink = $I->grabLink('b4_c4_l4');

        // Trying to delete another link
        $I->sendDELETE('links/' . $anotherLink->id);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        // Delete link
        $I->sendDELETE('links/' . $link->id);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $this->assertFalse($link->refresh(), 'Link deleted');
    }
}
