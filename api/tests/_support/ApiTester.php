<?php

namespace tests;

use Codeception\Util\HttpCode;
use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends Actor
{
    use _generated\ApiTesterActions;

    /**
     * Method to perform the user authorization according to OAuth pattern
     *
     * @access protected
     * @param $url_ => post url
     * @param $email_ => user identification
     * @param $password_ => access password
     *
     * @return string
     */
    public function userAuthorization($url_, $email_, $password_)
    {
        // Get the unit test context
        $I = $this;

        // Identify the action
        $I->wantTo('Authenticate user ' . $email_);

        // Send the post information
        $I->sendPOST($url_, ['email' => $email_, 'password' => $password_]);

        // Check if the response was successful (code 200)
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $response = $I->grabResponse();
        $responseArray = json_decode($response, true);
        $token = $responseArray['access_token'];

        // Get the token from the post return
        //$token = $I->grabDataFromJsonResponse('access_token');
        //$token = $token[0];

        // Return the token
        return $token;
    }
}