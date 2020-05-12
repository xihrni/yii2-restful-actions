<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;

/**
 * 更新操作
 *
 * Class UpdateAction
 * @package xihrni\yii2\restful\actions
 */
class UpdateAction extends Action
{
    /**
     * @var array [$fields = []] 只允许更新的字段
     */
    public $fields = [];


    /**
     * 运行
     *
     * @param  int $id 主键
     * @return object
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model  = $this->findModel($id, 0);
        $model->load(Yii::$app->request->post(), '');
        $fields = $this->fields;
        $result = count($fields) ? $model->save(true, $fields) : $model->save();

        if ($result) {
            return $model;
        } else {
            throw new HttpException(422, json_encode($model->errors));
        }
    }
}
