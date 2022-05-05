<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //

    /**
     * Update user status
     * @param integer $user_id
     * @param integer $status_code
     * @return Success Response
     * 
     */
    public function updateStatus($user_id,$status_code)
    {
       //   
       try{
           $user = User::findOrFail($user_id);
           $user->status = $status_code;
           $user->save();

           //other way to update status
          // $user = User::whereId($user_id)->update(['status' => $status_code]);

           return Redirect()->back();
       }catch(\Throwable $th)
       {
           throw $th;
       } 
    }
    
}
