<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Room;

class RoomController extends Controller
{
	public function index()
	{
		return view('dashboards.admins.room');
	}

	//ADD NEW ROOM
	public function addRoom(Request $request)
	{
		$validator = \Validator::make($request->all(), [
			'RoomName' => 'required',
			'RoomNumber' => 'required',
			'RoomAmount' => 'required',
			'Image_room' => 'required|image|mimes:jpeg,png,jpg,|max:2048',
		]);

		if (!$validator->passes()) {
			return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
		} else {

			$Image_room = $request->file('Image_room');
			$new_name = rand() . '.' . $Image_room->getClientOriginalExtension();
			$Image_room->move(public_path('img/Image_Room'), $Image_room->getClientOriginalName());
			$imageFileName = $Image_room->getClientOriginalName();

			$room = new Room();
			$room->RoomName = $request->RoomName;
			$room->RoomNumber = $request->RoomNumber;
			$room->RoomAmount = $request->RoomAmount;
			$room->Image_room = $imageFileName;
			$room->RoomStatus = 0;

			$query = $room->save();

			if (!$query) {
				return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
			} else {
				return response()->json(['code' => 1, 'msg' => 'เพิ่มห้องประชุมเรียบร้อย']);
			}
		}
	}

	// GET ALL Room
	public function getRoomList(Request $request)
	{
		$rooms = Room::all();
		return DataTables::of($rooms)
			->addIndexColumn()
			->addColumn('actions', function ($row) {
				return '
                                        <button class="btn btn-sm btn-info" data-id="' . $row['RoomID'] . '" id="infoRoomBtn">
                                        <i class="fas fa-info-circle"></i></button>
                                        <button class="btn btn-sm btn-primary" data-id="' . $row['RoomID'] . '" id="editRoomBtn">
                                        <i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" data-id="' . $row['RoomID'] . '" id="deleteRoomBtn">
                                        <i class="fas fa-trash-alt"></i></button>
                                        ';
			})
			->rawColumns(['actions'])
			->make(true);
	}

	//GET ROOM DETAILS
	public function getRoomDetails(Request $request)
	{
		$room_id = $request->room_id;
		$roomDetails = Room::find($room_id);
		return response()->json(['details' => $roomDetails]);
	}

	//UPDATE ROOM DETAILS
	public function updateRoomDetails(Request $request)
	{
		$room_id = $request->rid;
		$room = Room::find($room_id);
		$path = 'img/Image_Room/';

		//validate input
		$validator = \Validator::make($request->all(), [
			'RoomName' => 'required',
			'RoomNumber' => 'required',
			'RoomAmount' => 'required',
			'Image_room_update' => 'image',
		], [
			'Image_room_update.image' => 'รูปภาพเท่านั้น'

		]);

		if (!$validator->passes()) {
			return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
		} else {
			//Update Room
			if ($request->hasFile('Image_room_update')) {
				$file_path = $path . $room->Image_room;
				//DELETE oldPicture
				if ($room->Image_room != null && \Storage::disk('public_image')->exists($file_path)) {
					\Storage::disk('public_image')->delete($file_path);
				}
				//Upload new picture
				$file = $request->file('Image_room_update');
				$file_name = $file->getClientOriginalName();
				$upload = $file->storeAs($path, $file_name, 'public_image');


				if ($upload) {
					$room->update([
						'RoomName' => $request->RoomName,
						'RoomNumber' => $request->RoomNumber,
						'RoomAmount' => $request->RoomAmount,
						'Image_room' => $file_name,
					]);

					return response()->json(['code' => 1, 'msg' => 'อัพเดทห้องประชุมเรียบร้อย']);
				}
			} else {


				$room->RoomName = $request->RoomName;
				$room->RoomNumber = $request->RoomNumber;
				$room->RoomAmount = $request->RoomAmount;
				$query = $room->save();

				if ($query) {
					return response()->json(['code' => 1, 'msg' => 'อัพเดทห้องประชุมเรียบร้อย']);
				} else {
					return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
				}
			}
		}
	}

	// DELETE ROOM RECORD
	public function deleteRoom(Request $request)
	{
		$room_id = $request->room_id;
		$query = Room::find($room_id)->delete();

		if ($query) {
			return response()->json(['code' => 1, 'msg' => 'ลบห้องประชุมเรียบร้อย']);
		} else {
			return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
		}
	}

	public function getAllRooms() {
		$rooms = Room::pluck('RoomName')->toArray();
		
		return view('welcome',compact('rooms'));
	}
}
