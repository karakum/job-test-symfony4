<?php

namespace App\Test\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TextControllerTest extends WebTestCase
{

    public function testGet()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }

    public function testPost()
    {
        $client = static::createClient();

        $client->request('POST', '/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'job' => [
                'text' => 'Привет, мне на <a href="test@test.ru">test@test.ru</a> пришло приглашение встретиться, попить кофе с <strong>10%</strong> содержанием молока за <i>$5</i>, пойдем вместе!',
                'methods' => [
                    'stripTags',
                    'removeSpaces',
                    'replaceSpacesToEol',
                    'htmlspecialchars',
                    'removeSymbols',
                    'toNumber',
                ],
            ],
        ]));


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent(), 'Content emty');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('text', $data, 'Has text key');
        $this->assertEquals('10', $data['text']);
    }

    public function testUnknownTextMethod()
    {
        $client = static::createClient();

        $client->request('POST', '/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'job' => [
                'text' => 'Привет, мне на <a href="test@test.ru">test@test.ru</a> пришло приглашение встретиться, попить кофе с <strong>10%</strong> содержанием молока за <i>$5</i>, пойдем вместе!',
                'methods' => [
                    'stripTags',
                    'removeSpaces',
                    'replaceSpacesToEol',
                    'htmlspecialchars',
                    'removeSymbols',
                    'toNumber',
                    'unknown',
                ],
            ],
        ]));


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent(), 'Content emty');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('text', $data, 'Has text key');
        $this->assertEquals('10', $data['text'], 'Text equal "10"');
        $this->assertArrayHasKey('errors', $data, 'Has errors key');
        $this->assertContains("Method 'unknown' not implemented", $data['errors'], 'Has error of unknown method');
    }


}