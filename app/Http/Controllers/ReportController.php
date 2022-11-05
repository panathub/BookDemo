<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use App\Models\Report;
use DB;
use DataTables;

class ReportController extends Controller
{
            // GET ALL BOOKING
            public function getReportList(Request $request){
                $sql="SELECT users.name,rooms.RoomName,department.DepartmentName,reports.* FROM reports 
                INNER JOIN users ON users.id = reports.id
                INNER JOIN rooms ON rooms.RoomID = reports.RoomID
                LEFT  JOIN department ON department.DepartmentID = users.DepartmentID
				ORDER BY ReportID DESC";  
                $datareports=DB::select($sql); 
                         return DataTables::of($datareports)
                         ->addIndexColumn()
                         ->addColumn('actions', function($row){
                            return '                  
                                    <button class="btn btn-sm btn-info" data-id="'.$row->ReportID.'" id="infoReportBtn">
                                    <i class="fas fa-info-circle"></i></button>
                                    <button class="btn btn-sm btn-danger" data-id="'.$row->ReportID.'" id="deleteReportBtn">
                                    <i class="fas fa-trash-alt"></i></button>
                                    ';
                        })
                        ->addColumn('checkbox', function($row){
                            return '<input type="checkbox" name="report_checkbox" data-id="'.$row->ReportID.'"><label></label>';
                        })
                        ->rawColumns(['actions','checkbox'])
                         ->make(true);      
                
          }
    
        //GET BOOKING DETAILS
        public function getReportDetails(Request $request){
        $report_id = $request->report_id;
    
        $sql="SELECT users.name,rooms.*,department.DepartmentName,reports.* FROM reports 
        INNER JOIN users ON users.id = reports.id
        INNER JOIN rooms ON rooms.RoomID = reports.RoomID
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE reports.ReportID ='$report_id'";
        $reportDetails=DB::select($sql)[0];
    
        return response()->json(['details'=>$reportDetails]);
    }

          // DELETE BOOKING RECORD
          public function deleteReport(Request $request){
            $report_id = $request->report_id;
            $query = Report::find($report_id)->delete();
        
            if($query){
                return response()->json(['code'=>1, 'msg'=>'ลบข้อมูลเรียบร้อย']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }

        public function deleteSelectedReports(Request $request){
            $report_ids = $request->report_ids;
            Report::whereIn('ReportID', $report_ids)->delete();
            return response()->json(['code'=>1, 'msg'=>'Reports have been delete']);
        }
}
