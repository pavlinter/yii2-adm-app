<?php

namespace app\core\adm\models;

use pavlinter\adm\Adm;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * SourceMessageSearch represents the model behind the search form about `app\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    public $translation;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category', 'message', 'translation'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        return array_merge([
            'translation' => Yii::t('modelAdm/source-message', 'Translation'),
        ], $labels);
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
        $sourceMessageTable = static::tableName();
        $query = static::find()->from(['s' => $sourceMessageTable]);

        $sort = isset($params['sort']) ? $params['sort'] : null;
        $emptyTranslation = Yii::$app->request->get('is-empty');
        $publicTranslation = Yii::$app->request->get('is-public');

        $isTranslationSearch = $emptyTranslation || (isset($params['SourceMessageSearch']['translation']) && $params['SourceMessageSearch']['translation']);
        $isTranslationSort   = in_array($sort, ['-translation', 'translation']) ? $sort : null;
        $notLikes = [
            'adm',
            'i18n-dot',
            'admpages',
            'modelAdm',
            'model/contact_msg',
            'model/news',
            'admnews',
        ];
        if ($isTranslationSearch || $isTranslationSort) {
            $messageTable = Adm::getInstance()->manager->createMessageQuery('tableName');
            $query->innerJoin(['m'=> $messageTable],'m.id=s.id')->with(['messages']);
            if ($emptyTranslation == 1) {
                $query->andWhere([
                    'm.translation' => '',
                    'm.language_id' => Yii::$app->i18n->getId(),
                ]);
            }
        }

        if ($publicTranslation) {
            $notLikesCondition = ['and'];
            foreach ($notLikes as $like) {
                $notLikesCondition[] = ['not like', 's.category', new Expression("'" . $like . "%'")];
            }
            $query->andWhere($notLikesCondition);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id'=> SORT_DESC ]
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $dataProvider->sort->attributes['translation']['asc'] = ['m.translation' => SORT_ASC];
        $dataProvider->sort->attributes['translation']['desc'] = ['m.translation' => SORT_DESC];

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);


        if ($isTranslationSearch) {
            $query->andFilterWhere(['like', 'm.translation', $this->translation]);
        }

        $query->andFilterWhere(['like', 's.category', $this->category])
            ->andFilterWhere(['like', 's.message', $this->message]);

        return $dataProvider;
    }
}
