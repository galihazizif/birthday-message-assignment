<?php

namespace app\controllers;

use app\models\CustomerUser;
use app\models\MailSchedule;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends \yii\rest\Controller
{


     /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [
            'index' => ['POST','DELETE','GET'],
        ];
    }

    public function actionIndex($id = null)
    {
        $request = \Yii::$app->request;
        switch($request->method){
            case 'POST':
                return $this->create();
            break;
            case 'DELETE':
                return $this->delete($id);
            break;
        }
    }

    public function create()
    {
        $customerUser = new CustomerUser(\Yii::$app->request->post());

        if($customerUser->validate()){
            $customerUser->save(false);
            return $customerUser->attributes;
        }else{
            throw new BadRequestHttpException(json_encode($customerUser->getFirstErrors()));
        }
    }

    public function delete($id)
    {
        $customerUser = CustomerUser::findOne($id);

        if(!empty($customerUser)){
            MailSchedule::deleteAll(['recipient_user_id' => $id]);
            $customerUser->delete();
            return ['status' => 'OK'];
        }else{
            throw new NotFoundHttpException("user ID Not Found");
        }
    }

}
