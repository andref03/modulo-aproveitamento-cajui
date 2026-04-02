<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "disciplina_ifnmg".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nome
 * @property int $carga_horaria
 * @property string|null $ementa
 * @property int $curso_id
 * @property int|null $pre_requisito_id
 *
 * @property Curso $curso
 * @property DisciplinaIfnmg[] $disciplinaIfnmgs
 * @property ItemEquivalencia[] $itemEquivalencias
 * @property DisciplinaIfnmg $preRequisito
 */
class DisciplinaIfnmg extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'disciplina_ifnmg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ementa', 'pre_requisito_id'], 'default', 'value' => null],
            [['codigo', 'nome', 'carga_horaria', 'curso_id'], 'required'],
            [['carga_horaria', 'curso_id', 'pre_requisito_id'], 'default', 'value' => null],
            [['carga_horaria', 'curso_id', 'pre_requisito_id'], 'integer'],
            [['ementa'], 'string'],
            [['codigo'], 'string', 'max' => 30],
            [['nome'], 'string', 'max' => 150],
            [['codigo'], 'unique'],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => Curso::class, 'targetAttribute' => ['curso_id' => 'id']],
            [['pre_requisito_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisciplinaIfnmg::class, 'targetAttribute' => ['pre_requisito_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nome' => 'Nome',
            'carga_horaria' => 'Carga Horaria',
            'ementa' => 'Ementa',
            'curso_id' => 'Curso ID',
            'pre_requisito_id' => 'Pre Requisito ID',
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
     * Gets query for [[DisciplinaIfnmgs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisciplinaIfnmgs()
    {
        return $this->hasMany(DisciplinaIfnmg::class, ['pre_requisito_id' => 'id']);
    }

    /**
     * Gets query for [[ItemEquivalencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemEquivalencias()
    {
        return $this->hasMany(ItemEquivalencia::class, ['disciplina_destino_id' => 'id']);
    }

    /**
     * Gets query for [[PreRequisito]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreRequisito()
    {
        return $this->hasOne(DisciplinaIfnmg::class, ['id' => 'pre_requisito_id']);
    }

}
