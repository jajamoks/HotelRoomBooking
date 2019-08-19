<?php

namespace App\Http\Controllers;

use App\HotelDetails;
use App\Http\Resources\RoomManagerCollection;
use App\RoomManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomManagerController extends Controller
{

    public function store(Request $request)
    {
        try {
            //validate payload
            $validator = $this->validateDetails($request);

            if ($validator->fails()) {
                return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
            }

            //check if room exist
            $roomM = RoomManager::where('name', $request->get('name'))->first();
            if ($roomM) {
                return response()->json(['msg' => 'Room name (' . $request->get('name') . ') already exist', 'code' => '01']);
            }

            //assign room image name
            $fileName = 'room_' . $request->get('name') . '_' . time() . '.jpg';
            $this->uploadImage($request, $fileName);

            //get hotel details
            $hotelDetails = HotelDetails::findOrFail(1)->first();

            $model = new RoomManager([
                'name' => strtoupper($request->get('name')),
                'hotel_id' => $hotelDetails->id,
                'room_type_id' => $request->get('room_type'),
                'image' => $fileName,
            ]);

            $model->save();

            if ($model) {
                return response()->json(['msg' => 'room details saved.', 'code' => '00']);
            } else {
                return response()->json(['msg' => 'room details could not be saved.', 'code' => '01']);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => 'hotel details could not be saved.', 'code' => '02']);
        }
    }

    public function index()
    {
        return new RoomManagerCollection(RoomManager::all());
    }

    public function edit($id)
    {
        $model = RoomManager::find($id);
        return response()->json($model);
    }

    public function update($id, Request $request)
    {

        //validate payload
        $validator = $this->validateDetails($request);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        //get room details
        $model = RoomManager::find($id);
        if ($model->name != $request->get('name')) {
            $roomM = RoomManager::where('name', $request->get('name'))->first();
            if ($roomM) {
                return response()->json(['msg' => 'This room name (' . $request->get('name') . ') already exist', 'code' => '01']);
            }
        }

        //get hotel details
        $hotelDetails = HotelDetails::findOrFail(1)->first();
        if ($model) {

            $model->name = strtoupper($request->get('name'));
            $model->hotel_id = $hotelDetails->id;
            $model->room_type_id = $request->get('room_type');

        } else {
            return response()->json(['msg' => 'This record does not exist.', 'code' => '01']);
        }
        $model->save();

        if ($model) {
            return response()->json(['msg' => 'room details updated.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'room details could not be updated.', 'code' => '01']);
        }
    }

    public function delete($id)
    {
        $model = RoomManager::find($id);
        $model->delete();
        return response()->json(['msg' => 'Record deleted', 'code' => '00']);
    }

    protected function validateDetails($request)
    {
        return Validator::make($request->all(), [
            // 'image' => 'mimes:jpeg,jpg,png|required|max:10000',
            'name' => 'required',
            'room_type' => 'required',
        ]);
    }

    protected function uploadImage($request, $fileName)
    {
        $path = public_path() . "/images/rooms/" . $fileName;
        $img = $request->image;
        $img = substr($img, strpos($img, ",") + 1);
        $data = base64_decode($img);
        $success = file_put_contents($path, $data);
    }
}
