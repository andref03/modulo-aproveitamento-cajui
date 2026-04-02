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
            [['coordenador_id', 'resultado_final', 'data_envio', 'data_finalizacao'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'EM_EDICAO'],
            [['numero_protocolo', 'estudante_id'], 'required'],
            [['estudante_id', 'coordenador_id'], 'default', 'value' => null],
            [['estudante_id', 'coordenador_id'], 'integer'],
            [['data_criacao', 'data_envio', 'data_finalizacao'], 'safe'],
            [['numero_protocolo'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['resultado_final'], 'string', 'max' => 25],
            [['numero_protocolo'], 'unique'],
            [['coordenador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coordenador::class, 'targetAttribute' => ['coordenador_id' => 'id']],
            [['estudante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estudante::class, 'targetAttribute' => ['estudante_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_protocolo' => 'Numero Protocolo',
            'estudante_id' => 'Estudante ID',
            'coordenador_id' => 'Coordenador ID',
            'status' => 'Status',
            'resultado_final' => 'Resultado Final',
            'data_criacao' => 'Data Criacao',
            'data_envio' => 'Data Envio',
            'data_finalizacao' => 'Data Finalizacao',
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

}
