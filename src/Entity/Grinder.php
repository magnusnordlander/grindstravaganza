<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Repository\GrinderRepository;

/**
 * @ORM\Entity(repositoryClass=GrinderRepository::class)
 */
class Grinder
{
    /**
     * @var Uuid
     * @ORM\Column(type="uuid", nullable=false)
     * @ORM\Id()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12, nullable=false)
     */
    protected $mac;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $manufacturer;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    protected $model;

    /**
     * @var Uuid
     * @ORM\Column(type="uuid", nullable=false)
     */
    private $userId;

    /**
     * @var GrindReport[]|Collection
     * @ORM\OneToMany(targetEntity="GrindReport", mappedBy="grinder")
     */
    private $reports;

    /**
     */
    public function __construct(Uuid $userId, string $mac, ?string $manufacturer = null, ?string $model = null)
    {
        $this->id = Uuid::v4();
        $this->mac = $mac;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->userId = $userId;
        $this->reports = new ArrayCollection();
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return GrindReport[]|Collection
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function getName(): string
    {
        if ($this->manufacturer && $this->model) {
            return $this->manufacturer . ' ' . $this->model;
        }

        return $this->getFormattedMacAddress();
    }

    public function getFormattedMacAddress(): string
    {
        return strtoupper(implode(':', str_split($this->mac, 2)));
    }
}
