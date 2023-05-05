<?php

namespace Plugin\ProductShortCode\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\ProductShortCode\Form\Type\Admin\ConfigType;
use Plugin\ProductShortCode\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Eccube\Repository\ProductRepository;

use Plugin\ProductShortCode\Entity\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfigController extends AbstractController
{
   
    private $configRepository;
    private $productRepository;
  
    public function __construct(ConfigRepository $configRepository , ProductRepository $productRepository)
    {
        $this->configRepository = $configRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product_short_code/config", name="product_short_code_admin_config")
     * @Template("@ProductShortCode/admin/index.twig")
     */
     public function index(Request $request,PaginatorInterface $paginator, $page_no = 1)
     {
         $Configs = $this->configRepository->findBy([],['id' => 'ASC']);
 
        
         $pagination = $paginator->paginate(
             $Configs,
             $page_no,
             $this->eccubeConfig->get('eccube_default_page_count')
         );

 
         return [
             'pagination' => $pagination,
         ];
     }


     /**
     * @Route("/%eccube_admin_route%/product_short_code/config/new", name="product_short_code_admin_new")
     * @Template("@ProductShortCode/admin/config.twig")
     */
    public function new(Request $request)
    {
        $Config = new Config();
        // dump($Config);
        // exit;
        
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        //公開のみ取得
        $product_datas = $this->productRepository->findBy(['Status' => 1]);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $Options = $form->get('Options')->getData();
            if(!empty($Options)){
                foreach($Options as $Option){
                    $Option->setConfig($Config);
                }
            }
            
            $this->entityManager->persist($Config);
            $this->entityManager->flush();
            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('product_short_code_admin_config');
        }

        return [
            'form' => $form->createView(),
            'product_datas' => $product_datas
        ];
    }


    /**
     * @Route("/%eccube_admin_route%/product_short_code/config/{id}/edit", name="product_short_code_admin_detail",requirements={"id" = "\d+"})
     * @Template("@ProductShortCode/admin/config.twig")
     */
    public function detail(Request $request,$id = 0)
    {
        $Config = $this->configRepository->find($id);
        
        $product_datas = $this->productRepository->findBy(['Status' => 1]);

        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();

            $Options = $form->get('Options')->getData();
            if(!empty($Options)){
                foreach($Options as $Option){
                    $Option->setConfig($Config);
                }
            }
            $this->entityManager->persist($Config);
            $this->entityManager->flush();
            $this->addSuccess('更新しました。', 'admin');

            return $this->redirectToRoute('product_short_code_admin_config');
        }

        return [
            'form' => $form->createView(),
            'product_datas' => $product_datas
        ];
    }
     

     /**
     * 削除.
     *
     * @Route("/%eccube_admin_route%/product_short_code/config/{id}/delete", name="product_short_code_admin_delete", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, $id = 0)
    {
        $Config = $this->configRepository->find($id);
        if (!$Config) {
            throw new NotFoundHttpException('データがありません');
        }
        $this->entityManager->remove($Config);
        $this->entityManager->flush();
        $this->addSuccess('削除しました。','admin');

        return $this->redirectToRoute('product_short_code_admin_config');
    }
}
