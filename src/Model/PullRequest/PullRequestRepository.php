<?php

namespace App\Model\PullRequest;

use App\Model\PullRequest;

interface PullRequestRepository
{
    public function getPullRequest(int $id): PullRequest;

    public function getPullRequests(?string $state = null, array $labels = []): array;

    public function updatePullRequestBody(PullRequest $pullRequest, string $body): PullRequest;

    public function addLabelToPullRequest(PullRequest $pullRequest, string $label): PullRequest;

    public function removeLabelFromPullRequest(PullRequest $pullRequest, string $label): PullRequest;

    public function mergePullRequest(PullRequest $pullRequest, string $mergeMethod): void;
}
