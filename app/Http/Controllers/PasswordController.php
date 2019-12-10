<?php

namespace App\Http\Controllers;

use App\Password;
use Illuminate\Http\Request;
use App\Category;
use App\User;
use Mockery\Generator\StringManipulation\Pass\Pass;

class PasswordController extends Controller
{
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
        $user_email = $request->data->email;
        
        $user = User::where('email', $user_email)->first();

        $category = Category::where('id_user', $user->id)->where('name', $request->category_name)->first();
        if (isset($category)) 
        {
            $password = new Password();

            $password->create($request, $category->id);

            return response()->json(['Message' => 'Password creada'], 201);    
        }
        else {
            return response()->json(['Message' => 'No se ha podido crear'], 401);    
        }
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

        $passwordArray = array();

        if (isset($user)) {    
            $categories = Category::where('id_user',$user->id)->get();
            foreach ($categories as $key => $category) {
             
                $passwords = Password::where('id_category',$category->id)->get();
                array_push($passwordArray,$passwords);
            }
             return response()->json([ "Passwords" => $passwordArray]);
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

        $category = Category::where('id_user', $user->id)->where('name', $request->category_name)->first();
        
        if (isset($category)) 
        {
            $password = Password::where('id_category', $category->id)->where('title', $request->title)->first();

            if (isset($password)) 
            {
                $password->title = $request->new_title;
                $password->password = $request->password;
                $password->update();

                return response()->json(['Message' => 'Password editado'], 200);
            }
            else 
            {
                return response()->json(['Message' => 'No existe esta contraseña'], 401);
            }
        }
        else
        {
            return response()->json(['Message' => 'No existe esta categoría'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_email = $request->data->email;
        
        $user = User::where('email', $user_email)->first();

        $categorySearched = Category::where('id_user',$user->id)->where('name',$request->name)->first();
        
        if (!isset($categorySearched)) {
             return response()->json(["Error" => "No existe la categoria"], 401);
        }else{
            $passwordSearched = Password::where('id_category',$categorySearched->id)->where('title',$request->title)->first();
            if (!isset($passwordSearched)) {
                 return response()->json(["Error" => "No existe la contraseña"], 401);
            }else{                
                $passwordSearched->delete();
                return response()->json(["Success" => "Se ha borrado la contraseña"], 201);
            }
           
        }
    }
}
