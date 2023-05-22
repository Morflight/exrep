<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class AbstractApiTest extends ApiTestCase
{
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();

        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $commands = [
            'doctrine:database:create',
            'doctrine:schema:create',
            'doctrine:fixtures:load',
        ];

        foreach ($commands as $command) {
            self::runCommand($application, $command, ['--env' => 'test']);
        }
    }

    public static function tearDownAfterClass(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        self::runCommand($application, 'doctrine:database:drop', ['--env' => 'test', '--force' => true]);
    }

    protected static function runCommand(Application $application, $commandName, $commandArguments = [])
    {
        $command = $application->find($commandName);
        $commandInput = new ArrayInput($commandArguments);
        $commandInput->setInteractive(false);
        $output = new BufferedOutput();

        $command->run($commandInput, $output);

        return $output->fetch();
    }

    protected function getBearer(): string
    {
        $authentication = <<<JSON
        {
            "email": "admin@gmail.com",
            "password": "root"
        }
        JSON;

        $client = static::createClient();
        $response = $client->request(
            'POST',
            '/api/login_check',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => $authentication,
            ]
        );

        $response = $response->toArray();

        return 'Bearer ' . $response['token'];
    }

    protected function requestEndpoint(string $method, string $uri, string $body = ''): array
    {
        $client = static::createClient();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => $this->getBearer(),
        ];

        if ($body !== '') {
            $headers['Content-Type'] = 'application/json';
        }

        $response = $client->request($method, $uri, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return $response->toArray();
    }
}
