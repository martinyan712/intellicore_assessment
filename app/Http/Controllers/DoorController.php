<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;


class DoorController extends Controller
{

    public $viewFolder = 'door';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list(Request $request)
    {
        $viewPage = $this->viewFolder.'.list';

        
        $request->request->add(
            ['mRequest' => ['params'=>
                [
                    'code_count'=>Code::count()
                ]
            ]
        ]);
		
		$this->pageConfig($viewPage,$request,$response);
		return $response;
		
    }
}
