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

            [['solicitacao_id', 'disciplina_origem_nome', 'disciplina_origem_carga_horaria', 'instituicao_origem', 'disciplina_destino_id'], 'required', 'message' => '{attribute} é obrigatório.'],

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
            }", 'message' => 'Justificativa é obrigatória para pareceres indeferidos.'],
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
            'disciplina_origem_carga_horaria' => 'Carga horária da disciplina de origem',
            'disciplina_origem_ementa' => 'Ementa / Conteúdo programático',
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

        // Preenchimento automático da data de análise
        if ($this->parecer === 'PENDENTE') {
            $this->data_analise = null;
        } else {
            if (empty($this->data_analise)) {
                $this->data_analise = date('Y-m-d H:i:s');
            }
        }

        // Se não for novo e a solicitação não estiver em edição,
        // impedir alteração dos dados acadêmicos/origem/destino
        if (!$insert && $this->solicitacao && $this->solicitacao->status !== 'EM_EDICAO') {
            $original = self::findOne($this->id);

            if ($original) {
                $this->solicitacao_id = $original->solicitacao_id;
                $this->disciplina_origem_nome = $original->disciplina_origem_nome;
                $this->disciplina_origem_carga_horaria = $original->disciplina_origem_carga_horaria;
                $this->disciplina_origem_ementa = $original->disciplina_origem_ementa;
                $this->instituicao_origem = $original->instituicao_origem;
                $this->disciplina_destino_id = $original->disciplina_destino_id;
            }
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

            if ($disciplinaDestino) {
                // Regra: não deferir quando a carga horária de origem for inferior à de destino
                if ($this->disciplina_origem_carga_horaria < $disciplinaDestino->carga_horaria) {
                    $this->addError(
                        'parecer',
                        "Não é permitido deferir equivalência. A carga horária da disciplina de origem ({$this->disciplina_origem_carga_horaria}h) é inferior à carga horária da disciplina de destino ({$disciplinaDestino->carga_horaria}h)."
                    );
                }
                
                // Regra: se a disciplina destino possui pré-requisito, não pode ser aproveitada
                if ($disciplinaDestino->pre_requisito_id !== null) {
                    $this->addError(
                        'parecer',
                        "Não é permitido deferir equivalência. A disciplina de destino possui pré-requisito e não pode ser aproveitada."
                    );
                }
            }
        }
    }

    public function getDataAnaliseFormatada()
    {
        return $this->data_analise
            ? Yii::$app->formatter->asDatetime($this->data_analise, 'php:d/m/Y H:i')
            : 'Ainda não analisado';
    }

    public function podeEditarDadosAcademicos()
    {
        return $this->solicitacao && $this->solicitacao->status === 'EM_EDICAO';
    }

    public function podeEditarAnalise()
    {
        return $this->solicitacao && $this->solicitacao->status === 'EM_ANALISE';
    }

    public function usuarioPodeEditarDadosAcademicos()
    {
        $usuario = Yii::$app->user->identity;

        return $usuario &&
            ($usuario->isAdmin() || $usuario->isAluno()) &&
            $this->solicitacao &&
            $this->solicitacao->status === 'EM_EDICAO';
    }

    public function usuarioPodeEditarAnalise()
    {
        $usuario = Yii::$app->user->identity;

        return $usuario &&
            ($usuario->isAdmin() || $usuario->isCoordenador()) &&
            $this->solicitacao &&
            $this->solicitacao->status === 'EM_ANALISE';
    }

}
