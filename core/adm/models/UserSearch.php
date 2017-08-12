<?php

namespace app\core\adm\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch
 */
class UserSearch extends \app\models\User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'status', 'id'], 'integer'],
            [['username', 'email', 'online'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'role' => $this->role,
            'status' => $this->status,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        if ($this->online !== '') {
            if ($this->online == 0) {

            } else if($this->online == 1){

            } else {
                //date range
            }
        }

        return $dataProvider;
    }
}
