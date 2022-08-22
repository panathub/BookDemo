<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\User;
use App\Models\Modal;

class AdminController extends Controller
{
    function index(){

        return view('dashboards.admins.index');
       }
    
       function profile(){
           return view('dashboards.admins.profile');
       }
       

       function updateInfo(Request $request){
           
               $validator = \Validator::make($request->all(),[
                   'name'=>'required',
                   'email'=> 'required|email|unique:users,email,'.Auth::user()->id,
                   'favoritecolor'=>'required',
               ]);

               if(!$validator->passes()){
                   return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
               }else{
                    $query = User::find(Auth::user()->id)->update([
                         'name'=>$request->name,
                         'email'=>$request->email,
                         'favoriteColor'=>$request->favoritecolor,
                    ]);

                    if(!$query){
                        return response()->json(['status'=>0,'msg'=>'Something went wrong.']);
                    }else{
                        return response()->json(['status'=>1,'msg'=>'Your profile info has been update successfuly.']);
                    }
               }
       }

       function updatePicture(Request $request){
           $path = 'users/images/';
           $file = $request->file('admin_image');
           $new_name = 'UIMG_'.date('Ymd').uniqid().'.jpg';

           //Upload new image
           $upload = $file->move(public_path($path), $new_name);
           
           if( !$upload ){
               return response()->json(['status'=>0,'msg'=>'Something went wrong, upload new picture failed.']);
           }else{
               //Get Old picture
               $oldPicture = User::find(Auth::user()->id)->getAttributes()['picture'];

               if( $oldPicture != '' ){
                   if( \File::exists(public_path($path.$oldPicture))){
                       \File::delete(public_path($path.$oldPicture));
                   }
               }

               //Update DB
               $update = User::find(Auth::user()->id)->update(['picture'=>$new_name]);

               if( !$upload ){
                   return response()->json(['status'=>0,'msg'=>'Something went wrong, updating picture in db failed.']);
               }else{
                   return response()->json(['status'=>1,'msg'=>'Your profile picture has been updated successfully']);
               }
           }
       }


       function changePassword(Request $request){
           //Validate form
           $validator = \Validator::make($request->all(),[
               'oldpassword'=>[
                   'required', function($attribute, $value, $fail){
                       if( !\Hash::check($value, Auth::user()->password) ){
                           return $fail(__('The current password is incorrect'));
                       }
                   },
                   'min:8',
                   'max:30'
                ],
                'newpassword'=>'required|min:8|max:30',
                'cnewpassword'=>'required|same:newpassword'
            ],[
                'oldpassword.required'=>'Enter your current password',
                'oldpassword.min'=>'Old password must have atleast 8 characters',
                'oldpassword.max'=>'Old password must not be greater than 30 characters',
                'newpassword.required'=>'Enter new password',
                'newpassword.min'=>'New password must have atleast 8 characters',
                'newpassword.max'=>'New password must not be greater than 30 characters',
                'cnewpassword.required'=>'ReEnter your new password',
                'cnewpassword.same'=>'New password and Confirm new password must match'
            ]);

           if( !$validator->passes() ){
               return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
           }else{
                
            $update = User::find(Auth::user()->id)->update(['password'=>\Hash::make($request->newpassword)]);

            if( !$update ){
                return response()->json(['status'=>0,'msg'=>'Something went wrong, Failed to update password in db']);
            }else{
                return response()->json(['status'=>1,'msg'=>'Your password has been changed successfully']);
            }
           }
       }

        //GET MODAL DETAILS
        public function getModalDetails(Request $request){
            $m_id = $request->m_id;

			$modalDetails = Modal::where('id', $m_id)->first();
            return response()->json(['details'=>$modalDetails]);

        }

       //UPDATE MODAL DETAILS
       public function updateModalDetails(Request $request){
        $m_id = $request->mid;
        $modal = Modal::find($m_id);
        $path = 'img/Image_Room/';
    
        $validator = \Validator::make($request->all(),[
            'Image_modal_update'=>'image',
            'text'=>'required'
        ]);
    
        if(!$validator->passes()){
               return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
        //Update Room
        if($request->hasFile('Image_modal_update')){
            $file_path = $path.$modal->image;
            //DELETE oldPicture
            if($modal->image != null && \Storage::disk('public_image')->exists($file_path)){
                \Storage::disk('public_image')->delete($file_path);
            }
            //Upload new picture
            $file = $request->file('Image_modal_update');
            $file_name = $file->getClientOriginalName();
            $upload = $file->storeAs($path, $file_name, 'public_image');
            
            if($upload){
                $modal->update([
                    'text'=>$request->text,
                    'image' => $file_name,
                ]);

                return response()->json(['code'=>1, 'msg'=>'อัพเดทเรียบร้อย']);
            }
        }else{
      
        $modal->text = $request->text;
        $query = $modal->save();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'อัพเดทเรียบร้อย']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }
    }
    }        
}
