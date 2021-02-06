<?php

namespace tests\api;

use tests\ApiTester;
use tests\ChatbotTester;
use Codeception\Example;

/**
 * Class ChatbotCest
 */
class ChatbotCest
{
    /**
     * This method always executes before the test
     *
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        // TODO
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
     * Chatbot extraction test cases
     *
     * @param ChatbotTester $I
     * @param Example $testCase
     *
     * Test case 01
     * @example { "expected_result": "Hey there, we are ready to rumble!"}
     */
    public function generateWelcomeMessageCase(ChatbotTester $I, Example $testCase)
    {
        /**
         * Test cases
         * 
         * @param $expected_result => text there is expected to be inside the generated result
         */
        $I->testGenerateWelcomeMessage($testCase['expected_result']);
    }

    /**
     * Chatbot extraction test cases
     *
     * @param ChatbotTester $I
     * @param Example $testCase
     *
     * Test case 01
     * @example { "message": "access my account", "expected_result": "login"}
     * 
     * Test case 02
     * @example { "message": "disconnect", "expected_result": "logout"}
     * 
     * Test case 03
     * @example { "message": "create account", "expected_result": "register"}
     * 
     * Test case 04
     * @example { "message": "exchange money", "expected_result": "quotation"}
     * 
     * Test case 05
     * @example { "message": "set default currency", "expected_result": "setCurrency"}
     * 
     * Test case 06
     * @example { "message": "send money", "expected_result": "deposit"}
     * 
     * Test case 07
     * @example { "message": "give me my money!", "expected_result": "withdraw"}
     * 
     * Test case 08
     * @example { "message": "show my account balance", "expected_result": "showBalance"}
     * 
     * Test case 09
     * @example { "message": "cancel operation", "expected_result": "cancel"}
     * 
     * Test case 10
     * @example { "message": "hey there", "expected_result": "greeting"}
     * 
     */
    public function chatbotIdentifyIntentCase(ChatbotTester $I, Example $testCase)
    {
        /**
         * Test cases
         * 
         * @param $expected_result => text there is expected to be inside the generated result
         */
        $I->testchatbotIdentifyIntent($testCase['message'], $testCase['expected_result']);
    }
}