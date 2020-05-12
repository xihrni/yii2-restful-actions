# Yii2 RESTFul 操作
基于 Yii2 的 RESTFul 风格框架操作类

## Install
```composer
$ composer require xihrni/yii2-restful-actions
```

## Usage
```php
<?php

namespace app\controllers;

class ArticleController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Article';


    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => 'xihrni\yii2\restful\actions\IndexAction',
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => 'xihrni\yii2\restful\actions\ViewAction',
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => 'xihrni\yii2\restful\actions\CreateAction',
                'modelClass' => $this->modelClass,
            ],
            'update' => [
                'class' => 'xihrni\yii2\restful\actions\UpdateAction',
                'modelClass' => $this->modelClass,
            ],
            'delete' => [
                'class' => 'xihrni\yii2\restful\actions\DeleteAction',
                'modelClass' => $this->modelClass,
            ],
            'status' => [
                'class' => 'xihrni\yii2\restful\actions\StatusAction',
                'modelClass' => $this->modelClass,
            ],
        ]);
    }
}
```