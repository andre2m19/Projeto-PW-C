<?php
use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Debug;
use ArmoredCore\WebObjects\Session;

class FinanceController extends BaseController
    implements ResourceControllerInterface{

    public function index()
    {
        $Finance = Finance::all();
        return View::make('finance.index',['finance'=>$Finance]);
    }

    public function create()
    {
        return View::make('finance.create');
    }

    public function store()
    {
        $finance = new Finance(Post::getAll());

        if($finance->is_valid()){
            $finance->save();
            Redirect::toRoute('finance/index');
        } else {

            Redirect::flashToRoute('finance/create', ['finance' => $finance]);
        }


    }

    public function show($id)
    {
        $Finance = Finance::find([$id]);

        if (is_null($Finance)) {
            //TODO redirect to standard error page
        } else {
            return View::make('finance.show', ['finance' => $Finance]);
        }
    }

    public function edit($id)
    {
        $finance = Finance::find([$id]);

        if (is_null($finance)) {
            //TODO redirect to standard error page
        } else {
            return View::make('finance.edit', ['finance' => $finance]);
        }
    }

    public function update($id)
    {
        $Finance = Finance::find([$id]);
        $Finance->update_attributes(Post::getAll());

        if($Finance->is_valid()){
            $Finance->save();
            Redirect::toRoute('finance/index');
        } else {
            //redirect to form with data and errors
            Redirect::flashToRoute('finance/edit', ['finance' => $Finance]);
        }
    }

    public function destroy($id)
    {
        $Finance = Finance::find([$id]);
        $Finance->delete();
        Redirect::toRoute('finance/index');
    }
}
?>