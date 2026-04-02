<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItemEquivalencia;

/**
 * ItemEquivalenciaSearch represents the model behind the search form of `app\models\ItemEquivalencia`.
 */
class ItemEquivalenciaSearch extends ItemEquivalencia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'solicitacao_id', 'disciplina_origem_carga_horaria', 'disciplina_destino_id'], 'integer'],
            [['disciplina_origem_nome', 'disciplina_origem_ementa', 'instituicao_origem', 'parecer', 'justificativa', 'data_analise'], 'safe'],
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
        $query = ItemEquivalencia::find();

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
            'solicitacao_id' => $this->solicitacao_id,
            'disciplina_origem_carga_horaria' => $this->disciplina_origem_carga_horaria,
            'disciplina_destino_id' => $this->disciplina_destino_id,
            'data_analise' => $this->data_analise,
        ]);

        $query->andFilterWhere(['ilike', 'disciplina_origem_nome', $this->disciplina_origem_nome])
            ->andFilterWhere(['ilike', 'disciplina_origem_ementa', $this->disciplina_origem_ementa])
            ->andFilterWhere(['ilike', 'instituicao_origem', $this->instituicao_origem])
            ->andFilterWhere(['ilike', 'parecer', $this->parecer])
            ->andFilterWhere(['ilike', 'justificativa', $this->justificativa]);

        return $dataProvider;
    }
}
