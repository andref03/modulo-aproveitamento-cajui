<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_equivalencia".
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property string $disciplina_origem_nome
 * @property int $disciplina_origem_carga_horaria
 * @property string|null $disciplina_origem_ementa
 * @property string $instituicao_origem
 * @property int $disciplina_destino_id
 * @property string $parecer
 * @property string|null $justificativa
 * @property string|null $data_analise
 *
 * @property DisciplinaIfnmg $disciplinaDestino
 * @property SolicitacaoAproveitamento $solicitacao
 */
class ItemEquivalencia extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_equivalencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['disciplina_origem_ementa', 'justificativa', 'data_analise'], 'default', 'value' => null],
            [['parecer'], 'default', 'value' => 'PENDENTE'],
            [['solicitacao_id', 'disciplina_origem_nome', 'disciplina_origem_carga_horaria', 'instituicao_origem', 'disciplina_destino_id'], 'required'],
            [['solicitacao_id', 'disciplina_origem_carga_horaria', 'disciplina_destino_id'], 'default', 'value' => null],
            [['solicitacao_id', 'disciplina_origem_carga_horaria', 'disciplina_destino_id'], 'integer'],
            [['disciplina_origem_ementa', 'justificativa'], 'string'],
            [['data_analise'], 'safe'],
            [['disciplina_origem_nome', 'instituicao_origem'], 'string', 'max' => 150],
            [['parecer'], 'string', 'max' => 15],
            [['disciplina_destino_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisciplinaIfnmg::class, 'targetAttribute' => ['disciplina_destino_id' => 'id']],
            [['solicitacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitacaoAproveitamento::class, 'targetAttribute' => ['solicitacao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'solicitacao_id' => 'Solicitacao ID',
            'disciplina_origem_nome' => 'Disciplina Origem Nome',
            'disciplina_origem_carga_horaria' => 'Disciplina Origem Carga Horaria',
            'disciplina_origem_ementa' => 'Disciplina Origem Ementa',
            'instituicao_origem' => 'Instituicao Origem',
            'disciplina_destino_id' => 'Disciplina Destino ID',
            'parecer' => 'Parecer',
            'justificativa' => 'Justificativa',
            'data_analise' => 'Data Analise',
        ];
    }

    /**
     * Gets query for [[DisciplinaDestino]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisciplinaDestino()
    {
        return $this->hasOne(DisciplinaIfnmg::class, ['id' => 'disciplina_destino_id']);
    }

    /**
     * Gets query for [[Solicitacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitacao()
    {
        return $this->hasOne(SolicitacaoAproveitamento::class, ['id' => 'solicitacao_id']);
    }

}
