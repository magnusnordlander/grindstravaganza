<?php
declare(strict_types=1);

namespace App\Entity;

class GrindReport
{
    protected $grinder;

    protected $version;

    protected $startMillis;

    protected $endMillis;

    protected $tareValue;

    protected $temporaryTarget;

    protected $grindTargetTime;

    protected $purgeTargetTime;

    protected $grindTargetWeight;

    protected $productivity;

    protected $scaleCalibration;

    protected $reactionTime;

    protected $type;

    protected $measuringPoints;

    /**
     * @param MeasuringPoint[] $measuringPoints
     */
    public function __construct(
        ?Grinder $grinder,
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
        $this->measuringPoints = $measuringPoints;
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

    public function getMeasuringPoints()
    {
        return $this->measuringPoints;
    }
}
