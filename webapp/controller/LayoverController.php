<?php
use ArmoredCore\WebObjects\Data;
use ArmoredCore\WebObjects\Debug;
use ArmoredCore\WebObjects\Layout;
use ArmoredCore\WebObjects\URL;

class LayoverController extends BaseAuthController
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
         \ArmoredCore\WebObjects\Debug::barDump($layover);
         die();

         if($layover -> is_valid())
         {
             $layover -> save();
             \Redirect::toRoute('Layover/index');
         }
     }
}
