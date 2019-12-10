<?php

namespace App\Http\Controllers;

use App\Category;
use App\Password;

use App\User;
use Illuminate\Http\Request;
use App\CustomClasses\Token;

class UserController extends Controller
{

    public function login(Request $request)
    {    
        if ($request->email != null) 
        {
            $user = User::where('email', $request->email)->first();
            if ($user->password != null) {
                if ($user->password == $request->password)
                {
                    $data_token = new Token(['email' => $user->email]);
                    $data_token = $data_token->encode();
                    return response()->json(["token"=>$data_token], 201);
                }    
            }
        }
        return response()->json(['message' => 'No registrado'], 401);    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->create($request);
        $data_token = ["email"=>$user->email];
        
        $token = new Token($data_token);
        $token = $token->encode();

        return response()->json(["token"=> $token], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_email = $request->data->email;
        
        $user = User::where('email', $user_email)->first();

        $infoToShow = array();
        array_push($infoToShow, $user);
        
        if (isset($user)) {    
           $categories = Category::where('id_user',$user->id)->get();
           foreach ($categories as $key => $category) {
               array_push($infoToShow, ["Category" => $category]);     
               $passwords = Password::where('id_category',$category->id)->get();
               array_push($infoToShow,["Password" => $passwords]);
           }
            return response()->json(["Info user" => $infoToShow]);
        }else{
            return response()->json(["Error" => "No existe un usuario con ese mail"]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_email = $request->data->email;
        
        $user = User::where('email', $user_email)->first();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->update();
        return response()->json(['Message' => 'Usuario editado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
