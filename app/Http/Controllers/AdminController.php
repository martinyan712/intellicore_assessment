<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;
use App\Models\DoorCode as DoorCode;


class AdminController extends Controller
{

    public $viewFolder = 'admin';
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
    public function dashboard(Request $request)
    {
        $viewPage = $this->viewFolder.'.dashboard';

        
        $request->request->add(
            ['mRequest' => ['params'=>
                [
                    'code_count'=>Code::count(),
                    'code_used'=>DoorCode::count()
                ]
            ]
        ]);
		
		$this->pageConfig($viewPage,$request,$response);
			return $response;
		
    }
}
