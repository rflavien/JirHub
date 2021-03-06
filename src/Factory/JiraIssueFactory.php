<?php

namespace App\Factory;

use App\Model\JiraIssue;
use App\Model\JiraIssueStatus;
use App\Model\JiraIssueType;
use GuzzleHttp\Psr7\Uri;

class JiraIssueFactory
{
    /** @var string */
    private $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function create(array $issueData): JiraIssue
    {
        return new JiraIssue(
            $issueData['key'],
            new JiraIssueStatus(
                $issueData['fields']['status']['id'],
                $issueData['fields']['status']['name']
            ),
            new JiraIssueType(
                $issueData['fields']['issuetype']['id'],
                $issueData['fields']['issuetype']['name']
            ),
            new Uri(
                sprintf(
                    '%s/browse/%s',
                    $this->host,
                    $issueData['key']
                )
            )
        );
    }
}
