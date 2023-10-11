<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code as Code;
use App\Models\DoorCode as DoorCode;

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

    public function generate(Request $request){
        if($request->generate){
            //Rand digit
            $numbers = 100;
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
}
