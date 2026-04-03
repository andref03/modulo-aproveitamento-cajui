<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UsuarioSearch extends Usuario
{
    public function rules()
    {
        return [
            [['id', 'estudante_id', 'coordenador_id'], 'integer'],
            [['nome', 'email', 'perfil'], 'safe'],
            [['ativo'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Usuario::find()->with(['estudante', 'coordenador'])->orderBy(['id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ativo' => $this->ativo,
            'estudante_id' => $this->estudante_id,
            'coordenador_id' => $this->coordenador_id,
        ]);

        $query->andFilterWhere(['ilike', 'nome', $this->nome])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'perfil', $this->perfil]);

        return $dataProvider;
    }
}

