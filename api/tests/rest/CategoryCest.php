<?php
/**
 * @noinspection PhpUnused Controller actions
 */

namespace api\tests\api;

use api\tests\ApiTester;
use Codeception\Util\HttpCode;
use Codeception\Util\Shared\Asserts;
use api\fixtures\BoardFixture;
use api\fixtures\CategoryFixture;
use api\fixtures\LinkFixture;
use api\fixtures\UserFixture;

/**
 * Category Controller test
 */
class CategoryCest
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
        ];
    }

    public function checkUnauthorizedAccess(ApiTester $I)
    {
        $I->wantTo('Test Unauthorized Access');
        $I->donNotHaveAuthorization();

        // all models
        $I->sendGET('categories');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // create
        $I->sendPOST('categories', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // update
        $I->sendPUT('categories/1', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // delete
        $I->sendDELETE('categories/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkValidationErrors(ApiTester $I)
    {
        $I->wantTo('Check Validation Errors');
        $I->haveAuthorization(1);

        $I->sendPOST('categories', [
            'name' => '',
            'board_id' => 0,
            'description' => -4,
            'color' => '45',
            'icon' => ['icon1', 'icon2'],
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'name']);
        $I->seeResponseContainsJson(['field' => 'board_id']);
        $I->seeResponseContainsJson(['field' => 'color']);
    }

    public function checkIndex(ApiTester $I)
    {
        $I->wantTo('Get All Records');
        $I->haveAuthorization(1);
        $board = $I->grabBoard('simple_1');

        $I->sendGET('categories');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);  // board_id required

        $I->sendGET('categories', ['board_id' => $board->id]);
        $I->seeResponseCodeIs(HttpCode::OK);

        foreach ($board->categories as $category) {
            $I->seeResponseContainsJson([
                'name' => $category->name,
                'board_id' => $category->board_id,
                'description' => $category->description,
                'color' => $category->color,
                'icon' => $category->icon,
            ]);
        }
        // Category has links inside
        $I->seeResponseJsonMatchesXpath('//links[*]/url');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'links' => 'array',
        ]);
    }

    public function checkCreate(ApiTester $I)
    {
        $I->wantTo('Create New Record');
        $I->haveAuthorization(1);
        $userBoard = $I->grabBoard('simple_1');
        $anotherBoard = $I->grabBoard('simple_4');

        $params = [
            'name' => 'Some new Category',
            'board_id' => $anotherBoard->id,
            'description' => 'Some description',
            'color' => '12f0b1',
            'icon' => 'fas fa-icon',
        ];

        // board_id does not belong user 1
        $I->sendPOST('categories', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'board_id']);

        // board_id does not exist
        $params['board_id'] = 98745785;
        $I->sendPOST('categories', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'board_id']);

        // correct request
        $params['board_id'] = $userBoard->id;
        $I->sendPOST('categories', $params);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseContainsJson($params);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'board_id' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'links' => 'array',
        ]);
        $I->seeResponseContainsJson(['links' => []]);
    }

    public function checkUpdate(ApiTester $I)
    {
        $I->wantTo('Update Existing Record');
        $I->haveAuthorization(1);
        $category = $I->grabCategory('b1_c1');
        $anotherBoard = $I->grabBoard('simple_4');

        $params = [
            'name' => 'New category name',
            'description' => 'New description',
            'color' => '110bf5',
            'icon' => 'fas fa-move',
        ];
        $I->sendPUT('categories/' . $category->id, $params);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($params);
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'links' => 'array',
        ]);

        // bad 'board_id'
        $params['board_id'] = 98745785;
        $I->sendPOST('categories', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'board_id']);
        // board_id does not belong user 1
        $params['board_id'] = $anotherBoard->id;
        $I->sendPOST('categories', $params);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'board_id']);
    }

    public function checkDelete(ApiTester $I)
    {
        $I->wantTo('Delete Record');
        $I->haveAuthorization(1);
        $category = $I->grabCategory('b1_c1');
        $anotherCategory = $I->grabCategory('b4_c4');
        $links = $category->links;
        $this->assertNotEmpty($links, 'Category has links');

        // Trying to delete another category
        $I->sendDELETE('categories/' . $anotherCategory->id);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        // Delete category
        $I->sendDELETE('categories/' . $category->id);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $this->assertFalse($category->refresh(), 'Category deleted');

        // Check related data also deleted
        foreach ($links as $link) {
            $this->assertFalse($link->refresh(), 'Links removed after Category deleted');
        }
    }
}
