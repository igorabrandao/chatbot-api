<?php
/**
 * Class PushNotificationIOS is used to send PUSH notifications to iOS devices.
 *
 * @property string $certificatePath Path of PEM certificate
 * @property string $certificatePassword Password of PEM certificate
 * @property string $title Title of the notification to be sent
 * @property string $message Text of notification
 * @property string $count Number that appears next to the message
 * @property string $audio Sound to be played upon arrival of the notification
 * @property string $destinations Array with the registration IDs of recipients devices
 * @property string $sandboxMode Default: false. Set to true only if it is necessary to test the sending of notifications
 */

namespace api\modules\v1\components;

use Yii;
use yii\base\Component;

class PushNotificationIOS extends Component
{
    public $certificatePath;
    public $certificatePassword;
    public $title;
    public $message;
    public $count;
    public $audio;
    public $destinations;
    public $sandboxMode = false;

    /**
     * Performs sends notification through APN PUSH service
     * @param bool $sandboxMode
     */
    function __construct($sandboxMode = false)
    {
        $this->sandboxMode = $sandboxMode;
        $this->certificatePath = $this->sandboxMode ? Yii::getAlias('@certifiedSandbox') : Yii::getAlias('@certified');
    }

    public function send()
    {
        $validate = $this->validate();
        if ($validate) {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificatePath);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $this->certificatePassword);
            $sock = stream_socket_client($this->sandboxMode == true ? 'ssl://gateway.sandbox.push.apple.com:2195' : 'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            $data = json_encode(array('aps' => $this->formatNotification()));
            $pre = chr(0) . pack('n', 32);
            $pos = pack('n', strlen($data)) . $data;
            $status = array();
            foreach ($this->destinations as $token) {
                $msg = $pre . pack('H*', $token) . $pos;
                $result = fwrite($sock, $msg, strlen($msg));
                if ($result) $status['succ'][] = $token;
                else $status['fail'][] = $token;
            }
            fclose($sock);
            return $status;
        } else {
            return $validate;
        }
    }

    /**
     * Validate all of the required fields of the class
     * @return bool|string
     */
    public function validate()
    {
        if (empty($this->certificatePath))
            return "Error: Path of certificate is required";
        if (empty($this->title))
            return "Error: Title is required";
        if (empty($this->message))
            return "Error: Message is required";
        if (empty($this->destinations))
            return "Error: At least one destination is required";
        else
            return true;
    }

    /**
     * Formats the notification to a correct format to be sent to the PUSH service
     * @return array
     */
    public function formatNotification()
    {
        $notification = array();

        $notification['alert'] = $this->title . " \n" . $this->message;

        if (!empty($this->count))
            $notification['badge'] = $this->count;
        if (!empty($this->audio))
            $notification['sound'] = $this->audio;
        else
            $notification['sound'] = '';

        return $notification;
    }
}