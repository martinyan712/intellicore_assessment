<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function pageConfig($view,$request,&$response){
		$menuList = array(
			'mErrors'=>$request->mErrors,
			'oRequest'=>$request->oRequest,
			'mToastr'=>$request->mToastr,
		);

		if(empty($request->get('mRequest')['params'])){
				$result = $menuList;
		}else{
			$result = array_merge($menuList,$request->get('mRequest')['params']);
		}				
		
		$response =  view($view,$result);
		
		return false;
		
		
	}
}
