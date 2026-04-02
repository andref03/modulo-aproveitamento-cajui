<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Coordenador;

/**
 * CoordenadorSearch represents the model behind the search form of `app\models\Coordenador`.
 */
class CoordenadorSearch extends Coordenador
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'curso_id'], 'integer'],
            [['nome', 'email'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Coordenador::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'curso_id' => $this->curso_id,
        ]);

        $query->andFilterWhere(['ilike', 'nome', $this->nome])
            ->andFilterWhere(['ilike', 'email', $this->email]);

        return $dataProvider;
    }
}
