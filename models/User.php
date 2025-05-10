<?php
namespace app\models;

use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface
{
    public $id = 1;
    public $username;

    public static function findIdentity($id)
    {
        return $id == 1 ? new static() : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByUsername($username)
    {
        return $username === \Yii::$app->params['adminUsername'] ? new static() : null;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, \Yii::$app->params['adminPasswordHash']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
}