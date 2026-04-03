<?php

namespace app\components;

use Yii;

class AuthHelper
{
    public static function isAdmin()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->perfil === 'ADMIN';
    }

    public static function isAluno()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->perfil === 'ALUNO';
    }

    public static function isCoordenador()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->perfil === 'COORDENADOR';
    }
}