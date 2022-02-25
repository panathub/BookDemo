<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Models\Department;
use App\Models\User;


class ManageUserController extends Controller
{
    public function addUser(Request $request){
        $validator = \Validator::make($request->all(),[
            'Name'=>'required',
            'email'=>'required|email|max:100',
            'password'=>'required',
            //'picture'=>'required|image|mimes:jpeg,png,jpg,|max:2048',
            'DepartmentID'=>'required',
            'roleID'=>'required',
        ]);
    
        if(!$validator->passes()){
             return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
           
           /* $picture = $request->file('picture');
            $new_name = rand() . '.' . $picture->getClientOriginalExtension();
            $picture->move(public_path('img/Image_User'), $picture->getClientOriginalName());
            $imageFileName = $picture->getClientOriginalName(); */

            $user = new User();
            $user->Name = $request->Name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
           // $user->picture = $imageFileName;
            $user->roleID = $request->roleID;
            $user->DepartmentID = $request->DepartmentID;
            
            $query = $user->save();
            //echo $query;
            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'เพิ่มผู้ใช้เรียบร้อย']);
            }
        }
    }

        // GET ALL USER
        public function getUserList(Request $request){
            $users = DB::table('users')
                     ->join('department', 'users.DepartmentID', '=', 'department.DepartmentID')
                     ->join('role', 'users.roleID', '=', 'role.roleID')
                     ->select('users.*','department.DepartmentName','role.roleName')
                     ->get();   
                     return DataTables::of($users)
                     ->addIndexColumn()
                     ->addColumn('actions', function($row){
                         return '
                                 <button class="btn btn-sm btn-primary" data-id="'.$row->id.'" id="editUserBtn">
                                 <i class="fas fa-edit"></i></button>
                                 <button class="btn btn-sm btn-danger" data-id="'.$row->id.'" id="deleteUserBtn">
                                 <i class="fas fa-trash-alt"></i></button>
                                 ';
                     })
                     ->rawColumns(['actions'])
                     ->make(true);    
            
      }

      //GET USER DETAILS
   public function getUserDetails(Request $request){
    $user_id = $request->user_id;
    $userDetails = DB::table('users')
                ->join('department', 'users.DepartmentID', '=', 'department.DepartmentID')
                ->join('role', 'users.roleID', '=', 'role.roleID')
                ->select('users.*','department.DepartmentName','role.roleName')
                ->find($user_id);
    return response()->json(['details'=>$userDetails]);
}


    //UPDATE USER DETAILS
    public function updateUserDetails(Request $request){
    $user_id = $request->uid;

    $validator = \Validator::make($request->all(),[
        'Name'=>'required',
        'email'=>'required|email|max:100',
        'password'=>'required',
        'DepartmentID'=>'required',
        'roleID'=>'required',    
    ]);

    if(!$validator->passes()){
           return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
    }else{
         
        $user = User::find($user_id);
        $user->Name = $request->Name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->roleID = $request->roleID;
        $user->DepartmentID = $request->DepartmentID;
        $query = $user->save();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'อัพเดทผู้ใช้งานเรียบร้อย']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
        }
    }
}

// DELETE USER RECORD
public function deleteUser(Request $request){
    $user_id = $request->u_id;
    $query = User::find($user_id)->delete();

    if($query){
        return response()->json(['code'=>1, 'msg'=>'ลบผู้ใช้เรียบร้อย']);
    }else{
        return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
    }
}
}
