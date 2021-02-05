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
class ChatbotTester extends ApiTester
{
    /**
     * Define the tested host
     * @var string
     */
    private $host = "https://chatbotapi.igorabrandao.com.br/v1/";

    /**
     * Test Chatbot welcome message generation process
     *
     * @access public
     * @param $expected_result => text there is expected to be inside the generated result
     */
    public function testGenerateWelcomeMessage($expected_result)
    {
        // Get the unit test context
        $I = $this;

        // Identify the action
        $I->wantTo('Test chatbot welcome message generation');

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send get data
        $I->sendGET($this->host . 'chatbot/generate-welcome-message');

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['message' => $expected_result]);
    }

    /**
     * Test Chatbot welcome message generation process
     *
     * @access public
     * @param $message => the message sent to the chatbot
     * @param $expected_result => text there is expected to be inside the generated result
     */
    public function testchatbotIdentifyIntent($message, $expected_result)
    {
        // Get the unit test context
        $I = $this;

        // Identify the action
        $I->wantTo('Test chatbot intent identification of mesagge: ' . $message);

        // Post header
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Send get data
        $I->sendGET($this->host . 'chatbot/receive-message');

        $I->sendPOST($this->host . 'chatbot/receive-message', ['message' => $message]);

        // Check the return status
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        // Check if the response was successful
        $I->seeResponseContainsJson(['message' => $expected_result]);
    }
}