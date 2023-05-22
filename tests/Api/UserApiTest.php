<?php

namespace App\Tests\Api;

class UserApiTest extends AbstractApiTest
{
    public function testGetUser(): void
    {
        $expected = [
            'firstName' => 'Admin',
            'lastName'  => 'John',
            'email' => 'admin@gmail.com',
            'dateOfBirth' => '1990-01-01 00:00:00',
        ];

        $response = $this->requestEndpoint('GET', '/api/users/1');

        $this->assertEquals($expected, $response);
    }
}
