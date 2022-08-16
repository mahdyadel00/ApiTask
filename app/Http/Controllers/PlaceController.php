<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\PlaceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Place;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class PlaceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if(Auth::user()){
            $place = Place::get();
            return PlaceResource::collection($place);
        }

        // return $this->sendResponse(AdResource::collection($ads), 'All Ads retrieved successfully.');
    }

    protected function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $place = Place::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return $this->sendResponse(new PlaceResource($place), 'Place created successfully.');
    }//End of store function

    protected function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $place = Place::find($id);
        $place->title = $request->title;
        $place->description = $request->description;
        $place->save();
        return $this->sendResponse(new PlaceResource($place), 'Place updated successfully.');
    }//End of update function

    protected function destroy($id){
        $place = Place::find($id);
        $place->delete();
        return $this->sendResponse(new PlaceResource($place), 'Place deleted successfully.');
    }//End of destroy function

}
