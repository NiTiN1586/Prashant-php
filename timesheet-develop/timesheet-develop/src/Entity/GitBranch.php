<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Jagaad\WitcherApi\Integration\Domain\GitManagement\Branch;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 *
 * @ORM\Table(name="git_branch", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UK_git_branch_branchName_gitProject", columns={"branch_name", "git_project"})
 * }, indexes={
 *     @ORM\Index(name="IDX_git_branch_gitProject", columns={"git_project"}),
 *     @ORM\Index(name="IDX_git_branch_task", columns={"task"})
 * })
 *
 * @UniqueEntity(fields={"branchName", "project"}, message="Git Branch with such name already exists for specified project")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @ORM\HasLifecycleCallbacks
 */
class GitBranch
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="branch_name", type="string", length=100)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max=100,
     *      maxMessage="Branch name cannot be longer than {{ limit }} characters",
     * )
     */
    private string $branchName;

    /**
     * @ORM\Column(name="web_url", type="string", length=200)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max=200,
     *      maxMessage="Web Url cannot be longer than {{ limit }} characters",
     * )
     */
    private string $webUrl;

    /**
     * @ORM\Column(name="merged", type="boolean",  options={"default": false})
     */
    private bool $isMerged = false;

    /**
     * @ORM\ManyToOne(targetEntity=GitProject::class, inversedBy="gitBranches")
     * @ORM\JoinColumn(name="git_project", referencedColumnName="id")
     */
    private GitProject $project;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class, inversedBy="gitBranches")
     * @ORM\JoinColumn(name="task", referencedColumnName="id")
     */
    private ?Task $task;

    public static function createFromDTOs(Branch $branch, GitProject $gitProject, ?Task $task): self
    {
        $gitBranch = new self();
        $gitBranch->setProject($gitProject);
        $gitBranch->setBranchName($branch->getName());
        $gitBranch->setIsMerged($branch->isMerged());
        $gitBranch->setWebUrl($branch->getWebUrl());
        $gitBranch->setTask($task);

        return $gitBranch;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBranchName(): string
    {
        return $this->branchName;
    }

    public function setBranchName(string $branchName): self
    {
        $this->branchName = $branchName;

        return $this;
    }

    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    public function setWebUrl(string $webUrl): self
    {
        $this->webUrl = $webUrl;

        return $this;
    }

    public function isMerged(): bool
    {
        return $this->isMerged;
    }

    public function setIsMerged(bool $isMerged): self
    {
        $this->isMerged = $isMerged;

        return $this;
    }

    public function getProject(): GitProject
    {
        return $this->project;
    }

    public function setProject(GitProject $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function undelete(): self
    {
        $this->deletedAt = null;

        return $this;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }
}
