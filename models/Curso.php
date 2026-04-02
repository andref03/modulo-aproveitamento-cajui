<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curso".
 *
 * @property int $id
 * @property string $nome
 * @property string $campus
 *
 * @property Coordenador $coordenador
 * @property DisciplinaIfnmg[] $disciplinaIfnmgs
 * @property Estudante[] $estudantes
 */
class Curso extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'campus'], 'required'],
            [['nome'], 'string', 'max' => 150],
            [['campus'], 'string', 'max' => 100],
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
            'campus' => 'Campus',
        ];
    }

    /**
     * Gets query for [[Coordenador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoordenador()
    {
        return $this->hasOne(Coordenador::class, ['curso_id' => 'id']);
    }

    /**
     * Gets query for [[DisciplinaIfnmgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisciplinaIfnmgs()
    {
        return $this->hasMany(DisciplinaIfnmg::class, ['curso_id' => 'id']);
    }

    /**
     * Gets query for [[Estudantes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudantes()
    {
        return $this->hasMany(Estudante::class, ['curso_id' => 'id']);
    }

}
