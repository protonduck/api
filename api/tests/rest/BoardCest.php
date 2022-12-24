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
 * Board Controller test
 */
class BoardCest
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
        $I->sendGET('boards');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // create
        $I->sendPOST('boards', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // update
        $I->sendPUT('boards/1', []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        // delete
        $I->sendDELETE('boards/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function checkValidationErrors(ApiTester $I)
    {
        $I->wantTo('Check Validation Errors');
        $I->haveAuthorization(1);

        $I->sendPOST('boards', [
            'name' => '',
            'image' => ['img1.jpg', 'img2.jpg'],
            'visibility' => 'false',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson(['field' => 'name']);
        $I->seeResponseContainsJson(['field' => 'image']);
        $I->seeResponseContainsJson(['field' => 'visibility']);
    }

    public function checkIndex(ApiTester $I)
    {
        $I->wantTo('Get All Records');
        $user = $I->grabUser('simple');
        $I->haveAuthorization($user->id);
        $boards = $user->boards;

        $I->sendGET('boards');
        $I->seeResponseCodeIs(HttpCode::OK);

        foreach ($boards as $board) {
            $I->seeResponseContainsJson([
                'id' => $board->id,
                'name' => $board->name,
                'image' => $board->image,
                'visibility' => $board->visibility,
                'sort' => $board->sort,
            ]);
        }
        // Has categories and links inside
        $I->seeResponseJsonMatchesXpath('//categories[*]/links[*]/url');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'categories' => 'array',
        ]);
    }

    public function checkCreate(ApiTester $I)
    {
        $I->wantTo('Create New Record');
        $I->haveAuthorization(1);

        $params = [
            'name' => 'New board',
            'image' => 'img1.jpg',
            'visibility' => 1,
        ];
        $I->sendPOST('boards', $params);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseContainsJson($params);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'categories' => 'array',
        ]);
        $I->seeResponseContainsJson(['categories' => []]);
    }

    public function checkUpdate(ApiTester $I)
    {
        $I->wantTo('Update Existing Record');
        $I->haveAuthorization(1);
        $board = $I->grabBoard('simple_1');

        $params = [
            'name' => 'New board name',
            'image' => 'new_img1.jpg',
            'visibility' => 1,
        ];
        $I->sendPUT('boards/' . $board->id, $params);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($params);

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'sort' => 'integer',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'categories' => 'array',
        ]);
    }

    public function checkDelete(ApiTester $I)
    {
        $I->wantTo('Delete Record');
        $I->haveAuthorization(1);

        $board = $I->grabBoard('simple_1');

        // Restricted
        $anotherBoard = $I->grabBoard('simple_4');
        $I->sendDELETE('boards/' . $anotherBoard->id);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $categories = $board->categories;
        $this->assertNotEmpty($categories, 'Board has categories');
        $links = [];
        foreach ($categories as $category) {
            foreach ($category->links as $link) {
                $links[] = $link;
            }
        }
        $this->assertNotEmpty($links, 'Board categories has at least one Link');

        $I->sendDELETE('boards/' . $board->id);
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $this->assertFalse($board->refresh(), 'Board deleted');

        // Check related data also deleted
        foreach ($categories as $category) {
            $this->assertFalse($category->refresh(), 'Category removed after Board deleted');
        }
        foreach ($links as $link) {
            $this->assertFalse($link->refresh(), 'Link removed after Category deleted, because Board deleted');
        }
    }
}
