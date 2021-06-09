<?php
use ArmoredCore\WebObjects\Data;
use ArmoredCore\WebObjects\Debug;
use ArmoredCore\WebObjects\Layout;
use ArmoredCore\WebObjects\URL;

class LayoverController extends BaseAuthController implements ResourceControllerInterface
{
    public function index($id)
    {
        $layovers = Layover::all(array('conditions' => array('idvoo = ?', $id)));
        $flight = Flight::find([$id]);
        return View::make('layover.index', ['layovers' => $layovers, 'flight' => $flight]);
    }

     public function create($id)
     {
        $aeroporto = Aeroport::all();
        \ArmoredCore\WebObjects\Debug::barDump($aeroporto);
        return View::make('layover.create', ['aeroporto' => $aeroporto]);
     }

     public function store()
     {
         $layover = new Layover(Post::getAll());

         $datapartida = strtoline(Post::get('datapartida'));
         $datachegada = strtoline(Post::get('datachegada'));

         $datapartida = date('Y-m-d H:i:s', $datapartida);
         $datachegada = date('Y-m-d H:i:s', $datachegada);

         $layover -> datapartida = $datapartida;
         $layover -> datachegada = $datachegada;

         \ArmoredCore\WebObjects\Debug::barDump($layover);


         if($layover -> is_valid())
         {
             $layover -> save();
             \Redirect::toRoute('Layover/index');
         }else{
             //redirect to form data errors
             Redirect::flashToRoute('layover/create', ['layover' => $layover]);
         }
     }
     public function show($id)
     {
        $layover = Layover::find([$id]);
        if(is_null($layover)){

            //TODO redirect to standard error page
        } else {
            return View::make('layover.show', ['layover' => $layover]);
        }

     }
}
