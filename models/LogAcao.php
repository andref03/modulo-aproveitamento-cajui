<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_acao".
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property string $descricao
 * @property string $usuario_nome
 * @property string $data_hora
 *
 * @property SolicitacaoAproveitamento $solicitacao
 */
class LogAcao extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_acao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['solicitacao_id', 'descricao', 'usuario_nome'], 'required'],
            [['solicitacao_id'], 'default', 'value' => null],
            [['solicitacao_id'], 'integer'],
            [['descricao'], 'string'],
            [['data_hora'], 'safe'],
            [['usuario_nome'], 'string', 'max' => 150],
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
            'descricao' => 'Descricao',
            'usuario_nome' => 'Usuario Nome',
            'data_hora' => 'Data Hora',
        ];
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
