<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\User;
use App\CustomClasses\Token;

class CategoryController extends Controller
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
        $category = new Category();

        $header = $request->header("Authorization");    

        $token = new Token();

        if ($header != null) 
        {
            try 
            {
                $decodedToken = $token->decode($header);
            } 
            catch (\Throwable $th) {
                return response()->json(['error' => $th], 401);

            }
            $user_email = $decodedToken->email;

            $user = User::where('email', $user_email)->first();
        }
        $category->create($request, $user);

        return response()->json(['Message' => 'Categoria creada'], 201);    
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

        if (isset($user)) {    
           $categories = Category::where('id_user',$user->id)->get();
            return response()->json([ "Categories" => $categories]);
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

        $category = Category::where('id_user', $user->id)->where('name', $request->name)->first();
        
        if (isset($category)) 
        {
            $category->name = $request->new_name;
            $category->update();
            return response()->json(['Message' => 'Categoria editado'], 200);
        }
        else
        {
            return response()->json(['Message' => 'No existe esta categoria'], 401);
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

        $category = Category::where('id_user',$user->id)->where('name',$request->name)->first();
        
        if (!isset($category)) {
             return response()->json(["Error" => "No existe la categoria"], 401);
        }else{
            $category->delete();
            return response()->json(["Success" => "Se ha borrado la categoria"], 201);
        }
    }
}
