<?php

namespace App\Model;

class PullRequest
{
    private $number;
    private $title;
    private $headRef;
    private $baseRef;
    private $url;
    private $headSha;
    private $user;
    private $labels;
    private $reviews;

    public function __construct(array $pullRequestData)
    {
        $this->number  = $pullRequestData['number'];
        $this->title   = $pullRequestData['title'];
        $this->headRef = $pullRequestData['head']['ref'];
        $this->baseRef = $pullRequestData['base']['ref'];
        $this->url     = $pullRequestData['html_url'];
        $this->headSha = $pullRequestData['head']['sha'];
        $this->user    = $pullRequestData['user']['login'];
        $this->labels  = [];
        $this->reviews = [];

        foreach ($pullRequestData['labels'] as $label) {
            $this->labels[] = $label['name'];
        }
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHeadRef(): string
    {
        return $this->headRef;
    }

    public function getBaseRef(): string
    {
        return $this->baseRef;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHeadSha(): string
    {
        return $this->headSha;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }

    public function setReviews(array $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }
}