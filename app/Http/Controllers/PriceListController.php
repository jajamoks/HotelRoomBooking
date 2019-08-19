<?php

namespace App\Http\Controllers;

use App\Http\Resources\PriceListCollection;
use App\PriceList;
use App\RoomManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PriceListController extends Controller
{
    public function store(Request $request)
    {

        //vaidate payload
        $validator = $this->validateDetails($request);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        //retrieve room details using room_type from room manager
        $roomR = RoomManager::find($request->get('room_type'));
        if (!$roomR) {
            return response()->json(['msg' => 'This room (' . $request->get('room_type') . ') does not exist', 'code' => '01']);

        }

        //get room pricing
        $roomP = PriceList::where('room_type_id', $request->get('room_type'))->first();
        if ($roomP) {
            return response()->json(['msg' => 'Pricing for this room type (' . $roomP->room_type->type . ') already exist', 'code' => '01']);
        }

        $model = new PriceList([
            'price' => $request->get('price'),
            'room_type_id' => $request->get('room_type'),
        ]);

        $model->save();

        if ($model) {
            return response()->json(['msg' => 'Pricing saved.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'Pricing could not be saved.', 'code' => '01']);
        }
    }

    public function index()
    {
        return new PriceListCollection(PriceList::all());
    }

    public function edit($id)
    {
        $model = PriceList::find($id);
        return response()->json($model);
    }

    public function update($id, Request $request)
    {
        $model = PriceList::find($id);

        $model->update($request->all());

        if ($model) {
            return response()->json(['msg' => 'Pricing updated.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'Pricing details could not be updated.', 'code' => '01']);
        }
    }

    public function delete($id)
    {
        $model = PriceList::find($id);

        $model->delete();

        return response()->json(['msg' => 'Record deleted', 'code' => '00']);
    }

    protected function validateDetails($request)
    {
        return Validator::make($request->all(), [
            'price' => 'required|numeric|min:1|max:50000|regex:/^\d+(\.\d{1,2})?$/',
            'room_type' => 'required:numeric',
        ]);
    }
}
