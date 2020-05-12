<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\web\HttpException;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * 创建操作
 *
 * Class CreateAction
 * @package xihrni\yii2\restful\actions
 */
class CreateAction extends Action
{
    /**
     * @var string [$viewAction = 'view'] 详情操作
     */
    public $viewAction = 'view';

    /**
     * @var bool [$isLocation = false] 是否重定向
     */
    public $isLocation = false;


    /**
     * 运行
     *
     * @return object 模型类对象
     * @throws HttpException
     */
    public function run()
    {
        /* @var $model ActiveRecord */
        $model = new $this->modelClass;
        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            if ($this->isLocation) {
                $id = implode(',', array_values($model->getPrimaryKey(true)));
                Yii::$app->getResponse()
                    ->setStatusCode(201)
                    ->getHeaders()
                    ->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
            } else {
                return $model;
            }
        } else {
            throw new HttpException(422, json_encode($model->errors));
        }
    }
}
