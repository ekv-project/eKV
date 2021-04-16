<?php

namespace App\Http\Controllers\Exam;

use App\Models\User;
use App\Models\Course;
use App\Models\Program;
use App\Models\Classroom;
use App\Models\StudyLevel;
use App\Models\CourseGrade;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\SemesterGrade;
use App\Models\SystemSetting;
use App\Models\ClassroomStudent;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use PDF;

class ExamController extends Controller
{
        /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $instituteSettings;
    public function __construct()
    {
        $this->instituteSettings = InstituteSetting::find(1);
    }
    /***************************************************************************/

    /**
     * Handling Views
     */
    public function transcript(Request $request){
        // If authenticated user is a student.
        if(Auth::user()->role == 'student'){
            // Check if student is associated with a class
            if(ClassroomStudent::where('users_username', Auth::user()->username)){
                return redirect()->route('transcript.student', [Auth::user()->username]);
            }else{
                abort(404, 'Pelajar tidak berada dalam mana-mana kelas!');
            }
        }else{
            return redirect()->route('dashboard');
        }
    }
    public function semesterView($studentID){
        if(User::where('username', $studentID)->where('role', 'student')->first()){
            // Only student itself, coordinators and admin can view
            if(Gate::allows('authUser', $studentID) || Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
                $semesterGrades = SemesterGrade::select('study_levels_code', 'semester')->where('users_username', $studentID)->get();
                return view('dashboard.exam.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Transkrip', 'semesterGrades' => $semesterGrades, 'studentID' => $studentID]);
            }else{
                abort(403, 'Anda tiada akses pada laman ini!');
            }
        }else{
            abort(404, 'Pelajar tidak dijumpai!');
        }
    }
    public function transcriptView($studentID, $studyLevel, $semester){
        if(User::where('username', $studentID)->where('role', 'student')->first()){
            // Only student itself, coordinators and admin can view
            if(Gate::allows('authUser', $studentID) || Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
                // Check if transcript exist
                if(SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first()){
                    if(ClassroomStudent::where('users_username', $studentID)->first()){
                        $studentClassroom = ClassroomStudent::where('users_username', $studentID)->first()->classroom;
                        $studentProgram = Program::where('code', $studentClassroom->programs_code)->first()->name;
                        $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                        $semesterGrade = SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first();
                        // Student Details
                        $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                        if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                            $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                        }else{
                            $studentIdentificationNumber = "N/A";
                        }
                        $studentDetails = [
                            'name' => $studentName,
                            'identificationNumber' => $studentIdentificationNumber,
                            'matrixNumber' => $studentID
                        ];
                        $courseGrades = CourseGrade::join('courses', 'course_grades.courses_code', 'courses.code')->select('course_grades.credit_hour', 'course_grades.grade_pointer', 'courses.code', 'courses.name')->where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->get();
                        return view('dashboard.exam.transcript')->with(['settings' => $this->instituteSettings, 'page' => 'Transkrip Penilaian', 'studentProgram' => $studentProgram, 'studyLevelName' => $studyLevelName, 'semester' => $semester, 'studentDetails' => $studentDetails, 'courseGrades' => $courseGrades, 'semesterGrade' => $semesterGrade]);
                    }else{
                        abort(404, 'Pelajar tidak diletakkan dalam kelas!');
                    }
                }else{
                    abort(404, 'Transkrip tidak dijumpai!');
                }
            }else{
                abort(403, 'Anda tiada akses pada laman ini!');
            }
        }else{
            abort(404, 'Pelajar tidak dijumpai!');
        }
    }
    public function transcriptAddView($studentID){
        // Only coordinator and admin can add transcript
        if(Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
            if(User::where('username', $studentID)->first()){
                if(User::where('username', $studentID)->first()->role == 'student'){
                    // Student Details
                    $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                    if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                        $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                    }else{
                        $studentIdentificationNumber = "N/A";
                    }
                    $studentDetails = [
                        'name' => $studentName,
                        'identificationNumber' => $studentIdentificationNumber,
                        'matrixNumber' => $studentID
                    ];
                    $studyLevels = StudyLevel::select('code', 'name', 'total_semester')->get();
                    $maxSemester = StudyLevel::select('total_semester')->get()->max()['total_semester'];
                    return view('dashboard.exam.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Transkrip', 'studyLevels' => $studyLevels, 'maxSemester' => $maxSemester, 'studentDetails' => $studentDetails]);
                }else{
                    abort(404, 'Pengguna bukanlah seorang pelajar!');
                }
            }else{
                abort(404, 'Pengguna tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
    public function transcriptUpdateView($studentID, $studyLevel, $semester){
        if(Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
            if(User::where('username', $studentID)->first()){
                if(User::where('username', $studentID)->first()->role == 'student'){
                    // Check if transcript is available (with SemesterGrade and CourseGrade if both existed)
                    $semesterGrade = SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first();
                    $courseGrades = CourseGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->get();
                    if($semesterGrade){
                        // Student Details
                        $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                        if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                            $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                        }else{
                            $studentIdentificationNumber = "N/A";
                        }
                        $studentDetails = [
                            'name' => $studentName,
                            'identificationNumber' => $studentIdentificationNumber,
                            'matrixNumber' => $studentID
                        ];
                        $studyLevel = StudyLevel::where('code', $studyLevel)->select('code', 'name')->first();
                        $maxSemester = StudyLevel::select('total_semester')->get()->max()['total_semester'];
                        return view('dashboard.exam.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemaskini Transkrip', 'studyLevel' => $studyLevel, 'studentID' => $studentID, 'semester' => $semester, 'semesterGrade' => $semesterGrade, 'courseGrades' => $courseGrades, 'studentDetails' => $studentDetails]);
                    }else{
                        abort(404, 'Tiada transkrip dijumpai!');
                    }
                }else{
                    abort(404, 'Pengguna bukanlah seorang pelajar!');
                }
            }else{
                abort(404, 'Pengguna tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }

    /**
     * Handling POST Requests
     */
    public function transcriptAddUpdate(Request $request, $studentID){
        // Only coordinator and admin could update. 
        if(Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
            // Check if user exist.
            if(User::where('username', $studentID)->first()){
                // Check if user is a student
                if(User::where('username', $studentID)->first()->role == 'student'){
                    $validated = $request->validate([
                        'studyLevel' => ['required'],
                        'semester' => ['required'],
                        'total_credit_gpa' => ['required', 'numeric'],
                        'total_credit_cgpa' => ['required', 'numeric'],
                        'gpa' => ['required', 'numeric'],
                        'cgpa' => ['required', 'numeric']
                    ]);
                    $studyLevel = $request->studyLevel;
                    $semester = $request->semester;
                    $creditGPA = $request->total_credit_gpa;
                    $creditCGPA = $request->total_credit_cgpa;
                    // Check if GPA and CGPA have a dot in it (meaning it's a float). If not add two '0' digits
                    if(strlen($request->gpa) > 4){
                        $pointerGPA = substr($request->gpa, 0, 4);
                    }else{
                        $pointerGPA = $request->gpa;
                    }
                    if(strlen($request->cgpa) > 4){
                        $pointerCGPA = substr($request->cgpa, 0, 4);
                    }else{
                        $pointerCGPA = $request->cgpa;
                    }
                    if(strpos($pointerGPA, ".")){
                        // Check if GPA and CGPA have 2 digits on the end, if not add a '0'
                        $gpa = explode('.', $pointerGPA);
                        if(strlen($gpa[1]) < 2){
                            $gpa = $pointerGPA . '0';
                        }else{
                            $gpa = $pointerGPA;
                        }
                    }else{
                        $gpa = $request->gpa . '.00';
                    }
                    if(strpos($pointerCGPA, ".")){
                        // Check if GPA and CGPA have 2 digits on the end, if not add a '0'
                        $cgpa = explode('.', $pointerCGPA);
                        if(strlen($cgpa[1]) < 2){
                            $cgpa = $pointerCGPA . '0';
                        }else{
                            $cgpa = $pointerCGPA;
                        }
                    }else{
                        $cgpa = $pointerCGPA . '.00';
                    }
                    // Because it's not neccessary to add multiple method for add and update. I'm just gonna use the same method for both actions.
                    // For this, student semester grade and all course grades will be deleted before inserting new ones
                    SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->delete();
                    CourseGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->delete();
                    // Add semester grade
                    $coursesCodes = $request->coursesCode;
                    $creditHours = $request->creditHour;
                    $gradePointers = $request->gradePointer;
                    $allData = array();
                    // Check if course grades is inserted, if not return an error
                    if(isset($coursesCodes)){
                        SemesterGrade::updateOrCreate(
                            ['users_username' => $studentID, 'study_levels_code' => $studyLevel, 'semester' => $semester],
                            ['total_credit_gpa' => $creditGPA, 'total_credit_cgpa' => $creditCGPA, 'gpa' => $gpa, 'cgpa' => $cgpa]
                        );
                        for($i=0; $i < count($coursesCodes); $i++){
                            // Check if course is existed
                            if(Course::where('code', $coursesCodes[$i])->first()){
                                if(strlen($gradePointers[$i]) > 4){
                                    $pointer = substr($gradePointers[$i], 0, 4);
                                }else{
                                    $pointer = $gradePointers[$i];
                                }
                                // Check if gradePointer have a dot in it (meaning it's a float). If not add two '0' digits
                                if(strpos($pointer, ".")){
                                    // Check if gradePointer have 2 digits on the end, if not add a '0'
                                    $gradePointer = explode('.', $pointer);
                                    if(strlen($gradePointer[1]) < 2){
                                        $gradePointer = $pointer . '0';
                                    }else{
                                        $gradePointer = $pointer;
                                    }
                                }else{
                                    $gradePointer = $pointer . '.00';
                                }
                                $allData[] = array('users_username' => strtolower($studentID), 'study_levels_code' => $studyLevel, 'semester' => $semester, 'courses_code' => strtolower($coursesCodes[$i]), 'credit_hour' => $creditHours[$i], 'grade_pointer' => $gradePointer);
                            }
                        }
                        // I have to make custom upsert function. Laravel upsert() doesn't
                        // seems to like 'semester' column without unique index.
                        foreach($allData as $data){
                            if(CourseGrade::where('users_username', $data['users_username'])->where('study_levels_code', $data['study_levels_code'])->where('semester', $data['semester'])->where('courses_code', $data['courses_code'])->count() < 1){
                                CourseGrade::create(
                                    $data
                                );
                            }else{
                                CourseGrade::where('users_username', $data['users_username'])
                                ->where('study_levels_code', $data['study_levels_code'])
                                ->where('semester', $data['semester'])
                                ->where('courses_code', $data['courses_code'])
                                ->update(['credit_hour' => $data['credit_hour'], 'grade_pointer' => $data['grade_pointer']]);
                            }
                        }
                        session()->flash('transcriptSuccess', 'Transkrip Berjaya Dikemas kini!');
                        return redirect()->back();
                    }else{
                        return redirect()->back()->withInput()->withErrors([
                            'noCourseInserted' => 'Tiada rekod gred kursus ditambah!'
                        ]);
                    }
                }else{
                    abort(404, 'Pengguna bukanlah seorang pelajar!');
                }
            }else{
                abort(404, 'Pengguna tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
    public function transcriptRemove(Request $request){
        // Only coordinator and admin could remove.
        if(Gate::allows('authCoordinator', $request->studentID) || Gate::allows('authAdmin')){
            // Check if user exist.
            if(User::where('username',  $request->studentID)->first()){
                // Check if user is a student
                if(User::where('username', $request->studentID)->first()->role == 'student'){ 
                    $studentID = $request->studentID;
                    $studyLevel = $request->studyLevel;
                    $semester = $request->semester;
                    // Remove SemesterGrade and all CourseGrades
                    SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->delete();
                    CourseGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->delete();
                    session()->flash('transcriptDeleteSuccess', 'Transkrip berjaya dibuang!');
                    return redirect()->back();
                }else{
                    abort(404, 'Pengguna bukanlah seorang pelajar!');
                }
            }else{
                abort(404, 'Pengguna tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
    public function transcriptDownload(Request $request, $studentID, $studyLevel, $semester){
        // Only the student, coordinator and admin could download. 
        if(Gate::allows('authUser', $studentID) || Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
            // Check if user exist.
            if(User::where('username', $studentID)->first()){
                // Check if user is a student
                if(User::where('username', $studentID)->first()->role == 'student'){
                    // Check if student is added into a classroom.
                    if(ClassroomStudent::where('users_username', $studentID)->first()){
                        error_reporting(E_ERROR);
                        // Check if transcript exist based on semester grade table.
                        if(SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first()){
                            $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                            $studentName = User::where('username', $studentID)->first()->fullname;
                            $title = ucwords($studentName) . " - Transkrip Semester {$semester}  {$studyLevelName}";
                            $studentClassroom = ClassroomStudent::where('users_username', $studentID)->first()->classroom;
                            if(Storage::disk('local')->exists('public/img/system/logo-300.png')){
                                $collegeImageUrl = 'public/img/system/logo-300.png';
                            }elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.jpg')){
                                $collegeImageUrl = 'public/img/system/logo-def-300.jpg';
                            }else{
                                $collegeImageUrl = '';  
                            }
                            $studentProgram = Program::where('code', $studentClassroom->programs_code)->first()->name;
                            $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                            $semesterGrade = SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first();
                            // Student Details
                            $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                            if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                                $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                            }else{
                                $studentIdentificationNumber = "N/A";
                            }
                            $studentDetails = [
                                'name' => $studentName,
                                'identificationNumber' => $studentIdentificationNumber,
                                'matrixNumber' => $studentID
                            ];
                            $courseGrades = CourseGrade::join('courses', 'course_grades.courses_code', 'courses.code')->select('course_grades.credit_hour', 'course_grades.grade_pointer', 'courses.code', 'courses.name')->where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->get();
                            PDF::SetCreator('eKV');
                            PDF::SetAuthor('eKV');
                            PDF::SetTitle($title);
                            PDF::AddPage();
                            PDF::SetFont('helvetica', 'B', 10);
                            $settings = $this->instituteSettings;
                            if(isset($settings)){
                                if(empty($settings['institute_name'])){
                                    $instituteName = "Kolej Vokasional Malaysia";
                                }else{
                                    $instituteName = ucwords($settings['institute_name']);
                                }
                            }else{
                                $instituteName = "Kolej Vokasional Malaysia";
                            }
                            if(Storage::disk('local')->exists('public/img/system/logo-300.png')){
                                $logo = asset('public/img/system/logo-300.png');
                            }elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.jpg')){
                                $logo = asset('public/img/system/logo-def-300.jpg');
                            }
                            // Header
                            PDF::Image($logo, 15, 10, 26, 26);
                            PDF::SetFont('helvetica', 'B', 12);
                            PDF::Multicell(0, 6, strtoupper($instituteName), 0, 'L', 0, 2, 42, 10);
                            PDF::SetFont('helvetica', '', 9);
                            PDF::Multicell(0, 10, 'ALAMAT INSTITUSI: ' . strtoupper($settings['institute_address']), 0, 'L', 0, 2, 42, 16);
                            PDF::Multicell(0, 5, 'ALAMAT E-MEL: ' . strtoupper($settings['institute_email_address']), 0, 'L', 0, 2, 42, 26);
                            PDF::Multicell(0, 5, 'NO. TELEFON PEJABAT: ' . strtoupper($settings['institute_phone_number']), 0, 'L', 0, 2, 42, 31);
                            PDF::Ln(1);
                            PDF::writeHTML("<hr>", true, false, false, false, '');
                            PDF::SetFont('helvetica', 'b', 10);
                            PDF::Multicell(0, 5, 'TRANSKRIP SEMESTER', 0, 'C', 0, 2, 10, 38);
                            PDF::Ln(1);
                            PDF::writeHTML("<hr>", true, false, false, false, '');
                            // Student Details
                            PDF::SetXY(10, 46);
                            PDF::SetFont('helvetica', '', 9);
                            PDF::MultiCell(95, 13, 'NAMA: ' . strtoupper($studentDetails['name']), 0, 'L', 0, 0, '', '', true);
                            PDF::MultiCell(95, 13, 'PERINGKAT PENGAJIAN: ' . strtoupper($studyLevelName), 0, 'L', 0, 0, '', '', true);
                            PDF::Ln();
                            PDF::MultiCell(95, 13, 'NO. K/P: ' . $studentDetails['identificationNumber'], 0, 'L', 0, 0, '', '', true);
                            PDF::MultiCell(95, 13, 'PROGRAM: ' . strtoupper($studentProgram), 0, 'L', 0, 0, '', '', true);
                            PDF::Ln();
                            PDF::MultiCell(95, 13, 'ANGKA GILIRAN: ' . strtoupper($studentDetails['matrixNumber']), 0, 'L', 0, 0, '', '', true);
                            PDF::MultiCell(95, 13, 'SEMESTER: ' . $semester, 0, 'L', 0, 0, '', '', true);
                            // Course Grade List
                            PDF::SetXY(10, 87);
                            PDF::writeHTML("<hr>", true, false, false, false, '');
                            PDF::SetXY(10, 88);
                            PDF::SetFont('helvetica', 'B', 9);
                            PDF::MultiCell(30, 5, 'KOD KURSUS', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(100, 5, 'NAMA KURSUS', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(30, 5, 'JAM KREDIT', 0, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(30, 5, 'NILAI GRED', 0, 'C', 0, 0, '', '', true);
                            PDF::SetFont('helvetica', '', 9);
                            foreach ($courseGrades as $courseGrade) { 
                                PDF::Ln();
                                PDF::MultiCell(30, 10, strtoupper($courseGrade->code), 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(100, 10, strtoupper($courseGrade->name), 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 10, $courseGrade->credit_hour, 0, 'C', 0, 0, '', '', true);
                                PDF::MultiCell(30, 10, $courseGrade->grade_pointer, 0, 'C', 0, 0, '', '', true);
                            }
                            // Testing purposes
                            // for ($i=0; $i < 15; $i++){ 
                            //     PDF::Ln();
                            //     PDF::MultiCell(30, 10, 'Test', 0, 'C', 0, 0, '', '', true);
                            //     PDF::MultiCell(100, 10, 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non corporis, voluptas enim quos est mollitia?', 0, 'C', 0, 0, '', '', true);
                            //     PDF::MultiCell(30, 10, 'Test', 0, 'C', 0, 0, '', '', true);
                            //     PDF::MultiCell(30, 10, 'Test', 0, 'C', 0, 0, '', '', true);
                            // }
                            // Semester Grade
                            PDF::SetXY(50, 245);
                            PDF::writeHTML("<hr>", true, false, false, false, '');
                            PDF::SetXY(10, 248);
                            PDF::SetFont('helvetica', 'B', 9);
                            PDF::MultiCell(90, 5, 'KOMPONEN', 1, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(50, 5, 'JUMLAH NILAI KREDIT', 1, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(50, 5, 'JUMLAH NILAI GRED', 1, 'C', 0, 0, '', '', true);
                            PDF::Ln();
                            PDF::MultiCell(90, 5, 'PNG SEMESTER SEMASA (PNGS)', 1, 'C', 0, 0, '', '', true);
                            PDF::SetFont('helvetica', '', 9);
                            PDF::MultiCell(50, 5, $semesterGrade->total_credit_gpa, 1, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(50, 5, $semesterGrade->gpa, 1, 'C', 0, 0, '', '', true);
                            PDF::Ln();
                            PDF::SetFont('helvetica', 'B', 9);
                            PDF::MultiCell(90, 5, 'PNG KUMULATIF KESELURUHAN (PNGKK)', 1, 'C', 0, 0, '', '', true);
                            PDF::SetFont('helvetica', '', 9);
                            PDF::MultiCell(50, 5, $semesterGrade->total_credit_cgpa, 1, 'C', 0, 0, '', '', true);
                            PDF::MultiCell(50, 5, $semesterGrade->cgpa, 1, 'C', 0, 0, '', '', true);
                            PDF::Ln();
                            PDF::SetXY(10, 265);
                            PDF::SetFont('helvetica', '', 8);
                            PDF::MultiCell(0, 5, 'Transkrip ini adalah janaan komputer.', 0, 'C', 0, 0, '', '', true);
                            PDF::Ln(3);
                            PDF::MultiCell(0, 5, 'Tandatangan tidak diperlukan.', 0, 'C', 0, 0, '', '', true);
                            PDF::Ln(3);
                            PDF::MultiCell(0, 5, 'Dijana menggunakan sistem eKV.', 0, 'C', 0, 0, '', '', true);
                            PDF::Output(strtoupper($studentName) . '_TRANSKRIP SEMESTER ' . $semester . '_' . strtoupper($studyLevelName), 'D');
                        }else{
                            abort(404, 'Transkrip untuk pelajar ini tidak dijumpai!');
                        }
                    }else{
                        abort(404, 'Pelajar tidak diletakkan dalam kelas!');
                    }
                }else{
                    abort(404, 'Pengguna bukanlah seorang pelajar!');
                }
            }else{
                abort(404, 'Tiada pengguna dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
}