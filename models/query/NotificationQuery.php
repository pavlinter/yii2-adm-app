<?php

namespace app\models\query;

use Yii;
use yii\db\ActiveQuery;

/**
 * Class NotificationQuery
 */
class NotificationQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function published($state = true)
    {
        $this->andWhere(['removed' => !$state]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function viewed($state = false)
    {
        $this->andWhere(['viewed' => $state]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function sortBy()
    {
        $this->orderBy(['created_at' => SORT_DESC]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function own()
    {
        $this->andWhere(['to_id' => Yii::$app->user->getId()]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fromUser($user_id = null)
    {
        if ($user_id === null) {
            $user_id = Yii::$app->user->getId();
        }
        $this->andWhere(['to_id' => $user_id]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function pk($id)
    {
        $this->andWhere(['id' => $id]);
        return $this;
    }




}