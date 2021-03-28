<?php
declare(strict_types=1);

namespace App;


use App\Entity\GrindReport;
use App\Entity\MeasuringPoint;
use App\Repository\GrinderRepository;
use Symfony\Component\Uid\Uuid;

class ReportParser
{
    /**
     * @var GrinderRepository
     */
    private $grinderRepository;

    public function __construct(GrinderRepository $grinderRepository)
    {
        $this->grinderRepository = $grinderRepository;
    }

    public function parseReport(Uuid $userId, string $report): GrindReport
    {
        $lines = explode("\n", $report);
        $lines = array_filter($lines);
        preg_match("/C0FFEE:(?<version>\d+):(?<mac>[0-9a-f]+):(?<startMillis>\d+):(?<endMillis>\d+):(?<tareValue>[\d\-]+):(?<temporaryTarget>\d+):(?<grindTargetTime>\d+):(?<purgeTargetTime>\d+):(?<grindTargetWeight>\d+):(?<productivity>\d+):(?<scaleCalibration>[\d\.\-]+):(?<reactionTime>\d+):(?<type>\d+)$/", $lines[0], $matches);

        if (($matches['version'] ?? null) != 1) {
            throw new \RuntimeException("Unknown version");
        }

        if (array_slice($lines, -1, 1)[0] !== "EEFFC0") {
            throw new \RuntimeException("Missing trailer");
        }

        $points = [];

        foreach (array_slice($lines, 1, -1) as $point) {
            [$time, $raw] = explode(":", $point);
            $points[] = new MeasuringPoint((int)$time, (int)$raw);
        }

        return new GrindReport(
            $this->grinderRepository->findOrCreateByUserAndMac($userId, $matches['mac']),
            (int)$matches['version'],
            (int)$matches['startMillis'],
            (int)$matches['endMillis'],
            (int)$matches['tareValue'],
            (int)$matches['temporaryTarget'],
            (int)$matches['grindTargetTime'],
            (int)$matches['purgeTargetTime'],
            (int)$matches['grindTargetWeight'],
            (int)$matches['productivity'],
            (float)$matches['scaleCalibration'],
            (int)$matches['reactionTime'],
            (int)$matches['type'],
            $points
        );
    }
}
