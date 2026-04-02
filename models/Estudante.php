<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estudante".
 *
 * @property int $id
 * @property string $nome
 * @property string $matricula
 * @property string $email
 * @property int $curso_id
 *
 * @property Curso $curso
 * @property SolicitacaoAproveitamento[] $solicitacaoAproveitamentos
 */
class Estudante extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'matricula', 'email', 'curso_id'], 'required'],
            [['curso_id'], 'default', 'value' => null],
            [['curso_id'], 'integer'],
            [['nome', 'email'], 'string', 'max' => 150],
            [['matricula'], 'string', 'max' => 30],
            [['email'], 'unique'],
            [['matricula'], 'unique'],
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
            'nome' => 'Nome',
            'matricula' => 'Matricula',
            'email' => 'Email',
            'curso_id' => 'Curso ID',
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
        return $this->hasMany(SolicitacaoAproveitamento::class, ['estudante_id' => 'id']);
    }

}
