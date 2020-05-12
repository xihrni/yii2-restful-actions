<?php

namespace xihrni\yii2\restful\actions;

use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * 详情操作
 *
 * Class ViewAction
 * @package xihrni\yii2\restful\actions
 */
class ViewAction extends Action
{
    /**
     * @var int [$status = 0] 状态
     */
    public $status = 0;


    /**
     * 详情
     *
     * @param  int $id 主键
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        return $this->findModel($id, $this->status);
    }
}
