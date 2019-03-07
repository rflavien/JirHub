<?php

namespace App\Model\PullRequest\Repository;

use App\Model\PullRequest\PullRequest;
use Github\Client as KnpLabsGitHubClient;

class KnpLabsPullRequestRepository implements PullRequestRepository
{
    const RESULTS_PER_PAGE = 50;

    /** @var KnpLabsGitHubClient */
    public $client;

    public function __construct()
    {
        $this->client = new KnpLabsGitHubClient();
        $this->client->authenticate(getenv('GITHUB_TOKEN'), null, KnpLabsGitHubClient::AUTH_HTTP_TOKEN);
    }

    public function getPullRequest(int $id): PullRequest
    {
        $pullRequestData = $this->client->api('pull_request')->show(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $id
        );

        return $this->buildPullRequest($pullRequestData);
    }

    public function getPullRequests(
        ?string $state = null,
        array $labels = []
    ): array {
        $pullRequestsData = $this->client->api('pull_request')->all(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            [
                'per_page' => self::RESULTS_PER_PAGE,
                'labels'   => $labels,
            ] + (null === $state) ? [] : ['state' => $state]
        );

        return array_map(
            function ($pullRequestData) {
                return $this->buildPullRequest($pullRequestData);
            },
            $pullRequestsData
        );
    }

    public function updatePullRequestBody(PullRequest $pullRequest, string $body): PullRequest
    {
        $this->client->api('pull_request')->update(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $pullRequest->getNumber(),
            ['body' => $body]
        );

        return $pullRequest->setBody($body);
    }

    public function addLabelToPullRequest(PullRequest $pullRequest, string $label): PullRequest
    {
        $this->client->api('issue')->labels()->add(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $pullRequest->getNumber(),
            $label
        );

        return $pullRequest->addLabel($label);
    }

    public function removeLabelFromPullRequest(PullRequest $pullRequest, string $label): PullRequest
    {
        $this->client->api('issue')->labels()->remove(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $pullRequest->getNumber(),
            $label
        );

        return $pullRequest->removeLabel($label);
    }

    public function mergePullRequest(PullRequest $pullRequest, string $mergeMethod = 'squash'): void
    {
        $this->client->api('pull_request')->merge(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $pullRequest->getNumber(),
            'Merged by JirHub',
            $pullRequest->getHeadSha(),
            $mergeMethod,
            $pullRequest->getTitle()
        );
    }

    protected function getPullRequestReviews(PullRequest $pullRequest): array
    {
        $reviews = $this->client->api('pull_request')->reviews()->all(
            getenv('GITHUB_REPOSITORY_OWNER'),
            getenv('GITHUB_REPOSITORY_NAME'),
            $pullRequest->getNumber(),
            ['per_page' => self::RESULTS_PER_PAGE]
        );

        return $reviews;
    }

    protected function buildPullRequest(array $pullRequestData): PullRequest
    {
        $pullRequestLabels = [];

        foreach ($pullRequestData['labels'] as $label) {
            $pullRequestLabels[] = $label['name'];
        }

        $pullRequest = new PullRequest(
            $pullRequestData['number'],
            $pullRequestData['title'],
            $pullRequestData['body'],
            $pullRequestData['head']['ref'],
            $pullRequestData['base']['ref'],
            $pullRequestData['html_url'],
            $pullRequestData['head']['sha'],
            $pullRequestData['user']['login'],
            $pullRequestLabels,
            []
        );

        return $pullRequest->setReviews($this->getPullRequestReviews($pullRequest));
    }
}
