<?php

namespace App\Dashboard\Handler;

use App\Dashboard\Query\ReviewEnvironments;
use App\Model\PullRequest\PullRequest;
use App\Model\PullRequest\Repository\PullRequestRepository;

class DashboardHandler
{
    const REVIEW_ENVIRONMENTS = 'review_environments';

    const PULL_REQUEST_TO_DEPLOY = 'pull_requests_to_deploy';

    const PULL_REQUEST_TO_MERGE_ON_DEV = 'pull_requests_to_merge_on_dev';

    /** @var PullRequestRepository */
    protected $repository;

    /** @var ReviewEnvironments */
    protected $reviewEnvironments;

    public function __construct(
        ReviewEnvironments $reviewEnvironments,
        PullRequestRepository $repository
    ) {
        $this->reviewEnvironments = $reviewEnvironments;
        $this->repository = $repository;
    }

    public function getData()
    {
        return [
            self::REVIEW_ENVIRONMENTS          => $this->reviewEnvironments->fetch(),
            self::PULL_REQUEST_TO_DEPLOY       => $this->repository->getPullRequests(PullRequest::STATE_OPEN, ['~validation-required']),
            self::PULL_REQUEST_TO_MERGE_ON_DEV => $this->repository->getPullRequests(PullRequest::STATE_OPEN, ['~validated'])
        ];
    }
}
