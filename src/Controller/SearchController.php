<?php
/**
* Контроллер для поиска по БД
*
*/
namespace App\Controller;

use App\Entity\Client;
use App\Entity\Catalog;
use App\Entity\Device;

use Symfony\Component\Form\Extension\Core\Type\SearchType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

/**
* @Route("/lk/")
*/
class SearchController extends AbstractController
{

    /**
    * Поиск по основным объектам
    * @Route("search/",name="search.main", requirements={"searchQuery":"([\S\s]+)"}, methods={"GET","POST"})
    */
    public function index(Request $request)
    {
        $form=$this->createForm(SearchType::class,[]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $searchPanel=$form->getData();
            $query='%'.str_replace(' ','%',$searchPanel).'%';
            $clients=$this->getDoctrine()->getRepository(Client::class)->getClient($query);
            $catalogs=$this->getDoctrine()->getRepository(Catalog::class)->getCatalogByName($query);
            $devices=$this->getDoctrine()->getRepository(Device::class)->getDevicesByName($query);
        }
        else{
            $searchPanel='';
            $catalogs=[];
            $devices=[];
            $clients=[];
        }
        return $this->render('lk/search/main.html.twig',[
            'searchQuery'=>$searchPanel,
            'clients'=>$clients,
            'catalogs'=>$catalogs,
            'devices'=>$devices,
            'form'=>$form->createView(),
        ]);
    }

    /**
    * Формирование поисковой формы и возврат в twig->base.html.twig->render()
    * @Route("search/panel",name="search.show")
    */
    public function show()
    {

            $form=$this->createForm(SearchType::class,[],
                        array(
                            'action' => $this->generateUrl('search.main')
                        )
                  );
            return $this->render('lk/search/panel.html.twig',[
                'form'=>$form->createView()
            ]);

    }


}
