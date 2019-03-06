<?php

namespace App\Dashboard\Query\Adapter;

use App\Dashboard\Query\ReviewEnvironments;
use App\Model\PullRequest\PullRequest;
use App\Model\PullRequest\Repository\PullRequestRepository;
use App\Model\ReviewEnvironment;

class FromRepositoryReviewEnvironments implements ReviewEnvironments
{
    /** @var PullRequestRepository */
    protected $repository;

    public function __construct(PullRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch(): array
    {
        $environments = [
            new ReviewEnvironment('red'),
            new ReviewEnvironment('blue'),
            new ReviewEnvironment('green'),
            new ReviewEnvironment('yellow'),
        ];

        foreach ($environments as $environment) {
            $pullRequestsOnEnvironment = $this->repository->getPullRequests(PullRequest::STATE_OPEN, ['~validation-' . $environment->getName()]);

            if (!empty($pullRequestsOnEnvironment)) {
                $environment->setPullRequest($pullRequestsOnEnvironment[0]);
            }
        }

        return $environments;
    }
}
