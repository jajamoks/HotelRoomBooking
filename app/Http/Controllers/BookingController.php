<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Resources\BookingCollection;
use App\PriceList;
use App\RoomManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function store(Request $request)
    {

        //format date
        $now = Carbon::now()->format('Y-m-d');
        $now = Carbon::createFromFormat('Y-m-d', $now);
        $start = Carbon::createFromFormat('Y-m-d', $request->get('date_start'));

        //verify booking date
        if (!$start->gte($now)) {
            return response()->json(['msg' => 'Start date cannot be ( ' . $start . ')', 'code' => '01']);
        }

        //validate request payload
        $validator = $this->validateDetails($request);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        //check if room is available or booked
        $roomB = Booking::where([
            ['room_id', '=', $request->get('room_id')],
            ['date_end', '>=', $request->get('date_start')],
        ])->first();
        if ($roomB) {
            return response()->json(['msg' => 'Choose another date, this room (' . $roomB->room->name . ') is booked between ' . $roomB->date_start . ' - ' . $roomB->date_end, 'code' => '01']);
        }

        //verify  room id exist
        $roomR = RoomManager::where('id', $request->get('room_id'))->first();
        if (!$roomR) {
            return response()->json(['msg' => 'Room (' . $request->get('room_id') . ') does not exist', 'code' => '01']);
        }

        //verify room price
        $roomP = PriceList::where('room_type_id', $roomR->room_type_id)->first();
        if (!$roomP) {
            return response()->json(['msg' => 'Pricing for the room (' . $roomR->name . ') does not exist', 'code' => '01']);
        }

        //calculate total nights from date range
        $totalNight = $this->getNights($request->get('date_start'), $request->get('date_end'));

        $model = new Booking([
            'date_start' => $request->get('date_start'),
            'date_end' => $request->get('date_end'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'total_nights' => $totalNight,
            'total_price' => $this->calRoomPrice($totalNight, $roomP->price),
            'user_id' => $request->get('user_id'),
            'room_id' => $roomR->id,
        ]);

        $model->save();

        if ($model) {
            return response()->json(['msg' => 'booking saved.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'booking could not be saved.', 'code' => '01']);
        }

    }

    /**
     *
     */

    public function index()
    {
        return new BookingCollection(Booking::all());
    }

    public function edit($id)
    {
        $model = Booking::find($id);
        return response()->json($model);
    }

    public function findByDate(Request $request)
    {
        //validate payload
        $validator = Validator::make($request->all(), [
            'date_start' => 'required|date|date_format:Y-m-d|before:date_end',
            'date_end' => 'required|date|date_format:Y-m-d|after:date_start',
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

        if ($request->get('room_id')) {
            $model = Booking::where([
                ['date_start', '>=', $request->get('date_start')],
                ['date_start', '<=', $request->get('date_end')],
                ['room_id', '=', $request->get('room_id')],
            ])->get();
        } else {
            $model = Booking::where([
                ['date_start', '>=', $request->get('date_start')],
                ['date_end', '<=', $request->get('date_end')],
            ])->get();
        }
        return response()->json(['msg' => 'successful', 'code' => '00', 'data' => $model]);
    }

    public function update($id, Request $request)
    {
        // validate payload
        $validator = $this->validateDetails($request);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->messages()->first(), 'code' => '01']);
        }

         //verify if room exist
        $roomR = RoomManager::where('id', $request->get('room_id'))->first();
        if (!$roomR) {
            return response()->json(['msg' => 'Room (' . $request->get('room_id') . ') does not exist', 'code' => '01']);
        }

        //verify room pricing
        $roomP = PriceList::where('room_type_id', $roomR->room_type_id)->first();
        if (!$roomP) {
            return response()->json(['msg' => 'Pricing for the room (' . $roomR->name . ') does not exist', 'code' => '01']);
        }

        $model = Booking::find($id);

        //format date
        $oldStart = Carbon::createFromFormat('Y-m-d', $model->date_start);
        $newtart = Carbon::createFromFormat('Y-m-d', $request->get('date_start'));

        $oldEnd = Carbon::createFromFormat('Y-m-d', $model->date_end);
        $newEnd = Carbon::createFromFormat('Y-m-d', $request->get('date_end'));

        //check if booking date is being edited
        if ($oldStart != $newtart || $oldEnd != $newEnd) {
            $roomB = Booking::where([
                ['date_start', '>=', $request->get('date_start')],
                ['date_start', '<=', $request->get('date_end')],
                ['room_id', '=', $request->get('room_id')],
            ])->get();
            if ($roomB) {
                return response()->json(['msg' => 'Choose another date, this room (' . $roomB[0]->room->name . ') is booked', 'code' => '01']);

            }
        }

        //calculate total night
        $totalNight = $this->getNights($request->get('date_start'), $request->get('date_end'));
        if ($model) {
            $model->date_start = $request->get('date_start');
            $model->date_end = $request->get('date_end');
            $model->name = $request->get('name');
            $model->email = $request->get('email');
            $model->phone_number = $request->get('phone_number');
            $model->total_nights = $totalNight;
            $model->total_price = $this->calRoomPrice($totalNight, $roomP->price);
            $model->user_id = $request->get('user_id');
            $model->room_id = $roomR->id;

        } else {
            return response()->json(['msg' => 'This record does not exist.', 'code' => '01']);
        }
        $model->save();

        if ($model) {
            return response()->json(['msg' => 'booking details updated.', 'code' => '00']);
        } else {
            return response()->json(['msg' => 'roobookingm details could not be updated.', 'code' => '01']);
        }
    }

    public function delete($id)
    {
        $model = Booking::find($id);
        $model->delete();
        return response()->json(['msg' => 'Record deleted', 'code' => '00']);
    }

    protected function validateDetails($request)
    {
        return Validator::make($request->all(), [
            'room_id' => 'required',
            'date_start' => 'required|date|date_format:Y-m-d|before:date_end',
            'date_end' => 'required|date|date_format:Y-m-d|after:date_start',
            'email' => 'email',
            'name' => 'required|max:25',
            'phone_number' => 'required|max:18',
        ]);
    }

    protected function calRoomPrice($days, $price)
    {
        return $days * $price;
    }

    protected function getNights($start, $end)
    {
        $end = Carbon::createFromFormat('Y-m-d', $end);
        $start = Carbon::createFromFormat('Y-m-d', $start);
        return $end->diffInDays($start);
    }
}
