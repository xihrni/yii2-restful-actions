<?php

namespace xihrni\yii2\restful\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;

/**
 * 删除操作
 *
 * Class DeleteAction
 * @package xihrni\yii2\restful\actions
 */
class DeleteAction extends Action
{
    /**
     * 运行
     *
     * @param  int $id 主键
     * @return void
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id, 0);

        if (!$model->softDelete()) {
            throw new HttpException(500, json_encode($model->errors));
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
