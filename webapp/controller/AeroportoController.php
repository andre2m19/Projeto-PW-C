<?php
use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\view;

class AeroportoController extends BaseController
{
    public function index()
    {
        // TODO: Implement index() method.
        $aeroporto = Aeroporto::all();
        return View::make('aeroporto.index', ['aeroporto' => $aeroporto]);
    }

    public function create()
    {
        // TODO: Implement create() method.
        return View::make('aeroporto.create');
    }

    public function show()
    {
        $aeroporto = Aeroporto::find([]);

        if (is_null($aeroporto)) {
            //TODO redirect to standard error page
        } else {
            return View::make('aeroporto.show', ['aeroporto' => $aeroporto]);
        }
    }
    public function edit(){
        $aeroporto = Aeroporto::find();

        if (is_null($aeroporto)) {
            //TODO redirect to standard error page
        } else {
            return View::make('aeroporto.edit', ['aeroporto' => $aeroporto]);
        }

    }
}
?>