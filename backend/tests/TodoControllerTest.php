<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testGetTodos(): void
    {
        // 'createClient()' gives us a client that acts like a browser.
        $client = static::createClient();

        // 1. Create a new To-Do item first to ensure our database is not empty.
        $client->request(
            'POST',
            '/api/todos',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "Test Todo Item"}'
        );

        // 2. Now, request the endpoint we want to test.
        $client->request('GET', '/api/todos');

        // Assert that the response was successful (HTTP status code 200-299).
        $this->assertResponseIsSuccessful();

        // Assert that the response is in JSON format.
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Assert that the JSON response contains our created item's title.
        $this->assertStringContainsString('Test Todo Item', $client->getResponse()->getContent());
    }
}
