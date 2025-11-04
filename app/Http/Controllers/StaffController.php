<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Teacher;
use App\Models\TeacherDocuments;
use App\Models\SalaryDocument;
use App\Models\BillCounter;
use App\Models\SmsSetting;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\WhatsappSetting;
use App\Models\PermissionManagement;
use App\Models\Master\MessageTemplate;
use App\Models\Master\Branch;
use App\Models\ClassType;
use App\Models\Master\TeacherSubject;
use App\Models\Master\TimePeriods;
use Helper;
use Session;
use Hash;
use Str;
use PDF;
use Redirect;
use Auth;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Spatie\DataUrl\DataUrl;

class StaffController extends Controller

{

    
public function add(Request $request){
                $BillCounter = BillCounter::where('type','Teacher')->where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->get()->first();
                if($request->isMethod('post')){
                    $request->validate([
                        'UniqueId' => 'required',
                        'first_name' => 'required',
                        'gender_id' => 'required',
                        'dob' => 'required',
                        'mobile' => 'required|unique:teachers,mobile',
                        'father_name' => 'required',
                        'qualification'  => 'required',
                        'userName'  => 'required|unique:users,userName',
                        'password' => 'required|min:4'
                    ]);
                    $photo = null;
                        if($request->file('photo')){
                        $image = $request->file('photo');
                        $path = $image->getRealPath();      
                        $photo =  time().uniqid().$image->getClientOriginalName();
                        $destinationPath = env('IMAGE_UPLOAD_PATH').'profile';
                        $image->move($destinationPath, $photo);     
                    }
                    $experience_letter = null;
                        if($request->file('experience_letter')){
                        $image = $request->file('experience_letter');
                        $path = $image->getRealPath();      
                        $experience_letter =  time().uniqid().$image->getClientOriginalName();
                        $destinationPath = env('IMAGE_UPLOAD_PATH').'teacher/teacher_'.$request->UniqueId.'/experience_letter';
                        $image->move($destinationPath, $experience_letter); 
                    }
                    $qualification_proof = null;
                        if($request->file('qualification_proof')){
                        $image = $request->file('qualification_proof');
                        $path = $image->getRealPath();      
                        $qualification_proof =  time().uniqid().$image->getClientOriginalName();
                        $destinationPath = env('IMAGE_UPLOAD_PATH').'teacher/teacher_'.$request->UniqueId.'/qualification_proof';
                        $image->move($destinationPath, $qualification_proof);     
                    }
                    $id_proof = null;
                        if($request->file('id_proof')){
                        $image = $request->file('id_proof');
                        $path = $image->getRealPath();      
                        $id_proof =  time().uniqid().$image->getClientOriginalName();
                        $destinationPath = env('IMAGE_UPLOAD_PATH').'teacher/teacher_'.$request->UniqueId.'/id_proof';
                        $image->move($destinationPath, $id_proof);     
                    }
                    $counter = !empty($BillCounter->counter) ? $BillCounter->counter : 0 ;
                    $BillCounter->counter = $counter + 1 ;
                    $BillCounter->save();
                    $teacher = new Teacher;//model name
                    $teacher->session_id = Session::get('session_id');
                    $teacher->branch_id = Session::get('branch_id');
                    $teacher->UniqueId = $request->UniqueId;
                    $teacher->userName = $request->userName;
                    $teacher->first_name = $request->first_name;
                    $teacher->last_name = $request->last_name;
                    $teacher->password = $request->password;
            		$teacher->gender_id =$request->gender_id;
            		$teacher->dob =$request->dob;
            		$teacher->joining_date =$request->joining_date;
            		$teacher->refer_name =$request->refer_name;
            		$teacher->refer_address =$request->refer_address;
            		$teacher->refer_mobile =$request->refer_mobile;
            		$teacher->father_name =$request->father_name;
            		$teacher->class_type_id=serialize($request->class_type_id);
            		$teacher->email =$request->email;
            		$teacher->mobile =$request->mobile;
            		$teacher->address =$request->address;
            		$teacher->qualification =$request->qualification;
            		$teacher->medical_leave =$request->medical_leave;
            		$teacher->casual_leave =$request->casual_leave;
            		$teacher->other_leave =$request->other_leave;
            		$teacher->aadhaar =$request->aadhaar;
            		$teacher->pan_no =$request->pan_card;
            		$teacher->bank_name =$request->bank;
            		$teacher->account_no =$request->account_no;
            		$teacher->ifsc_code =$request->ifsc_code;
            		$teacher->salary =$request->salary;
                    $teacher->save();
                    $teacher_id = $teacher->id;
                    $teacher_us = new User;//model name
                    $teacher_us->session_id = Session::get('session_id');
                    $teacher_us->branch_id = Session::get('branch_id');
                    $teacher_us->access_branch_id = Session::get('branch_id');
                    $teacher_us->teacher_id = $teacher_id;
            		$teacher_us->first_name = $request->first_name;
            		$teacher_us->last_name = $request->last_name;
            		$teacher_us->email  = $request->email;
            		$teacher_us->mobile  = $request->mobile;
            		$teacher_us->role_id  = 2;
                    $teacher_us->dob = $request->dob;
                    $teacher_us->image = $photo;
                    $teacher_us->father_name = $request->father_name;
                    $teacher_us->address = $request->address;
                    $teacher_us->gender =$request->gender;
                    $teacher_us->status  = 1;
            	    $teacher_us->userName = $request->userName;
            		$teacher_us->password = Hash::make($request->password);
            		$teacher_us->confirm_password  = $request->password;
                    $teacher_us->save();
                    $user_id = $teacher_us->id;
                      $rolePermissions = DB::table('role_permissions')
                        ->where('role_id', 2)
                        ->get();
                    
                    if ($rolePermissions->isNotEmpty()) {
                        $insertData = [];
                        foreach ($rolePermissions as $perm) {
                            $insertData[] = [
                                'user_id'        => $user_id,
                                'sidebar_id'     => $perm->sidebar_id,
                                'sidebar_name'   => $perm->sidebar_name ?? '',
                                'sub_sidebar_id' => $perm->sub_sidebar_id ?? null,
                                'add'            => $perm->add ?? 0,
                                'edit'           => $perm->edit ?? 0,
                                'view'           => $perm->view ?? 0,
                                'delete'         => $perm->delete ?? 0,
                                'status'         => $perm->status ?? 0,
                                'print'          => $perm->print ?? 0,
                                'created_at'     => now(),
                                'updated_at'     => now(),
                            ];
                        }
                    
                        DB::table('user_permission')->insert($insertData);
                    }

                    
                    $document_upl  = new TeacherDocuments;//model name
                    $document_upl->session_id = Session::get('session_id');
                    $document_upl->branch_id = Session::get('branch_id');
                    $document_upl->teacher_id = $teacher_id;
                    $document_upl->user_id = $user_id;
                    $document_upl->joining_date = $request->joining_date;
                    $document_upl->referral_name = $request->referral_name;
                    $document_upl->photo = $photo;
                    $document_upl->Id_proof_img = $id_proof;
                    $document_upl->qualification_proof_img = $qualification_proof;
                    $document_upl->experience_letter_img = $experience_letter;
                    $document_upl->save();
            	    $PermissionManagement = new PermissionManagement; //model name
                    $PermissionManagement->session_id = Session::get('session_id');
                    $PermissionManagement->branch_id = Session::get('branch_id');
                    $PermissionManagement->edit  = 1;
            		$PermissionManagement->deletes  = 1; 
            		$PermissionManagement->download  = 1;
                    $PermissionManagement->sidebar_id = "1,2,3,8,9,10,12,23";
                    // $PermissionManagement->sidebar_id = "1,2,3,8,9,10,12";
                    // $PermissionManagement->sidebar_sub_id = "4,5,6,10,11,12,16,17,18,19,20,21,22,23,105,106,107,108,109,125,39,41,65,66,67,68,69";
                    $PermissionManagement->sidebar_sub_id = "5,11,12,16,17,18,19,20,21,22,23,109,111,39,41,65,66,67,68,69";
                    $PermissionManagement->reg_user_id = $user_id;
                    $PermissionManagement->save();
                    $template =  MessageTemplate::Select('message_templates.*','message_types.slug','message_types.status as message_type_status')
                    ->leftjoin('message_types','message_types.id','message_templates.message_type_id')
                    ->where('message_types.slug','teachers')->first();
                    $branch = Branch::find(Session::get('branch_id'));
                    $setting = Setting::where('branch_id',Session::get('branch_id'))->first();
                    $arrey1 = array(
                       '{#school_name#}',
                       '{#name#}',
                       '{#userName#}',
                       '{#father_name#}',
                       '{#support_mobile#}',
                       '{#dob#}',
                       '{#password#}',
                       '{#school_name#}');
                    $arrey2 = array(
                        $setting->name,
                        $request->first_name." ".$request->last_name,
                        $request->userName,
                        $request->father_name,
                        $setting->mobile,
                        date('d-m-Y', strtotime($request->dob)),
                        $request->password,
                        $setting->name);
                       $whatsapp = str_replace($arrey1, $arrey2, $template->whatsapp_content ?? '');
                                
                                if ($setting->firebase_notification == 1) {
                                    Helper::sendNotification(
                                        $template->title ?? 'Teacher',
                                        $whatsapp,
                                        'teacher',
                                        $request->id
                                    ); 
                                }
                                 
                                if ($template->message_type_status == 1) {
                                    if ($branch->whatsapp_srvc == 1) {
                                        $mobile = $request->mobile  ?? '';
                                        if (!empty($mobile)) {
                                            Helper::MessageQueue($mobile, $whatsapp);
                                        }
                                    }
                                }
                        
                    return response()->json([ 'status' => 'success','message' => 'Teacher Added Successfully.','print_url' => url('/joining_letter_print/' . $teacher->id) 
            ]);
                     
                }
                return view('staff.add_teachers.add',['BillCounter'=>$BillCounter]);
            }
            
    public function index(){

        $data = Teacher::select('teachers.*','doc.photo')
                    ->leftJoin('teacher_documents as doc','doc.teacher_id','teachers.id')
                    ->leftJoin('users as user','user.teacher_id','teachers.id')->get();
        return view('staff.add_teachers.index', ['data' => $data]);
    }

}