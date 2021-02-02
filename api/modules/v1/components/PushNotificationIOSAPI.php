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
use Pushok\AuthProvider;
use Pushok\Client;
use Pushok\Notification;
use Pushok\Payload;
use Pushok\Payload\Alert;


class PushNotificationIOSAPI extends Component
{
    public $title;
    public $message;
    public $count;
    public $audio;
    public $destinations;
    public $production;
    public $appBundleId;

    /**
     * Performs sends notification through APN PUSH service
     * @param bool $sandboxMode
     */
    function __construct($appBundleId = 'br.com.interativadigital.webfarma')
    {
        $this->production = !YII_ENV_DEV;
        $this->appBundleId = $appBundleId;
    }

    public function send()
    {
        $validate = $this->validate();
        if (!$validate) {
            return $validate;
        }

        $options = [
            'key_id' => 'LKLPZ64AR9', // The Key ID obtained from Apple developer account
            'team_id' => '5P927JRGH9', // The Team ID obtained from Apple developer account
            'app_bundle_id' => $this->appBundleId, // The bundle ID for app obtained from Apple developer account
            'private_key_path' => __DIR__ . '/AuthKey_2XCCX2CZSB.p8', // Path to private key
            'private_key_secret' => null // Private key secret
        ];

        $authProvider = AuthProvider\Token::create($options);

        $alert = Alert::create()->setTitle($this->title);
        $alert = $alert->setBody($this->message);

        $payload = Payload::create()->setAlert($alert);

        //set notification sound to default
        $payload->setSound('default');

        //add custom value to your notification, needs to be customized
        // $payload->setCustomValue('key', 'value');

        $deviceTokens = $this->destinations;

        $notifications = [];
        foreach ($deviceTokens as $deviceToken) {
            $notifications[] = new Notification($payload,$deviceToken);
        }

        $client = new Client($authProvider, $production = $this->production);
        $client->addNotifications($notifications);



        $responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

        $result = ["devices" => []];

        foreach ($responses as $response) {
            $responseData = [
                "device" => $response->getDeviceToken(),
                "ApnsId" => $response->getApnsId(),
                "statusCode" => $response->getStatusCode(),
                "reasonPhrase" => $response->getReasonPhrase(),
                "errorReason" => $response->getErrorReason(),
                "errorDescription" => $response->getErrorDescription()
            ];
            array_push($result["devices"], $responseData);
        }

        return $result;
    }

    /**
     * Validate all of the required fields of the class
     * @return bool|string
     */
    public function validate()
    {
        if (empty($this->title))
            return "Error: Title is required";
        if (empty($this->message))
            return "Error: Message is required";
        if (empty($this->destinations))
            return "Error: At least one destination is required";
        else
            return true;
    }
}
