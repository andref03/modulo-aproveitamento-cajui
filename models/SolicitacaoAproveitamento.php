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

            [['estudante_id'], 'required'],

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
        return !in_array($this->status, ['FINALIZADA', 'CANCELADA']);
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

        foreach ($this->itemEquivalencias as $item) {
            if ($item->parecer === 'PENDENTE') {
                return false;
            }
        }

        return count($this->itemEquivalencias) > 0;
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

}
