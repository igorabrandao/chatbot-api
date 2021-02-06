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

    /**
     * Set default wallet creation process
     *
     * @access public
     * @param $wallet_code_ => wallet code
     * @param $token_ => valid user token
     */
    public function testSetDefaultWallet($wallet_code_, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test to set the wallet ' . $wallet_code_ . ' as default');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'wallet/set-default-wallet', ['code' => $wallet_code_]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseEquals("true");
    }

    /**
     * Set default wallet exception handler
     *
     * @access public
     * @param $wallet_code_ => wallet code
     * @param $token_ => valid user token
     */
    public function testSetDefaultWalletException($wallet_code_, $expected_exception, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test to set the wallet ' . $wallet_code_ . ' as default');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'wallet/set-default-wallet', ['code' => $wallet_code_]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400

        // Check if the exception was caught successfully
        $I->seeResponseContainsJson(['name' => $expected_exception]);
    }

    /**
     * Test the deposit money process
     *
     * @access public
     * @param $user_id => user ID
     * @param $amount_currency => amount currency
     * @param $wallet_currency => wallet currency
     * @param $amount => amount mmoney
     * @param $token_ => valid user token
     */
    public function testDepositMoney($user_id, $amount_currency, $wallet_currency, $amount, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test the deposit of ' . $amount . ' ' . $amount_currency . ' to ' . $wallet_currency . ' wallet');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'transaction/deposit-money', ['user_id' => $user_id, 'amount_currency' => $amount_currency,
        'wallet_currency' => $wallet_currency, 'amount' => $amount]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['user_id' => $user_id, 'currency' => strtoupper($wallet_currency)]);
    }

    /**
     * Test the withdraw money process
     *
     * @access public
     * @param $user_id => user ID
     * @param $amount_currency => amount currency
     * @param $wallet_currency => wallet currency
     * @param $amount => amount mmoney
     * @param $token_ => valid user token
     */
    public function testWithdrawMoney($user_id, $amount_currency, $wallet_currency, $amount, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Test the withdraw of ' . $amount . ' ' . $amount_currency . ' from ' . $wallet_currency . ' wallet');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'transaction/withdraw-money', ['user_id' => $user_id, 'amount_currency' => $amount_currency,
        'wallet_currency' => $wallet_currency, 'amount' => $amount]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['user_id' => $user_id, 'currency' => strtoupper($wallet_currency)]);
    }

    /**
     * Test the withdraw money process
     *
     * @access public
     * @param $user_id => user ID
     * @param $wallet_currency => wallet currency
     * @param $token_ => valid user token
     */
    public function testShowBalance($user_id, $wallet_currency, $token_)
    {
        // Get the unit test context
        $I = $this;

        // Oauth user authorization
        $I->amBearerAuthenticated($token_);

        // Identify the action
        $I->wantTo('Show balance of ' . $wallet_currency . ' wallet');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send post data
        $I->sendPOST($this->host . 'transaction/show-wallet-balance', ['user_id' => $user_id, 
        'currency' => $wallet_currency]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['user_id' => $user_id, 'currency' => strtoupper($wallet_currency)]);
    }
}