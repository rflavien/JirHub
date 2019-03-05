<?php

namespace App\Model;

class PullRequest
{
    const STATE_OPEN = 'open';

    /** @var int $number */
    private $number;

    /** @var string $title */
    private $title;

    /** @var string $body */
    private $body;

    /** @var string $headRef */
    private $headRef;

    /** @var string $baseRef */
    private $baseRef;

    /** @var string $url */
    private $url;

    /** @var string $headSha */
    private $headSha;

    /** @var string $user */
    private $user;

    /** @var array $labels */
    private $labels;

    /** @var array $reviews */
    private $reviews;

    public function __construct(
        int $number,
        string $title,
        string $body,
        string $headRef,
        string $baseRef,
        string $url,
        string $headSha,
        string $user,
        array $labels,
        array $reviews
    ) {
        $this->number  = $number;
        $this->title   = $title;
        $this->body    = $body;
        $this->headRef = $headRef;
        $this->baseRef = $baseRef;
        $this->url     = $url;
        $this->headSha = $headSha;
        $this->user    = $user;
        $this->labels  = $labels;
        $this->reviews = $reviews;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
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

    public function hasLabel(string $label): bool
    {
        return \in_array($label, $this->labels, true);
    }

    public function addLabel(string $label): self
    {
        if (false === $this->hasLabel($label)) {
            $this->labels[] = $label;
        }

        return $this;
    }

    public function removeLabel(string $label): self
    {
        $key = array_search($label, $this->labels);

        if (false !== $key) {
            unset($this->labels[$key]);
        }

        return $this;
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
