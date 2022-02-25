<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(){
        return view('dashboards.admins.manageuser');
    }

      //ADD NEW ACC
      public function addDepartment(Request $request){
        $validator = \Validator::make($request->all(),[
            'DName'=>'required',
        ]);
    
        if(!$validator->passes()){
             return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
           

            $department = new Department();
            $department->DepartmentName = $request->DName;
            
            $query = $department->save();
            //echo $query;
            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'เพิ่มแผนกเรียบร้อย']);
            }
        }
    }
    
        // GET ALL DEPARTMENT
        public function getDepartmentList(Request $request){
            $ds = Department::all();
            return DataTables::of($ds)
                                ->addIndexColumn()
                                ->addColumn('actions', function($row){
                                    return '
                                            <button class="btn btn-sm btn-primary" data-id="'.$row['DepartmentID'].'" id="editDBtn">
                                            <i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" data-id="'.$row['DepartmentID'].'" id="deleteDBtn">
                                            <i class="fas fa-trash-alt"></i></button>
                                            ';
                                })
                                ->rawColumns(['actions'])
                                ->make(true);
      }
    
         //GET DEPARTMENT DETAILS
         public function getDepartmentDetails(Request $request){
            $d_id = $request->d_id;
            $dDetails = Department::find($d_id);
            return response()->json(['details'=>$dDetails]);
        }
    
        //UPDATE DEPARTMENT DETAILS
        public function updateDepartmentDetails(Request $request){
            $d_id = $request->did;
        
            $validator = \Validator::make($request->all(),[
                'DName'=>'required',
            ]);
        
            if(!$validator->passes()){
                   return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
            }else{
                 
                $d = Department::find($d_id);
                $d->DepartmentName = $request->DName;
                $query = $d->save();
        
                if($query){
                    return response()->json(['code'=>1, 'msg'=>'อัพเดทแผนกเรียบร้อย']);
                }else{
                    return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
                }
            }
        }
        
        // DELETE DEPARTMENT RECORD
        public function deleteDepartment(Request $request){
            $d_id = $request->d_id;
            $query = Department::find($d_id)->delete();
        
            if($query){
                return response()->json(['code'=>1, 'msg'=>'ลบแผนกเรียบร้อย']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }    
    
    }    

