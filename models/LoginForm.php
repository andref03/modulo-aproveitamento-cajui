<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $senha;
    public $rememberMe = false;

    private $_usuario = false;

    public function rules()
    {
        return [
            [['email', 'senha'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['senha', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'senha' => 'Senha',
            'rememberMe' => 'Lembrar-me',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $usuario = $this->getUsuario();

            if (!$usuario || !$usuario->validatePassword($this->senha)) {
                $this->addError($attribute, 'E-mail ou senha inválidos.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUsuario(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        }

        return false;
    }

    public function getUsuario()
    {
        if ($this->_usuario === false) {
            $this->_usuario = Usuario::findByEmail($this->email);
        }

        return $this->_usuario;
    }
}