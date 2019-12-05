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

        $passwords = $user->passwords();

        return response()->json(['Passwords' => $passwords], 200);
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
    public function destroy($id)
    {
        $res = Password::destroy($id);
        if ($res) {
            return response()->json([
                'msg' => 'Usuario borrado'
            ]);
        } else {
            return response()->json([
                'msg' => 'No se ha podido borrar el usuario'
            ]);
        }       
    }
}
