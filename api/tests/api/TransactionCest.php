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
     * Register wallet test cases
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
        $I->testRegisterWallet($testCase['user_id'], $testCase['currency'], $this->token);
    }

    /**
     * Register wallet exception test cases
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

    /**
     * Set default wallet test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "code": "94ac57b635a2863dfc48c70c5834a4e7"}
     *
     * Test case 2
     * @example { "code": "dd110c2972a774615939342700b3e2c2"}
     *
     * Test case 3
     * @example { "code": "736101075d0bd6e424e85ff9f5d64e9f"}
     *
     * Test case 4
     * @example { "code": "bc44f3de5b0d5dcdbdc85e28d59c3f93"}
     *
     * Test case 5
     * @example { "code": "9e8dc15570ff98512a692e76e2679bb3"}
     */
    public function setDefaultWalletCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $wallet_code_ => wallet code
         * @param $token_ => valid user token
         */
        $I->testSetDefaultWallet($testCase['code'], $this->token);
    }

    /**
     * Set default wallet test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "code": "94ac57b635a2863dfc48c70c5834a4e7aaa", "expected_exception": "Bad Request" }
     *
     * Test case 2
     * @example { "code": "dd110c2972a774615939342700b3e2c2bbb", "expected_exception": "Bad Request" }
     *
     * Test case 3
     * @example { "code": "736101075d0bd6e424e85ff9f5d64e9fccc", "expected_exception": "Bad Request" }
     *
     * Test case 4
     * @example { "code": "bc44f3de5b0d5dcdbdc85e28d59c3f93ddd", "expected_exception": "Bad Request" }
     *
     * Test case 5
     * @example { "code": "9e8dc15570ff98512a692e76e2679bb3eee", "expected_exception": "Bad Request" }
     */
    public function setDefaultWalletCaseException(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $wallet_code_ => wallet code
         * @param $token_ => valid user token
         */
        $I->testSetDefaultWalletException($testCase['code'], $testCase['expected_exception'], $this->token);
    }

    /**
     * Deposit money test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "user_id": 1, "amount_currency": "brl", "wallet_currency": "brl", "amount": 100.32}
     *
     * Test case 2
     * @example { "user_id": 1, "amount_currency": "usd", "wallet_currency": "brl", "amount": 10.98}
     *
     * Test case 3
     * @example { "user_id": 1, "amount_currency": "eur", "wallet_currency": "jpy", "amount": 25.12}
     *
     * Test case 4
     * @example { "user_id": 1, "amount_currency": "cad", "wallet_currency": "usd", "amount": 100.24}
     *
     * Test case 5
     * @example { "user_id": 1, "amount_currency": "cad", "wallet_currency": "cad", "amount": 147.80}
     */
    public function depositMoneyCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $user_id => user ID
         * @param $amount_currency => amount to deposit currency
         * @param $wallet_currency => wallet currency
         * @param $amount => amount of money
         * @param $token_ => valid user token
         */
        $I->testDepositMoney(
            $testCase['user_id'],
            $testCase['amount_currency'],
            $testCase['wallet_currency'],
            $testCase['amount'],
            $this->token
        );
    }

    /**
     * Withdraw money test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "user_id": 1, "amount_currency": "brl", "wallet_currency": "brl", "amount": 98.50}
     *
     * Test case 2
     * @example { "user_id": 1, "amount_currency": "usd", "wallet_currency": "brl", "amount": 1.05}
     *
     * Test case 3
     * @example { "user_id": 1, "amount_currency": "eur", "wallet_currency": "jpy", "amount": 25.12}
     *
     * Test case 4
     * @example { "user_id": 1, "amount_currency": "cad", "wallet_currency": "usd", "amount": 87.65}
     *
     * Test case 5
     * @example { "user_id": 1, "amount_currency": "cad", "wallet_currency": "cad", "amount": 147.81}
     */
    public function withdrawMoneyCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $user_id => user ID
         * @param $amount_currency => amount to deposit currency
         * @param $wallet_currency => wallet currency
         * @param $amount => amount of money
         * @param $token_ => valid user token
         */
        $I->testWithdrawMoney(
            $testCase['user_id'],
            $testCase['amount_currency'],
            $testCase['wallet_currency'],
            $testCase['amount'],
            $this->token
        );
    }

    /**
     * Show balance test cases
     *
     * @param TransactionTester $I
     * @param Example $testCase
     *
     * Test case 1
     * @example { "user_id": 1, "currency": "brl" }
     *
     * Test case 2
     * @example { "user_id": 1, "currency": "usd" }
     *
     * Test case 3
     * @example { "user_id": 1, "currency": "eur" }
     *
     * Test case 4
     * @example { "user_id": 1, "currency": "jpy" }
     *
     * Test case 5
     * @example { "user_id": 1, "currency": "cad" }
     * 
     * Test case 6
     * @example { "user_id": 1, "currency": "all" }
     */
    public function showBalanceCase(TransactionTester $I, Example $testCase)
    {
        /**
         * Test cases
         *
         * @param $user_id => user ID
         * @param $currency => wallet currency || ''all
         * @param $token_ => valid user token
         */
        $I->testShowBalance($testCase['user_id'], $testCase['currency'], $this->token);
    }
}
