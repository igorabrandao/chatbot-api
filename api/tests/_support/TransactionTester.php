<?php

namespace tests;

use Codeception\Util\HttpCode;
use PHPUnit_Framework_Assert;

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
class TransactionTester extends ApiTester
{
    /**
     * Define the tested host
     * @var string
     */
    private $host = "https://chatbotapi.igorabrandao.com.br/v1/";


    /**
     * Test wallet creation process
     *
     * @access public
     * @param $user_id => user ID
     * @param $currency => currency
     * @param $token_ => valid user token
     */
    public function testRegisterWallet($user_id, $currency, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test the creation of ' . $currency . ' wallet');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'wallet/register-wallet', ['user_id' => $user_id, 'currency' => $currency]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['user_id' => $user_id, 'currency' => strtoupper($currency)]);
    }

    /**
     * Test wallet creation process error handle
     *
     * @access public
     * @param $user_id => user ID
     * @param $currency => currency
     * @param $expected_exception => expected exception according to the test case
     * @param $token_ => valid user token
     */
    public function testRegisterWalletException($user_id, $currency, $expected_exception, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test the creation of ' . $currency . ' wallet');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'wallet/register-wallet', ['user_id' => $user_id, 'currency' => $currency]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400

        // Check if the exception was caught successfully
        $I->seeResponseContainsJson(['name' => $expected_exception]);
    }
}