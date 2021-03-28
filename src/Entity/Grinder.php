<?php
declare(strict_types=1);

namespace App\Entity;

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
     */
    public function __construct(Uuid $userId, string $mac, ?string $manufacturer = null, ?string $model = null)
    {
        $this->id = Uuid::v4();
        $this->mac = $mac;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->userId = $userId;
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


}
