<?php

namespace App\Http\Controllers;
use File;

use Illuminate\Http\Request;
use App\Models\Products;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['products'] = Products::get();
     	return response()->json($data);
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
        $data = $request->all();
        
        if ($request->hasFile('image'))
        {
                $file      = $request->file('image');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture   = date('His').'-'.$filename;
                //move image to public/img folder
                $file->move(base_path() . '/public/uploads', $picture);
        } 

        $result = Products::create([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $picture
        ]);

        if($result){
            return response()->json(['status' => 200]);
        }else{
            return response()->json('sorry');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Products::find($id);
        return response()->json(['status' => 200, 'product' => $products]);
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
        $data = $request->all();
        $exits_image = Products::find($id)->first()->image;

        if ($request->hasFile('image'))
        {
                //Delete old file
                $file_path = base_path().'/public/uploads/'.$exits_image;
                File::delete($file_path);
                // unlink($file_path);

                //upload new file
                $file      = $request->file('image');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture   = date('His').'-'.$filename;
                //move image to public/img folder
                $file->move(base_path() . '/public/uploads', $picture);
        }else{
            $picture = $request->image;
        }

        $result = Products::find($id)->update([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $picture
        ]);

        if($result){
            return response()->json(['status' => 200]);
        }else{
            return response()->json('sorry');
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
        $Product = Products::find($id);
        if($Product->delete()){
            return response()->json(['status' => 200]);
        }
    }
}
