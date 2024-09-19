<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\PropertiesRequest;
use App\Http\Requests\PropertyUpdateRequest;
use App\Models\Images;
use App\Models\Propertis;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertiesController extends Controller
{
    //
    public function properties() {
        $properties = Propertis::get();


        return response()->json([
            "data" => $properties
       ])->setStatusCode(200);
    }

    public function propertiesByID(int $id): JsonResponse
    {
        $properties = Propertis::with("images")->find($id);
        if(!$properties){
            throw new HttpResponseException(response([
                'errors'=> [
                    "message"=> [
                        "Not found."
                    ]
                ]
            ], 404));
        }
        return response()->json([
           "data" => $properties
        ])->setStatusCode(200);
    } 

    public function addProperty(PropertiesRequest $request) {

        
        $data = $request->validated();

        if($request->hasFile('thumbnail')) {
        
            $data['thumbnail'] = url(Storage::url($request->file('thumbnail')->store('public/thumbnail')));
        }

        $data['baru'] = filter_var($request->input('baru'), FILTER_VALIDATE_BOOLEAN);

        $property = new Propertis($data);
  
        
        $property->save();
        
        if($request->hasFile('images')) {
            
            foreach($request->file('images') as $file) {
                
                $images_property = new Images([
                    "properti_id" => $property["id"],
                    "file_path" => url(Storage::url($file->store('public/images')))
                ]);
                $images_property->save();
            };
        }
        // @dd($property);



        return response()->json([
            "message" => "Oke.",
             "data"=> $property
        ])->setStatusCode(200);
    }
    
    public function deleteProperty(int $id): JsonResponse {
        $data = Propertis::with("images")->find($id);

    
        if(!$data){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        if($data->thumbnail) {
            Storage::delete(str_replace(url('/storage'), 'public/thumnail', $data->thumbnail));
        }
        if($data->images) {
            foreach($data->images as $file) {
                Storage::delete(str_replace(url('/storage'), 'public/images', $file->file_path));
            }
            $data->images()->delete();
        }


        $data->delete();
        return response()->json([
           "message"=> "Delete data successfully."
        ], 200);
    }
    public function update(PropertyUpdateRequest $request, int $id): JsonResponse {
        
        $data = $request->validated();
        $property = Propertis::with('images')->find($id);

        if(!$property){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        if($request->file('newThumbnail')) {
            if($request->thumbnail) {
                Storage::delete(str_replace(url('/storage'), 'public/thumbnail', $request->thumbnail));
            }
            $data['thumbnail'] = url(Storage::url($request->file('newThumbnail')->store('public/thumbnail')));
        }
        
        $data['baru'] = filter_var($request->input('baru'), FILTER_VALIDATE_BOOLEAN);
        if($request->hasFile('images')) {
            
            foreach($request->file('images') as $file) {
                
                $images_property = new Images([
                    "properti_id" => $property["id"],
                    "file_path" => url(Storage::url($file->store('public/images')))
                ]);
                $images_property->save();
            };
        }

        $property->fill($data);
        $property->save();


        return response()->json([
            'message' => 'Update successfully.',
            'property' => $property
        ], 200);
    }

    public function addImage(ImageRequest $request, int $id_property): JsonResponse {

        
        $data = $request->validated();

        $isExist = Propertis::find($id_property);

        if(!$isExist){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        if($request->hasFile('file_path')) {
            $data["properti_id"] = $id_property;
            $data["file_path"] = url(Storage::url($request->file('file_path')->store('public/images')));
        }
        $images_property = new Images($data);
        $images_property->save();
            // @dd($property);

        return response()->json([
            "message" => "Oke.",
            "data"=> $images_property
        ])->setStatusCode(200);


    }

    public function updateImage(ImageRequest $request, int $id): JsonResponse {
        
        $data = $request->validated();
        $image = Images::find($id);

        if(!$image){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        if($request->file('file_path')) {
            if($image->file_path) {
                Storage::delete(str_replace(url('/storage'), 'public/images', $image->file_path));
            }
            $data['file_path'] = url(Storage::url($request->file('file_path')->store('public/images')));
        }


        $image->fill($data);
        $image->save();


        return response()->json([
            'message' => 'Update successfully.',
            'property' => $image
        ], 200);
    }

    public function deleteImage($id): JsonResponse {

        $image = Images::find($id);

        if(!$image){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message" => [
                        "Not found."
                    ]
                ]
                    ],404));
        }

        if($image->file_path) {
            Storage::delete(str_replace(url('/storage'), 'public/images', $image->file_path));
        }
        
        $image->delete();
        return response()->json([
            'message' => 'Deleted successfully.',
            'image' => $image
        ], 200);
    }


    public function filter(Request $request)
    {
        // Validasi input (tidak required karena filter bisa kosong)
        // $validator = Validator::make($request->all(), [
        //     'category'   => 'nullable|array', // category bisa array
        //     'category.*' => 'in:Rumah,Apartemen,Tanah,Ruko', // elemen dalam array harus valid
        //     'condition'  => 'nullable|array', // condition bisa array
        //     'condition.*'=> 'in:0,1', // elemen dalam array harus valid
        //     'priceSort'  => 'nullable|string|in:termahal,termurah', // Ini untuk sorting berdasarkan termahal/termurah
        //     // 'minPrice'   => 'nullable|string', // Ini untuk filter harga minimum
        //     // 'maxPrice'   => 'nullable|string', // Ini untuk filter harga maksimum
        // ]);
        

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => 'Invalid parameters',
        //         'messages' => $validator->errors(),
        //     ], 422);
        // }

        // Ambil data filter dari request
        $category = $request->input('category');
        $condition = $request->input('condition');
        $priceSort = $request->input('priceSort');
        // $minPrice = $request->input('minPrice');
        // $maxPrice = $request->input('maxPrice');

        // Query awal
        $query = Propertis::query(); // Query tanpa filter default, akan mengambil semua data jika filter kosong

        // Filter berdasarkan kategori jika ada
        if (!empty($category)) {
            $query->whereIn('type', $category);
        }

        // Filter berdasarkan kondisi jika ada
        if (!empty($condition)) {
            if(!empty($category[0])) {
                $query->whereIn('baru',  filter_var($category[0], FILTER_VALIDATE_BOOLEAN));
            }
            if(!empty($category[1])) {
                $query->whereIn('baru',  filter_var($category[1], FILTER_VALIDATE_BOOLEAN));
            }


        }

        // // Filter berdasarkan harga minimum jika ada
        // if (!empty($minPrice)) {
        //     $minPrice = str_replace(',', '', $minPrice); // Hilangkan koma
        //     $query->whereRaw("CAST(REPLACE(harga, ',', '') as UNSIGNED) >= ?", [$minPrice]);
        // }

        // // Filter berdasarkan harga maksimum jika ada
        // if (!empty($maxPrice)) {
        //     $maxPrice = str_replace(',', '', $maxPrice); // Hilangkan koma
        //     $query->whereRaw("CAST(REPLACE(harga, ',', '') as UNSIGNED) <= ?", [$maxPrice]);
        // }

        // Sort berdasarkan harga jika ada
        if (!empty($priceSort)) {
            if ($priceSort === 'termahal') {
                $query->orderByRaw("CAST(REPLACE(harga, ',', '') as UNSIGNED) DESC");
            } else {
                $query->orderByRaw("CAST(REPLACE(harga, ',', '') as UNSIGNED) ASC");
            }
        }

        // Ambil hasil query
        $properties = $query->get();

        // Return response sebagai JSON
        return response()->json($properties);
    }
}


