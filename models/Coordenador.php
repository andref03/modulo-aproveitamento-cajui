<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coordenador".
 *
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property int $curso_id
 *
 * @property Curso $curso
 * @property SolicitacaoAproveitamento[] $solicitacaoAproveitamentos
 */
class Coordenador extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coordenador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'curso_id'], 'required'],
            [['curso_id'], 'default', 'value' => null],
            [['curso_id'], 'integer'],
            [['nome', 'email'], 'string', 'max' => 150],
            [['curso_id'], 'unique'],
            [['email'], 'unique'],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Curso::class, 'targetAttribute' => ['curso_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome completo',
            'email' => 'E-mail',
            'curso_id' => 'Curso',
        ];
    }

    /**
     * Gets query for [[Curso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(Curso::class, ['id' => 'curso_id']);
    }

    /**
     * Gets query for [[SolicitacaoAproveitamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitacaoAproveitamentos()
    {
        return $this->hasMany(SolicitacaoAproveitamento::class, ['coordenador_id' => 'id']);
    }

}
