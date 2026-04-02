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

            [['solicitacao_id', 'disciplina_origem_carga_horaria', 'disciplina_destino_id'], 'integer'],
            [['disciplina_origem_ementa', 'justificativa'], 'string'],
            [['data_analise'], 'safe'],

            [['disciplina_origem_nome', 'instituicao_origem'], 'string', 'max' => 150],
            [['parecer'], 'string', 'max' => 20],

            [['parecer'], 'in', 'range' => ['PENDENTE', 'DEFERIDO', 'INDEFERIDO']],

            [['disciplina_origem_carga_horaria'], 'integer', 'min' => 1],

            [['disciplina_destino_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisciplinaIfnmg::class, 'targetAttribute' => ['disciplina_destino_id' => 'id']],
            [['solicitacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitacaoAproveitamento::class, 'targetAttribute' => ['solicitacao_id' => 'id']],

            [['parecer'], 'validarCargaHorariaParaDeferimento'],
            
            [['justificativa'], 'required', 'when' => function($model) {
                return $model->parecer === 'INDEFERIDO';
            }, 'whenClient' => "function (attribute, value) {
                return $('#itemequivalencia-parecer').val() === 'INDEFERIDO';
            }"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'solicitacao_id' => 'Solicitação',
            'disciplina_origem_nome' => 'Disciplina cursada anteriormente',
            'disciplina_origem_carga_horaria' => 'Carga horária da disciplina cursada',
            'disciplina_origem_ementa' => 'Ementa / conteúdo programático',
            'instituicao_origem' => 'Instituição de origem',
            'disciplina_destino_id' => 'Disciplina do IFNMG',
            'parecer' => 'Parecer',
            'justificativa' => 'Justificativa do parecer',
            'data_analise' => 'Data da análise',
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (in_array($this->parecer, ['DEFERIDO', 'INDEFERIDO']) && empty($this->data_analise)) {
            $this->data_analise = date('Y-m-d H:i:s');
        }

        return true;
    }

    public function getParecerFormatado()
    {
        return match ($this->parecer) {
            'PENDENTE' => 'Pendente',
            'DEFERIDO' => 'Deferido',
            'INDEFERIDO' => 'Indeferido',
            default => $this->parecer,
        };
    }

    public function validarCargaHorariaParaDeferimento($attribute, $params)
    {
        if ($this->parecer === 'DEFERIDO') {
            $disciplinaDestino = $this->disciplinaDestino;

            if ($disciplinaDestino && $this->disciplina_origem_carga_horaria < $disciplinaDestino->carga_horaria) {
                $this->addError(
                    'parecer',
                    'Não é permitido deferir equivalência quando a carga horária da disciplina de origem é inferior à da disciplina de destino.'
                );
            }
        }
    }

}
