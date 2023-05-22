<?php

namespace App\Tests\Api;

use App\Entity\ExpenseReport;
use Symfony\Component\HttpClient\Exception\TransportException;

class ExpenseReportApiTest extends AbstractApiTest
{
    public function testGetExpenseReports(): void
    {
        $response = $this->requestEndpoint('GET', '/api/expense_reports');
        $this->assertCount(30, $response);

        $response = $this->requestEndpoint('GET', '/api/expense_reports?page=4');
        $this->assertCount(10, $response);

        $response = $this->requestEndpoint('GET', '/api/expense_reports?company.name=Michelin');
        $this->assertCount(25, $response);

        $response = $this->requestEndpoint('GET', '/api/expense_reports?amount[lt]=50');
        $this->assertNotEmpty($response);
    }

    public function testGetExpenseReport(): void
    {
        $expectedSubSet = [
            'id' => 1,
            'expenseDate' => '2023-05-15',
            'registrationDate' => '2023-05-15 12:25:00',
            'company' => [
                'name' => 'LVMH',
            ],
        ];

        $response = $this->requestEndpoint('GET', '/api/expense_reports/1');

        $this->assertArraySubset($expectedSubSet, $response);
        $this->assertGreaterThanOrEqual(0, $response['amount']);
        $this->assertLessThanOrEqual(100, $response['amount']);
        $this->assertContainsEquals($response['type'], ExpenseReport::EXPENSE_TYPES);
    }

    public function testPostExpenseReport(): void
    {
        $expected = [
            'id' => 101,
            'amount' => 99.9,
            'expenseDate' => '2023-05-22',
            'registrationDate' => '2023-05-15 12:25:00',
            'company' => [
                'name' => 'Bedrock',
            ],
            'type' => 'Gas Expense',
        ];

        $payload = <<<JSON
        {
            "amount": 99.9,
            "expenseDate": "2023-05-22",
            "registrationDate": "2023-05-15 12:25:00",
            "company": "api/companies/3",
            "type": "Gas Expense"
        }
        JSON;

        $response = $this->requestEndpoint('POST', '/api/expense_reports', $payload);

        $this->assertEquals($expected, $response);
    }

    public function testPutExpenseReport(): void
    {
        $expected = [
            'id' => 101,
            'amount' => 99.99,
            'expenseDate' => '2023-05-20',
            'registrationDate' => '2023-05-15 12:25:00',
            'company' => [
                'name' => 'Bedrock',
            ],
            'type' => 'Gas Expense',
        ];

        $payload = <<<JSON
        {
            "amount": 99.99,
            "expenseDate": "2023-05-20",
            "registrationDate": "2023-05-15 12:25:00",
            "company": "api/companies/3",
            "type": "Gas Expense"
        }
        JSON;

        $response = $this->requestEndpoint('PUT', '/api/expense_reports/101', $payload);

        $this->assertEquals($expected, $response);
    }

    public function testDeleteExpenseReport(): void
    {
        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Response body is empty.');

        $this->requestEndpoint('DELETE', '/api/expense_reports/101');

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Response body is empty.');

        $this->requestEndpoint('GET', '/api/expense_reports/101');
    }
}
