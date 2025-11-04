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
    public function index(){

        $data = Teacher::select('teachers.*','doc.photo')
                    ->leftJoin('teacher_documents as doc','doc.teacher_id','teachers.id')
                    ->leftJoin('users as user','user.teacher_id','teachers.id')->get();
        return view('staff.add_teachers.index', ['data' => $data]);
    }

}