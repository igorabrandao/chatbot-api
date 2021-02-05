<?php

namespace tests\api;

use tests\ApiTester;
use tests\TransactionTester;
use Codeception\Example;

/**
 * Class TransactionCest
 */
class TransactionCest
{
    /**
     * User authentication settings
     *
     * @var string
     */
    private $url = "https://chatbotapi.igorabrandao.com.br/v1/users/login";
    private $email = "igorabrandao@outlook.com";
    private $password = "mudar123";
    private $token = "";

    /**
     * This method always executes before the test
     *
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        // Perform the user validation
        $this->token = $I->userAuthorization($this->url, $this->email, $this->password);
    }

    /**
     * This method always executes after the test
     *
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {
        // TODO
    }

    /**
     * Transaction extraction test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "user_id": 1, "currency": "brl"}
     *
     * Test case 2
     * @example { "user_id": 1, "currency": "usd"}
     *
     * Test case 3
     * @example { "user_id": 1, "currency": "eur"}
     *
     * Test case 4
     * @example { "user_id": 1, "currency": "cad"}
     *
     * Test case 5
     * @example { "user_id": 1, "currency": "jpy"}
     */
    public function registerWalletCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $user_id => user ID
         * @param $currency => currency code
         * @param $token_ => valid user token
         */
        //$I->testRegisterWallet($testCase['user_id'], $testCase['currency'], $this->token);
    }

    /**
     * Transaction extraction test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "user_id": 1, "currency": "xyz", "expected_exception": "Bad Request"}
     * 
     * Test case 2
     * @example { "user_id": 1, "currency": "jyp", "expected_exception": "Bad Request"}
     * 
     * Test case 3
     * @example { "user_id": 1, "currency": "usd1", "expected_exception": "Bad Request"}
     */
    public function registerWalletExceptionCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $user_id => user ID
         * @param $currency => currency code
         * @param $token_ => valid user token
         */
        $I->testRegisterWalletException($testCase['user_id'], $testCase['currency'], $testCase['expected_exception'], $this->token);
    }
}
