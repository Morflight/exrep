<?php

namespace App\Tests\Api;

class CompanyTest extends AbstractApiTest
{
    public function testGetCompanies(): void
    {
        $response = $this->requestEndpoint('GET', '/api/companies');

        $this->assertCount(5, $response);
    }

    public function testGetCompany(): void
    {
        $expected = ['name' => 'LVMH'];

        $response = $this->requestEndpoint('GET', '/api/companies/1');

        $this->assertEquals($expected, $response);
    }
}
