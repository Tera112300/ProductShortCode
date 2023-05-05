<?php

namespace Plugin\ProductShortCode\Controller\Admin;

use Eccube\Controller\AbstractController;

use Plugin\ProductShortCode\Form\Type\Admin\OptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Plugin\ProductShortCode\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OptionController extends AbstractController
{
    private $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

     /**
     * 削除.
     *
     * @Route("/%eccube_admin_route%/product_short_code/option/{id}/delete", name="product_short_code_admin_option_delete", requirements={"id" = "\d+"})
     */
    public function ajax_delete(Request $request, $id = 0)
    {
        $Config = $this->optionRepository->find($id);
        if (!$Config) {
            throw new NotFoundHttpException('データがありません');
        }
        $this->entityManager->remove($Config);
        $this->entityManager->flush();

        return new Response('Success',Response::HTTP_OK); // 成功時のレスポンスを返す
    }
}
