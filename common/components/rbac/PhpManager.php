<?php
namespace common\components\rbac;

use Yii;

class PhpManager extends \yii\rbac\PhpManager
{
    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        $assignment = Yii::createObject('yii\rbac\Assignment');
        $assignment->userId = $userId;
        $assignment->roleName = Yii::$app->user->identity->role;

        return [
            $assignment->roleName => $assignment,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function saveAssignments()
    {
        // save empty data
        $this->saveToFile([], $this->assignmentFile);
    }
}
