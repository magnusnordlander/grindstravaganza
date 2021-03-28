<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Grinder;
use App\Repository\GrinderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GrinderController extends AbstractController
{
    /**
     * @var GrinderRepository
     */
    private $repo;

    public function __construct(GrinderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/", name="grinder_list")
     */
    public function list()
    {
        return $this->render('grinder/list.html.twig', [
            'grinders' => $this->repo->findAll(),
        ]);
    }

    /**
     * @param Grinder $grinder
     * @Route("/grinders/{id}", name="grinder_show")
     * @ParamConverter("grinder")
     */
    public function show(Grinder $grinder)
    {
        return $this->render('grinder/show.html.twig', [
            'grinder' => $grinder,
        ]);
    }
}
