<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;
use App\Models\Door as Door;
use Illuminate\Support\Facades\Validator;


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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:doors|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'message'=>$validator->errors()],422);
        }
 


       $door =  Door::create([
            'name'=>$request->name
        ]);

        return response()->json(['success'=>true, 'door'=>$door]);
    }


    public function list(Request $request)
    {
        $limit = $request->limit | 10;
        $offset = ($request->page - 1) * $limit;


       $count =  Door::count();
       $doors = Door::with('codes')->skip($offset)->take($limit)->get();

        return response()->json(['success'=>true, 'list'=>$doors,'count'=>$count]);
    }

    /**
     * Show the application
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $viewPage = $this->viewFolder.'.index';

        
        $request->request->add(
            ['mRequest' => ['params'=>
                [
                ]
            ]
        ]);
		
		$this->pageConfig($viewPage,$request,$response);
		return $response;
		
    }
}
