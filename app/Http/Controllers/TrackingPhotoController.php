<?php

namespace App\Http\Controllers;

use App\Models\TrackingPhoto;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TrackingPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
    
        $tracking = TrackingPhoto::latest()->first();
    
    
        return view('startup_view.trackapp',compact('tracking'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    
    
    public function latest()
    {
        return response()->json(
            TrackingPhoto::latest()->first()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{

    Log::info('========== TRACKING UPLOAD ==========');

    Log::info('Request Data', $request->except('image'));

    Log::info('Has Image', [
        'has_image' => $request->hasFile('image')
    ]);

    /**
     * Validation
     */
    $validator = Validator::make($request->all(), [

        'image' => [
            'required',
            'image',
            'max:10240'
        ],

        'android_id' => [
            'required',
            'string'
        ],

        'latitude' => [
            'required',
            'numeric'
        ],

        'longitude' => [
            'required',
            'numeric'
        ],

        'accuracy' => [
            'required',
            'numeric'
        ],

        'timestamp' => [
            'required'
        ]

    ]);

    if ($validator->fails()) {

        Log::error('Validation Failed', [
            'errors' => $validator->errors()->toArray()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);

    }

    try {

        /**
         * Upload Image
         */
        $imagePath = $request
            ->file('image')
            ->store('tracking', 'public');

        Log::info('Image Uploaded', [
            'path' => $imagePath
        ]);

        /**
         * Save Database
         */
        $tracking = TrackingPhoto::create([

            'android_id'   => $request->android_id,

            'image'     => $imagePath,

            'latitude'  => $request->latitude,

            'longitude' => $request->longitude,

            'accuracy'  => $request->accuracy,

            'timestamp' => $request->timestamp

        ]);

        Log::info('Database Insert Success', [
            'id' => $tracking->id
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Tracking uploaded successfully',

            'data' => $tracking

        ]);

    } catch (\Throwable $e) {

        Log::error('Tracking Upload Failed', [

            'message' => $e->getMessage(),

            'file' => $e->getFile(),

            'line' => $e->getLine(),

            'trace' => $e->getTraceAsString()

        ]);

        return response()->json([

            'success' => false,

            'message' => $e->getMessage()

        ], 500);

    }

}

    /**
     * Display the specified resource.
     */
     public function show(TrackingPhoto $trackingPhoto)
    {


        return response()->json([

            'success'=>true,

            'data'=>$trackingPhoto

        ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrackingPhoto $trackingPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrackingPhoto $trackingPhoto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        TrackingPhoto $trackingPhoto
    )
    {



        if(
            Storage::disk('public')
            ->exists($trackingPhoto->image)
        ){


            Storage::disk('public')
                ->delete(
                    $trackingPhoto->image
                );


        }



        $trackingPhoto->delete();



        return response()->json([


            'success'=>true,


            'message'=>
                'Deleted successfully'


        ]);



    }
}
