<?php

namespace lo\core\modules\i18n\models\search;

use lo\core\modules\i18n\models\I18nSourceMessage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * I18nSourceMessageSearch represents the model behind the search form about `backend\modules\i18n\models\I18nSourceMessage`.
 */
class I18nSourceMessageSearch extends I18nSourceMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category', 'message'], 'safe'],
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
        $query = I18nSourceMessage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
