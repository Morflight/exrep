<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Aws\S3\S3Client;
use App\Entity\ExpenseReport;

class AWSController extends AbstractController
{
    private string $awsAccessKey;
    private string $awsSecretKey;
    private string $region;
    private string $bucketName;

    public function __construct(
        string $awsAccessKey,
        string $awsSecretKey,
        string $region,
        string $bucketName
    ) {
        $this->awsAccessKey = $awsAccessKey;
        $this->awsSecretKey = $awsSecretKey;
        $this->region = $region;
        $this->bucketName = $bucketName;
    }

    #[Route('/api/upload-reports-to-s3', name: 'upload_reports_to_s3', methods: ['GET'])]
    public function uploadReportsToS3Action(EntityManagerInterface $em): Response
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => $this->region,
            'credentials' => [
                'key'    => $this->awsAccessKey,
                'secret' => $this->awsSecretKey,
            ],
        ]);

        $expenseReports = $em->getRepository(ExpenseReport::class)->findAll();

        // Serialize ExpenseReport entities to CSV format
        $csvData = '';
        foreach ($expenseReports as $report) {
            $csvData .= $report->getId() . ',';
            $csvData .= $report->getExpenseDate()->format('Y-m-d H:i:s') . ',';
            $csvData .= $report->getAmount() . ',';
            $csvData .= $report->getRegistrationDate()->format('Y-m-d H:i:s') . "\n";
        }

        // Upload the CSV data to S3
        $s3->putObject([
            'Bucket' => $this->bucketName,
            'Key'    => 'expense_reports.csv',
            'Body'   => $csvData,
        ]);

        return new Response('Expense reports uploaded to S3 successfully.');
    }
}
