<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitacao_aproveitamento".
 *
 * @property int $id
 * @property string $numero_protocolo
 * @property int $estudante_id
 * @property int|null $coordenador_id
 * @property string $status
 * @property string|null $resultado_final
 * @property string $data_criacao
 * @property string|null $data_envio
 * @property string|null $data_finalizacao
 *
 * @property Coordenador $coordenador
 * @property Estudante $estudante
 * @property ItemEquivalencia[] $itemEquivalencias
 * @property LogAcao[] $logAcaos
 */
class SolicitacaoAproveitamento extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitacao_aproveitamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_protocolo', 'resultado_final', 'data_envio', 'data_finalizacao'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'EM_EDICAO'],

            [['estudante_id'], 'required', 'message' => '{attribute} é obrigatório.'],

            [['estudante_id', 'coordenador_id'], 'integer'],
            [['data_criacao', 'data_envio', 'data_finalizacao'], 'safe'],

            [['numero_protocolo'], 'string', 'max' => 30],
            [['status', 'resultado_final'], 'string', 'max' => 20],

            [['status'], 'in', 'range' => ['EM_EDICAO', 'EM_ANALISE', 'FINALIZADA', 'CANCELADA']],
            [['resultado_final'], 'in', 'range' => ['DEFERIDO_TOTAL', 'DEFERIDO_PARCIAL', 'INDEFERIDO_TOTAL'], 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_protocolo' => 'Número do Protocolo',
            'estudante_id' => 'Estudante',
            'coordenador_id' => 'Coordenador',
            'status' => 'Status da Solicitação',
            'resultado_final' => 'Resultado Final',
            'data_criacao' => 'Data de Criação',
            'data_envio' => 'Data de Envio',
            'data_finalizacao' => 'Data de Finalização',
        ];
    }

    /**
     * Gets query for [[Coordenador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoordenador()
    {
        return $this->hasOne(Coordenador::class, ['id' => 'coordenador_id']);
    }

    /**
     * Gets query for [[Estudante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudante()
    {
        return $this->hasOne(Estudante::class, ['id' => 'estudante_id']);
    }

    /**
     * Gets query for [[ItemEquivalencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemEquivalencias()
    {
        return $this->hasMany(ItemEquivalencia::class, ['solicitacao_id' => 'id']);
    }

    /**
     * Gets query for [[LogAcaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogAcaos()
    {
        return $this->hasMany(LogAcao::class, ['solicitacao_id' => 'id']);
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord && empty($this->status)) {
            $this->status = 'EM_EDICAO';
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            if (empty($this->numero_protocolo)) {
                $this->numero_protocolo = 'APR-' . date('YmdHis') . '-' . rand(100, 999);
            }

            if (empty($this->data_criacao)) {
                $this->data_criacao = date('Y-m-d H:i:s');
            }
        }

        return true;
    }

    public function podeEditar()
    {
        return $this->status === 'EM_EDICAO';
    }

    public function podeEnviar()
    {
        return $this->status === 'EM_EDICAO' && count($this->itemEquivalencias) > 0;
    }

    public function podeFinalizar()
    {
        if ($this->status !== 'EM_ANALISE') {
            return false;
        }

        if (count($this->itemEquivalencias) === 0) {
            return false;
        }

        foreach ($this->itemEquivalencias as $item) {
            if ($item->parecer === 'PENDENTE') {
                return false;
            }
        }

        return true;
    }

    public function getResultadoFinalFormatado()
    {
        return match ($this->resultado_final) {
            'DEFERIDO_TOTAL' => 'Deferido Total',
            'DEFERIDO_PARCIAL' => 'Deferido Parcial',
            'INDEFERIDO_TOTAL' => 'Indeferido Total',
            default => '-',
        };
    }

    public function getStatusFormatado()
    {
        return match ($this->status) {
            'EM_EDICAO' => 'Em edição',
            'EM_ANALISE' => 'Em análise',
            'FINALIZADA' => 'Finalizada',
            'CANCELADA' => 'Cancelada',
            default => $this->status,
        };
    }

    /**
     * Retorna a data de criação formatada no padrão brasileiro (dd/mm/yyyy HH:ii)
     */
    public function getDataCriacaoFormatada()
    {
        return $this->data_criacao
            ? Yii::$app->formatter->asDatetime($this->data_criacao, 'php:d/m/Y H:i')
            : '-';
    }

    /**
     * Retorna a data de envio formatada no padrão brasileiro (dd/mm/yyyy HH:ii)
     */
    public function getDataEnvioFormatada()
    {
        return $this->data_envio
            ? Yii::$app->formatter->asDatetime($this->data_envio, 'php:d/m/Y H:i')
            : '-';
    }

    /**
     * Retorna a data de finalização formatada no padrão brasileiro (dd/mm/yyyy HH:ii)
     */
    public function getDataFinalizacaoFormatada()
    {
        return $this->data_finalizacao
            ? Yii::$app->formatter->asDatetime($this->data_finalizacao, 'php:d/m/Y H:i')
            : '-';
    }

    public function registrarAcao($descricao)
    {
        $log = new LogAcao();
        $log->solicitacao_id = $this->id;
        $log->descricao = $descricao;
        $log->usuario_nome = Yii::$app->user->isGuest ? 'Anônimo' : Yii::$app->user->identity->nome;
        $log->data_hora = date('Y-m-d H:i:s');
        return $log->save();
    }

    public function podeSerEditadaPeloUsuario()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $usuario = Yii::$app->user->identity;

        if ($usuario->isAdmin()) {
            return $this->podeEditar();
        }

        if ($usuario->isAluno()) {
            return $this->podeEditar() && (int)$this->estudante_id === (int)$usuario->estudante_id;
        }

        return false;
    }

    public function podeSerFinalizadaPeloUsuario()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $usuario = Yii::$app->user->identity;

        if ($usuario->isAdmin()) {
            return $this->podeFinalizar();
        }

        if ($usuario->isCoordenador()) {
            return $this->podeFinalizar() && (int)$this->coordenador_id === (int)$usuario->coordenador_id;
        }

        return false;
    }

    public function podeSerEnviadaPeloUsuario()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $usuario = Yii::$app->user->identity;

        if ($usuario->isAdmin()) {
            return $this->podeEnviar();
        }

        if ($usuario->isAluno()) {
            return $this->podeEnviar() && (int)$this->estudante_id === (int)$usuario->estudante_id;
        }

        return false;
    }
}
