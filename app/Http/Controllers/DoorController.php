<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;
use App\Models\Door as Door;
use App\Models\DoorCode as DoorCode;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\CodeHistory as CodeHistory;

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

    public function detail(Request $request,$id){
        $viewPage = $this->viewFolder.'.detail';

        $door = Door::where('id',$id)->firstOrFail();
        $codes = Code::doesntHave('doors')->orWhere(function ($query) use ($id){
            $query->whereRelation('doors','doors_codes.door_id',$id);
        })->get(['id','code']);
        $codes->prepend(["id"=>'',"code"=>'']);
        $codedoor =  DoorCode::where('door_id', $id)->first();
        $request->request->add(
            ['mRequest' => ['params'=>
                [
                   'codes' => $codes,
                   'door'=>$door,
                   'code_door'=>($codedoor)?$codedoor->code_id:null
                ]
            ]
        ]);
		
		$this->pageConfig($viewPage,$request,$response);
		return $response;
    }

    public function edit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:codes,id',
            'status' => 'required|integer|between:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'message'=>$validator->errors()],422);
        }
        
        $door = Door::where('id',$id)->firstOrFail();
        DoorCode::where('door_id', $id)->delete();
        $door->update([
            'status'=>$request->status
        ]);

        if($request->code != ''){
            CodeHistory::where('isActive',1)->where('door_id',$id)->update([
                'isActive'=>0,
                'expired_at'=>Carbon::now()
            ]);
            DoorCode::create([
                'code_id'=>$request->code,
                'door_id'=>$id
            ]);
            CodeHistory::create([
                'code_id'=>$request->code,
                'door_id'=>$id,
                'isActive'=>1
            ]);
        }

        $result = ['success'=>true, 'door'=>$door];

        if($request->code){
            $result['code'] = Code::where('id',$request->code)->first();
        }

        return response()->json($result);

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
