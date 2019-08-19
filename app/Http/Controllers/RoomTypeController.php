<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomTypeCollection;
use App\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomTypeController extends Controller
{
    public function store(Request $request)
    {
        //validate room details
        $validator = $this->validateDetails($request);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        //check if room type exist
        $roomT = RoomType::where('type', $request->get('type'))->first();
        if ($roomT) {
            return response()->json(['msg' => 'This room type already exist ...' . $request->get('type'), 'code' => '01']);
        }

        $model = new RoomType([
            'type' => $request->get('type'),
        ]);

        $model->save();

        if ($model) {
            return response()->json(['msg' => 'room type saved.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'room type could not be saved.', 'code' => '01']);
        }
    }

    public function index()
    {
        return new RoomTypeCollection(RoomType::all());
    }

    public function edit($id)
    {
        $model = RoomType::find($id);
        return response()->json($model);
    }

    public function update($id, Request $request)
    {

        //validate rooom details
        $validator = $this->validateDetails($request);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        //verify room type
        $roomT = RoomType::where('type', $request->get('type'))->first();
        if ($roomT) {
            return response()->json(['msg' => 'This room type already exist ...' . $request->get('type'), 'code' => '01']);
        }

        //get room details
        $model = RoomType::find($id);

        if ($model) {
            $model->type = ucwords($request->get('type'));
        } else {
            return response()->json(['msg' => 'This record does not exist.', 'code' => '01']);
        }
        $model->save();
        return response()->json(['msg' => 'room type details updated.', 'code' => '00']);
    }

    public function delete($id)
    {
        $model = RoomType::find($id);

        $model->delete();

        return response()->json(['msg' => 'Record deleted', 'code' => '00']);
    }
    protected function validateDetails($request)
    {
        return Validator::make($request->all(), [
            'type' => 'required',
        ]);
    }
}
