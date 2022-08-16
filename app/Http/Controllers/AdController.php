<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\AdResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Nullable;

class AdController extends Controller
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
        if (Auth::user()) {
            $ads = Ad::get();
            return AdResource::collection($ads, 'All Ads retrieved successfully.');
        }

        // return $this->sendResponse(AdResource::collection($ads), 'All Ads retrieved successfully.');
    }

    protected function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'type'         => 'required', // DEFAULT or SOCIAL values
            'place_id'     => 'required',
            'plat_form'    => 'required', // DEFAULT or SOCIAL values
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $ad = Ad::create([
            'type'       => $request->type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'place_id'   => $request->place_id,
            'plat_form'  => $request->plat_form,
            'user_id'    => auth()->user()->id,
        ]);
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|nullable',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'data' => 'Image Not Valid']);
        }
        if ($request->hasFile('image')) {


            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/ads/' . $ad->id), $filename);
            $ad->image = '/uploads/ads/' . $ad->id . '/' . $filename;
            $ad->save();
        }
        //Video
        $validator = Validator::make(
            $request->all(),
            [
                'video' => 'mimes:mp4,mov,ogg,qt | max:20000|Nullable',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'data' => 'Video Not Valid']);
        }
        if ($request->hasFile('video')) {


            $video = $request->file('video');
            $filename = time() . '.' . $video->getClientOriginalExtension();
            $request->video->move(public_path('uploads/ads/' . $ad->id), $filename);
            $ad->video = '/uploads/ads/' . $ad->id . '/' . $filename;
            $ad->save();
        }

        return $this->sendResponse(new AdResource($ad), 'Ad created successfully.');
    } //End of store function

    protected function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'type'         => 'required', // DEFAULT or SOCIAL values
            'place_id'     => 'required',
            'plat_form'    => 'required', // DEFAULT or SOCIAL values
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $ad = Ad::findOrFail($id);
        $validator = Validator::make($request->all(), [

            'type'         => 'required', // DEFAULT or SOCIAL values
            'place_id'     => 'required',
            'plat_form'    => 'required', // DEFAULT or SOCIAL values
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($ad) {

            $ad->update([

                'type'       => $request->type,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'place_id'   => $request->place_id,
                'plat_form'  => $request->plat_form,
                'user_id'    => auth()->user()->id,
            ]);
            $validator = Validator::make(
                $request->all(),
                [
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|nullable',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'data' => 'Image Not Valid']);
            }
            if ($request->hasFile('image')) {


                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $request->image->move(public_path('uploads/ads/' . $ad->id), $filename);
                $ad->image = '/uploads/ads/' . $ad->id . '/' . $filename;
                $ad->save();
            }
            //Video
            $validator = Validator::make(
                $request->all(),
                [
                    'video' => 'mimes:mp4,mov,ogg,qt | max:20000|Nullable',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'data' => 'Video Not Valid']);
            }
            if ($request->hasFile('video')) {


                $video = $request->file('video');
                $filename = time() . '.' . $video->getClientOriginalExtension();
                $request->video->move(public_path('uploads/ads/' . $ad->id), $filename);
                $ad->video = '/uploads/ads/' . $ad->id . '/' . $filename;
                $ad->save();
            }

            return $this->sendResponse(new AdResource($ad), 'Ad created successfully.');
        } else {

            return response()->json(['status' => 'error', 'data' => 'Ad Not Found']);
        }
    } //End of store function

    protected function destroy($id)
    {
        $ad = Ad::find($id);
        if ($ad) {

            $ad->delete();
            return $this->sendResponse('', 'Ad deleted successfully.');
        } else {

            return response()->json(['status' => 'error', 'data' => 'Ad Not Found']);
        }
    } //End of destroy function


}
