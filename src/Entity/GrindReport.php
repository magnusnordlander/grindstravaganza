<?php
declare(strict_types=1);

namespace App\Entity;

use App\RelativeMeasuringPoint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 */
class GrindReport
{
    static $typeMap = [
        0 => 'Manual',
        1 => 'Purge',
        2 => 'Grind by time',
        3 => 'Grind by weight',
    ];

    /**
     * @ORM\Column(type="uuid", nullable=false)
     * @ORM\Id()
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable", nullable=false)
     */
    private $createdAt;

    /**
     * @var Grinder
     * @ORM\ManyToOne(targetEntity="Grinder", inversedBy="reports")
     * @ORM\JoinColumn(name="grinder_id", referencedColumnName="id")
     */
    protected $grinder;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $version;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $startMillis;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $endMillis;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $tareValue;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $temporaryTarget;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $grindTargetTime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $purgeTargetTime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $grindTargetWeight;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $productivity;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $scaleCalibration;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $reactionTime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="json", nullable=false, options={"jsonb": true})
     */
    protected $measuringData;

    /** @var MeasuringPoint[] */
    protected $measuringPoints;

    /**
     * @param MeasuringPoint[] $measuringPoints
     */
    public function __construct(
        Grinder $grinder,
        int $version,
        int $startMillis,
        int $endMillis,
        int $tareValue,
        int $temporaryTarget,
        int $grindTargetTime,
        int $purgeTargetTime,
        int $grindTargetWeight,
        int $productivity,
        float $scaleCalibration,
        int $reactionTime,
        int $type,
        array $measuringPoints
    ) {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->grinder = $grinder;
        $this->version = $version;
        $this->startMillis = $startMillis;
        $this->endMillis = $endMillis;
        $this->tareValue = $tareValue;
        $this->temporaryTarget = $temporaryTarget;
        $this->grindTargetTime = $grindTargetTime;
        $this->purgeTargetTime = $purgeTargetTime;
        $this->grindTargetWeight = $grindTargetWeight;
        $this->productivity = $productivity;
        $this->scaleCalibration = $scaleCalibration;
        $this->reactionTime = $reactionTime;
        $this->setMeasuringPoints($measuringPoints);
        $this->type = $type;
    }

    public function getGrinder(): ?Grinder
    {
        return $this->grinder;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getStartMillis(): int
    {
        return $this->startMillis;
    }

    public function getEndMillis(): int
    {
        return $this->endMillis;
    }

    public function getTareValue(): int
    {
        return $this->tareValue;
    }

    public function getTemporaryTarget(): int
    {
        return $this->temporaryTarget;
    }

    public function getGrindTargetTime(): int
    {
        return $this->grindTargetTime;
    }

    public function getPurgeTargetTime(): int
    {
        return $this->purgeTargetTime;
    }

    public function getGrindTargetWeight(): int
    {
        return $this->grindTargetWeight;
    }

    public function getProductivity(): int
    {
        return $this->productivity;
    }

    public function getScaleCalibration(): float
    {
        return $this->scaleCalibration;
    }

    public function getReactionTime(): int
    {
        return $this->reactionTime;
    }

    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param MeasuringPoint[] $measuringPoints
     */
    public function setMeasuringPoints(array $measuringPoints)
    {
        $this->measuringPoints = $measuringPoints;
        $this->measuringData = [];
        foreach ($measuringPoints as $mp) {
            $this->measuringData[$mp->getMicroTime()] = $mp->getRawData();
        }
    }

    public function getMeasuringPoints()
    {
        if ($this->measuringPoints === null) {
            $this->measuringPoints = [];
            foreach ($this->measuringData as $time => $raw) {
                $this->measuringPoints[] = new MeasuringPoint($time, $raw);
            }
        }

        return $this->measuringPoints;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDuration(): float
    {
        return ($this->endMillis - $this->startMillis) / 1000;
    }

    public function getTypeString(): string
    {
        return self::$typeMap[$this->type];
    }

    public function getGroundWeight(): ?float
    {
        if (count($this->getMeasuringPoints()) == 0) {
            return null;
        }

        return (array_slice($this->getMeasuringPoints(), -1, 1)[0]->getRawData() - $this->tareValue) / $this->scaleCalibration;
    }

    /**
     * @return RelativeMeasuringPoint[]
     */
    public function getRelativePoints(): array
    {
        $mps = $this->getMeasuringPoints();

        return array_map(function(MeasuringPoint $mp) use ($mps) {
            return new RelativeMeasuringPoint($mp, $this->tareValue, $this->scaleCalibration, $mps[0]);
        }, $mps);
    }

    public function getTargetUnit(): string
    {
        return $this->type == 3 ? 'g' : 's';
    }

    public function getTarget(): ?float
    {
        if ($this->temporaryTarget > 0) {
            return $this->temporaryTarget / 1000;
        }

        switch ($this->type) {
            case 1:
                return $this->purgeTargetTime / 1000;
            case 2:
                return $this->grindTargetTime / 1000;
            case 3:
                return $this->grindTargetWeight / 1000;
        }

        return null;
    }
}
