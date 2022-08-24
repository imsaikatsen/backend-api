<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class OutletController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlets = Outlet::orderBy('id', 'DESC')->get();
        return response()->json(['$outlets' => $outlets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'name' => 'required',
            'phone' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'required',
        );
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            return $validator->errors();
        }else{
            $image = $request->file('image');
            $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('upload/outlet_images');
            $image->move($destinationPath, $input['imagename']);


            $outlet = new Outlet();
            $outlet->name = $request->name;
            $outlet->user_id = Auth::user()->id;
            $outlet->phone = $request->phone;
            $outlet->latitude = $request->latitude;
            $outlet->longitude = $request->latitude;
            $outlet->image = $input['imagename'];
            $outlet->save();
            return response()->json(['message'=> 'Outlet Stored','product'=> $outlet]);
        }

    }

}
