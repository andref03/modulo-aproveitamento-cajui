<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SolicitacaoAproveitamento;

/**
 * SolicitacaoAproveitamentoSearch represents the model behind the search form of `app\models\SolicitacaoAproveitamento`.
 */
class SolicitacaoAproveitamentoSearch extends SolicitacaoAproveitamento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'estudante_id', 'coordenador_id'], 'integer'],
            [['numero_protocolo', 'status', 'resultado_final', 'data_criacao', 'data_envio', 'data_finalizacao'], 'safe'],
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
        $query = SolicitacaoAproveitamento::find();

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
            'estudante_id' => $this->estudante_id,
            'coordenador_id' => $this->coordenador_id,
            'data_criacao' => $this->data_criacao,
            'data_envio' => $this->data_envio,
            'data_finalizacao' => $this->data_finalizacao,
        ]);

        $query->andFilterWhere(['ilike', 'numero_protocolo', $this->numero_protocolo])
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'resultado_final', $this->resultado_final]);

        return $dataProvider;
    }
}
