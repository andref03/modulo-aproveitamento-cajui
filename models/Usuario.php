<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Usuario extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'usuario';
    }

    public function rules()
    {
        return [
            [['nome', 'email', 'senha_hash', 'perfil'], 'required'],
            [['estudante_id', 'coordenador_id'], 'integer'],
            [['ativo'], 'boolean'],
            [['nome', 'email', 'senha_hash'], 'string', 'max' => 255],
            [['perfil'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
        ];
    }

    public function getEstudante()
    {
        return $this->hasOne(Estudante::class, ['id' => 'estudante_id']);
    }

    public function getCoordenador()
    {
        return $this->hasOne(Coordenador::class, ['id' => 'coordenador_id']);
    }

    // =========================
    // IdentityInterface
    // =========================

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'ativo' => true]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // não vamos usar token agora
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // pode implementar depois se quiser "remember me"
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    // =========================
    // Login helpers
    // =========================

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'ativo' => true]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->senha_hash);
    }

    // =========================
    // Perfis
    // =========================

    public function isAdmin()
    {
        return $this->perfil === 'ADMIN';
    }

    public function isAluno()
    {
        return $this->perfil === 'ALUNO';
    }

    public function isCoordenador()
    {
        return $this->perfil === 'COORDENADOR';
    }

    public function getNomeExibicao()
    {
        return $this->nome;
    }
}