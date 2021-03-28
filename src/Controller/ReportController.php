<?php
declare(strict_types=1);

namespace App\Controller;

use App\ReportParser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

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
    /**
     * @var ReportParser
     */
    private $parser;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ReportParser $parser, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->parser = $parser;
        $this->em = $em;
    }

    /**
     * @Route("/report", name="take_report")
     */
    public function takeReport(Request $request)
    {
        $report = $this->parser->parseReport(Uuid::v5(Uuid::fromString("e9cc7700-9567-4195-ab1e-c44aff846e16"), $request->getUser()), $request->getContent());

        $this->em->persist($report);
        $this->em->flush();

        return new Response("", Response::HTTP_CREATED);
    }
}
