<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\MailSchedule;
use app\models\MessageType;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Client;

/**
 * Mail Queueu populate.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MailQueueController extends Controller
{
    /**
     * Generate mail queue.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionSend($email, $message)
    {
            $client = new Client();

            try{
                $response = $client->post('https://email-service.digitalenvision.com.au/send-email', [
                    'json' => [
                        'message' => $message,
                        "email" => $email
                    ]
                ]);
                if(($response->getStatusCode())){
                    return true;
                }
            }catch(\Exception $e){
                return false;
            }
    }

    public function actionProcessMailQueue()
    {

        $currentUnixTimestamp = time();
        $mailQueue = MailSchedule::find()->where([
            'status' => [
                MailSchedule::STATUS_ACTIVE, 
                MailSchedule::STATUS_FAILED
                ]
            ])->andWhere(['<','to_sent_at', $currentUnixTimestamp])->all();

        foreach($mailQueue as $item){
            if($this->actionSend($item->recipient, $item->body) == true){
                $item->status = MailSchedule::STATUS_SENT;
                $item->save(false);
            }else{
                $item->status = MailSchedule::STATUS_FAILED;
                $item->save(false);
            }

            echo $item->id.PHP_EOL;
        }
    }

    /**
     * Populate email queue based data from customer_user table, if there is no active queue, it will push one
     *
     * @return void
     */
    public function actionPopulateEmailQueue()
    {
        $sendEmailAt = 9;
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT DISTINCT cu.id as user_id,cu.email, cu.firstname, cu.lastname ,cu.birthday, cu.time_offset, COUNT(ms.id) as count FROM customer_user cu 
            LEFT JOIN mail_schedule ms 
            ON ms.recipient_user_id = cu.id AND ms.status IN(1,2)
            GROUP BY cu.email, cu.birthday, cu.time_offset, ms.id, cu.id, cu.firstname, cu.lastname;
        ");

        $results = $command->queryAll();

        $messageTypes = MessageType::find()->all();
        $messageTypesArr = ArrayHelper::map($messageTypes,'id','template');

        foreach($results as $result)
        {
            if(intval($result['count']) == 0){
                $fullname = $result['firstname'] . ' ' . $result['lastname'];
                $customerTimezone = ($result['time_offset'] > 0)? '+'.$result['time_offset']: $result['time_offset'];
                $customerBirthDate = new \DateTime($result['birthday'], new \DateTimeZone($customerTimezone));

                $currDateObj = new \DateTime('now', new \DateTimeZone($customerTimezone));
                $targetYear = date('Y');

                if(intval($currDateObj->format('n')) > intval($customerBirthDate->format('n'))){
                    //set notification for those birthday month already passed to next year
                    $targetYear = date('Y', strtotime('+1 year'));
                }else if(intval($currDateObj->format('n')) == intval($customerBirthDate->format('n'))){
                    if(intval($currDateObj->format('j')) > intval($customerBirthDate->format('j')) ){
                        //set notification for those birthday month and day already passed to next year
                        $targetYear = date('Y', strtotime('+1 year'));
                    }
                }

                foreach($messageTypesArr as $messageTypeId => $template){
                    $body = str_replace('{full_name}', $fullname, $template);
                    $dateTime = new \DateTime($targetYear.'-'.$customerBirthDate->format('m-d').' '.$sendEmailAt.':00:00', new \DateTimeZone($customerTimezone));
                    $toSendAt = $dateTime->format('U');
                    $queue = new MailSchedule([
                        'message_type' => $messageTypeId,
                        'subject' => 'Happy BirthDay',
                        'recipient' => $result['email'],
                        'recipient_user_id' => $result['user_id'],
                        'body' => $body,
                        'to_sent_at' => $toSendAt,
                        'status' => MailSchedule::STATUS_ACTIVE
                    ]);
                    $queue->save(false);
                }
            }
        }
       
    }
}
