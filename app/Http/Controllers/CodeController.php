<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;
use App\Models\Door as Door;
use App\Models\DoorCode as DoorCode;
use App\Models\CodeHistory as CodeHistory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CodeController extends Controller
{

    public $viewFolder = 'code';
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

    public function detail(Request $request,$id){
        $viewPage = $this->viewFolder.'.detail';

        $code = Code::where('id',$id)->firstOrFail();
        $doors = Door::doesntHave('codes')->where('status',1) ->orWhere(function ($query) use ($id){
            $query->whereRelation('codes','doors_codes.code_id',$id);
        })->get(['id','name']);
        $doors->prepend(["id"=>'',"name"=>'']);
        $codedoor =  DoorCode::where('code_id', $id)->first();
        $request->request->add(
            ['mRequest' => ['params'=>
                [
                   'code' => $code,
                   'doors'=>$doors,
                   'code_door'=>($codedoor)?$codedoor->door_id:null
                ]
            ]
        ]);
		
		$this->pageConfig($viewPage,$request,$response);
		return $response;
    }

    public function edit(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'door' => 'required|exists:doors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'message'=>$validator->errors()],422);
        }
        $code = Code::where('id',$id)->firstOrFail();
        DoorCode::where('code_id', $id)->delete();

        if($request->door != ''){
            CodeHistory::where('isActive',1)->where('code_id',$id)->update([
                'isActive'=>0,
                'expired_at'=>Carbon::now()
            ]);
            DoorCode::create([
                'door_id'=>$request->door,
                'code_id'=>$id
            ]);
            CodeHistory::create([
                'door_id'=>$request->door,
                'code_id'=>$id,
                'isActive'=>1
            ]);


        }

        $result = ['success'=>true, 'code'=>Code::where('id',$id)->first()];

        if($request->door){
            $result['door'] = Door::where('id',$request->door)->first();
        }

        return response()->json($result);

    }

    public function generate(Request $request){
        $validator = Validator::make($request->all(), [
            'number' => 'required|integer|min_digits:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false, 'message'=>$validator->errors()],422);
        }

        if($request->generate){
            //Rand digit
            $numbers = $request->number;
            if($request->has('numbers')){
                $numbers = $request->numbers;
            }
            $i=0;
            while($i < $numbers){
                $code = $this->digit(6);
                if(!$this->uniqueCode($code)){
                    continue;
                }
                if(!$this->notPalindorme($code)){  
                    continue;
                }
                if(!$this->notPalindorme($code)){  
                    continue;
                }
                if(!$this->charnorepaet($code,3)){  
                    continue;
                }
                if(!$this->nosequencelen($code,3)){  
                    continue;
                }
                if(!$this->atleastuniqchar($code,3)){  
                    continue;
                }
                Code::create([
                    'code'=>$code
                ]);
                $i++;
            }
            
            return response()->json(['success'=>true, 'code_count'=>Code::count(),'code_used'=>DoorCode::count()]);
        }else{
            abort(400);
        }


    }

    public function checkNumber(Request $request){
        $result = true;
        $code = $request->code;
        if(!$this->uniqueCode($code)){
            $result =  false;
        }
        if(!$this->notPalindorme($code)){  
            $result =  false;
        }
        if(!$this->notPalindorme($code)){  
            $result =  false;
        }
        if(!$this->charnorepaet($code,3)){  
            $result =  false;
        }
        if(!$this->nosequencelen($code,3)){  
            $result =  false;
        }
        if(!$this->atleastuniqchar($code,3)){  
            $result =  false;
        }
        return response()->json(['success'=>$result]); 

    }

    public function digit($length){
        $max = '';
        for($i=0;$i<$length;$i++){
            $max .= '9';
        }
        return str_pad(mt_rand(0, (int)$max), $length, '0', STR_PAD_LEFT);
    }

    public function uniqueCode($code){
        return Code::where('code',$code)->count() == 0;
    }

    public function notPalindorme($code){
        $code = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($code));
        return $code !== strrev($code);
    }

    public function charnorepaet($code,$times){
        $characterCounts = array_count_values(str_split($code));
        foreach ($characterCounts as $count) {
            if ($count > $times) {
                return false;
            }
        }
        return true;
    }

    public function nosequencelen($code,$times){
        $consecutiveCount = 1; 
        $lastDigit = null;
    
        for ($i = 0; $i < strlen($code); $i++) {
            $currentDigit = $code[$i];
            if (is_numeric($currentDigit)) {
                if ($lastDigit === null || $currentDigit - $lastDigit === 1) {
                    $consecutiveCount++;
                } else {
                    $consecutiveCount = 1;
                }
                if ($consecutiveCount > $times) {
                    return false; 
                }
                $lastDigit = $currentDigit; 
            } else {
                $consecutiveCount = 1;
            }
        }

        $consecutiveCount = 1; 
        $lastDigit = null;
        for ($i = 0; $i < strlen($code); $i++) {
            $currentDigit = $code[$i];
            if (is_numeric($currentDigit)) {
                if ($lastDigit === null || $currentDigit - $lastDigit === -1) {
                    $consecutiveCount++;
                } else {
                    $consecutiveCount = 1;
                }
                if ($consecutiveCount > $times) {
                    return false; 
                }
                $lastDigit = $currentDigit; 
            } else {
                $consecutiveCount = 1;
            }
        }




        return true;
    }

    public function atleastuniqchar($code, $char){
        $uniqueCharacterCount = count(array_unique(str_split($code)));
        return ($uniqueCharacterCount >= $char);
    }

    public function list(Request $request)
    {
        $limit = $request->limit | 10;
        $offset = ($request->page - 1) * $limit;


       $count =  Code::count();
       $codes = Code::with('doors')->skip($offset)->take($limit)->get();

        return response()->json(['success'=>true, 'list'=>$codes,'count'=>$count]);
    }
}
