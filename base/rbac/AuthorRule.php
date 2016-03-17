<?php

namespace app\base\rbac;

/**
 * Checks if user_id matches user passed via params.
 */
class AuthorRule extends \yii\rbac\Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user the user ID.
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['model'])){
            return $params['model']->user_id == $user;
        }
        if (is_integer($params)) {
            return $params == $user;
        } elseif ($params instanceof \yii\web\IdentityInterface){
            /* @var $params \app\models\User */
            return $params->getId() == $user;
        }
        return false;
    }
}