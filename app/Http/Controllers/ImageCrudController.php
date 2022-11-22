<?php

namespace App\Http\Controllers;

use App\Models\ImageCrud;
use Illuminate\Http\Request;
use App\Http\Resources\ImageCrudResource;
use Illuminate\Support\Facades\Storage;


class ImageCrudController extends Controller
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
    public function create(Request $request)
    {
        $image= new ImageCrud();
        $request->validate([
            'title'=>'required',
            'image'=>'required|max:1024'
        ]);
        $filename ="";
        if($request->hasFile('image')){
           $filename = $request->file('image')->store('/', 'fotos');

        }else{
            $filename = null;
        }

        $image->title = $request->title;
        $image->image = $filename;
        $result = $image->save();
        if($result){
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageCrud  $imageCrud
     * @return \Illuminate\Http\Response
     */
    public function show(ImageCrud $imageCrud)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImageCrud  $imageCrud
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = ImageCrud::findOrFail($id);
        return response()->json($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImageCrud  $imageCrud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image = ImageCrud::findOrFail($id);
        $filename="";

        if($request->hasFile('new_image')){
            $request->validate([
                'title'=>'required',
                'new_image'=>'required|max:1024'
            ]);
            if(Storage::disk('fotos')->exists($image->image)){
                Storage::disk('fotos')->delete($image->image);
            }

            $filename = $request->file('new_image')->store('/', 'fotos');
         }else{
            $request->validate([
                'title'=>'required',
            ]);
            $filename=$request->image;
         }

         $image->title = $request->title;
         $image->image = $filename;
         $result = $image->save();
         if($result){
             return response()->json(['success'=>true]);
         }else{
             return response()->json(['success'=>false]);
         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageCrud  $imageCrud
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageCrud $imageCrud)
    {
        //
    }

    public function get()
    {
        $images = ImageCrud::orderBy('id','DESC')->get();
        return response()->json($images);

    }

    public function delete($id)
    {
        $image = ImageCrud::findOrFail($id);
        Storage::disk('fotos')->delete($image->image);
        ImageCrud::where('id', $id)->delete();
        return response()->json('Eliminado exitosamente');

    }


}
