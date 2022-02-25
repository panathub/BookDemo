<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Models\Accessories;

class AccessoriesController extends Controller
{
    public function index(){
        return view('dashboards.admins.accessories');
    }

       //ADD NEW ACC
   public function addAcc(Request $request){
    $validator = \Validator::make($request->all(),[
        'AccName'=>'required',
        'AccQuantity'=>'required',
        'Image_acc'=>'required|image|mimes:jpeg,png,jpg,|max:2048',
    ]);

   

    if(!$validator->passes()){
         return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
    }else{
       
        $Image_acc = $request->file('Image_acc');
        $new_name = rand() . '.' . $Image_acc->getClientOriginalExtension();
        $Image_acc->move(public_path('img/Image_Accessories'), $Image_acc->getClientOriginalName());
        $imageFileName = $Image_acc->getClientOriginalName();

        $acc = new Accessories();
        $acc->Name = $request->AccName;
        $acc->Quantity = $request->AccQuantity;
        $acc->Image_acc = $imageFileName;
        
        $query = $acc->save();
        //echo $query;
        if(!$query){
            return response()->json(['code'=>0,'msg'=>'Something went wrong']);
        }else{
            return response()->json(['code'=>1,'msg'=>'เพิ่มอุปกรณ์เรียบร้อย']);
        }
    }
}

    // GET ALL ACC
    public function getAccList(Request $request){
        $accs = Accessories::all();
        return DataTables::of($accs)
                            ->addIndexColumn()
                            ->addColumn('actions', function($row){
                                return '
                                        <button class="btn btn-sm btn-info" data-id="'.$row['AccessoriesID'].'" id="infoAccBtn">
                                        <i class="fas fa-info-circle"></i></button>
                                        <button class="btn btn-sm btn-primary" data-id="'.$row['AccessoriesID'].'" id="editAccBtn">
                                        <i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" data-id="'.$row['AccessoriesID'].'" id="deleteAccBtn">
                                        <i class="fas fa-trash-alt"></i></button>
                                        ';
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
  }

     //GET ACC DETAILS
     public function getAccDetails(Request $request){
        $acc_id = $request->acc_id ;
        $accDetails = Accessories::find($acc_id);
        return response()->json(['details'=>$accDetails]);
    }

    //UPDATE ACC DETAILS
    public function updateAccDetails(Request $request){
        $acc_id = $request->aid;
        $acc = Accessories::find($acc_id);
        $path = 'img/Image_Accessories/';
    
        $validator = \Validator::make($request->all(),[
            'AccName'=>'required',
            'AccQuantity'=>'required',
            'Image_acc_update'=>'image',
        ],[
            'Image_acc_update.image'=>'รูปภาพเท่านั้น'
        
        ]);
    
        if(!$validator->passes()){
               return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
        //Update Accessories
        if($request->hasFile('Image_acc_update')){
            $file_path = $path.$acc->Image_acc;
            //DELETE oldPicture
            if($acc->Image_acc != null && \Storage::disk('public_image')->exists($file_path)){
                \Storage::disk('public_image')->delete($file_path);
            }
            //Upload new picture
            $file = $request->file('Image_acc_update');
            $file_name = $file->getClientOriginalName();
            $upload = $file->storeAs($path, $file_name, 'public_image');
            

            if($upload){
                $acc->update([
                    'Name'=>$request->AccName,
                    'Quantity'=>$request->AccQuantity,
                    'Image_acc' => $file_name,
                ]);

                return response()->json(['code'=>1, 'msg'=>'อัพเดทอุปกรณ์เรียบร้อย']);
            }
        }else{
            
            $acc->Name = $request->AccName;
            $acc->Quantity = $request->AccQuantity;
            $query = $acc->save();
    
            if($query){
                return response()->json(['code'=>1, 'msg'=>'อัพเดทอุปกรณ์เรียบร้อย']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }
    }
}    
    // DELETE ACC RECORD
    public function deleteAcc(Request $request){
        $acc_id = $request->acc_id;
        $query = Accessories::find($acc_id)->delete();
    
        if($query){
            return response()->json(['code'=>1, 'msg'=>'ลบอุปกรณ์เรียบร้อย']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
        }
    }    

}
