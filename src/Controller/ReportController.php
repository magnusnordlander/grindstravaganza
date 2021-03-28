<?php
declare(strict_types=1);

namespace App\Controller;

use App\ReportParser;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReportController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/report", name="take_report")
     */
    public function takeReport(Request $request)
    {
        $parser = new ReportParser();
        $report = $parser->parseReport($request->getContent());
        $this->logger->error($request->headers->get("Authorization"));
        $this->logger->error($this->serializer->serialize($report, 'json'));
        $this->logger->error($request->getContent());
        return new Response("", Response::HTTP_OK);
    }
}
