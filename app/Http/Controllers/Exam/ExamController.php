<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Course;
use App\Models\CourseGrade;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\InstituteSetting;
use App\Models\Program;
use App\Models\SemesterGrade;
use App\Models\StudyLevel;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{
        /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $instituteSettings;
    protected $currentUserUsername;
    protected $apiToken;
    public function __construct()
    {
        $this->instituteSettings = InstituteSetting::find(1);
        $this->middleware(function ($request, $next) {
            $this->currentUserUsername = 'admin';
            $this->apiToken = User::where('username', $this->currentUserUsername)->select('api_token')->first();
            return $next($request);
        });
    }
    /***************************************************************************/

    /**
     * Handling Views
     */
    public function semesterView($studentID){
        if(User::where('username', $studentID)->where('role', 'student')->first()){
            // Only student itself, coordinators and admin can view
            if(Gate::allows('authUser', $studentID) || Gate::allows('authCoordinator', $studentID) || Gate::allows('authAdmin')){
                $semesterGrades = SemesterGrade::select('study_levels_code', 'semester')->where('users_username', $studentID)->get();
                return view('dashboard.exam.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Transkrip', 'semesterGrades' => $semesterGrades, 'studentID' => $studentID]);
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
                    $studentClassroom = ClassroomStudent::where('users_username', $studentID)->first()->classroom;
                    $studentProgram = Program::where('code', $studentClassroom->programs_code)->first()->name;
                    $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                    $semesterGrade = SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first();
                    // Student Details
                    $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                    if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                        $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                    }else{
                        $studentIdentificationNumber = "NaN";
                    }
                    $studentDetails = [
                        'name' => $studentName,
                        'identificationNumber' => $studentIdentificationNumber,
                        'matrixNumber' => $studentID
                    ];
                    $courseGrades = CourseGrade::join('courses', 'course_grades.courses_code', 'courses.code')->select('course_grades.credit_hour', 'course_grades.grade_pointer', 'courses.code', 'courses.name')->where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->get();
                    return view('dashboard.exam.transcript')->with(['settings' => $this->instituteSettings, 'page' => 'Transkrip Penilaian', 'studentProgram' => $studentProgram, 'studyLevelName' => $studyLevelName, 'semester' => $semester, 'studentDetails' => $studentDetails, 'courseGrades' => $courseGrades, 'semesterGrade' => $semesterGrade]);
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
                        $studentIdentificationNumber = "NaN";
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
                            $studentIdentificationNumber = "NaN";
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
                        'total_credit_gpa' => ['required'],
                        'total_credit_cgpa' => ['required'],
                        'gpa' => ['required'],
                        'cgpa' => ['required']
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
                    // Check if transcript exist.
                    if(SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first()){
                        $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                        $studentName = User::where('username', $studentID)->first()->fullname;
                        $mpdf = new \Mpdf\Mpdf([
                            'mode' => 'utf-8',
                            'format' => 'A4',
                            'orientation' => 'P',
                        ]);
                        $mpdf->simpleTables = true;
                        $mpdf->packTableData = true;
                        $mpdf->keep_table_proportions = TRUE;
                        $mpdf->shrink_tables_to_fit=1;
                        $title = ucwords($studentName) . " - Transkrip Semester {$semester}  {$studyLevelName}";
                        $mpdf->SetTitle($title);
                        $studentClassroom = ClassroomStudent::where('users_username', $studentID)->first()->classroom;
                        $studentProgram = Program::where('code', $studentClassroom->programs_code)->first()->name;
                        $studyLevelName = StudyLevel::where('code', $studyLevel)->first()->name;
                        $semesterGrade = SemesterGrade::where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->first();
                        // Student Details
                        $studentName = User::select('fullname')->where('username', $studentID)->first()->fullname;
                        if(UserProfile::select('identification_number')->where('users_username', $studentID)->first() != null){
                            $studentIdentificationNumber = UserProfile::select('identification_number')->where('users_username', $studentID)->first()->identification_number;
                        }else{
                            $studentIdentificationNumber = "NaN";
                        }
                        $studentDetails = [
                            'name' => $studentName,
                            'identificationNumber' => $studentIdentificationNumber,
                            'matrixNumber' => $studentID
                        ];
                        $courseGrades = CourseGrade::join('courses', 'course_grades.courses_code', 'courses.code')->select('course_grades.credit_hour', 'course_grades.grade_pointer', 'courses.code', 'courses.name')->where('users_username', $studentID)->where('study_levels_code', $studyLevel)->where('semester', $semester)->get();
                        $stylesheet = '
                            .header{
                                text-align: center;
                                font-family: Arial;
                                width: 100%;
                            }
                            .footer{
                                text-align: center;
                            }
                            .studentDetails{
                                position: absolute;
                                top: 8%;
                                left: 0%;
                                width: 100%;
                            }
                            .courseGrades{
                                position: absolute;
                                top: 18%;
                                left: 0%;
                                width: 100%;
                            }
                            .semesterGrade{
                                position: absolute;
                                top: 75%;
                                left: 0%;
                                width: 100%;
                            }
                            table{
                                border-spacing: 1%;
                            }
                            table th, table td{
                                font-family: Arial;
                            }
                        ';
                        $studentDetailsHTML = '
                            <div class="studentDetails">
                                <table style="width: 80%;" align="center" autosize="1" >
                                    <tbody>
                                        <tr>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 15%;">NAMA: </th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . strtoupper($studentDetails['name']) . '</td>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 25%;">PERINGKAT PENGAJIAN: </th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . strtoupper($studyLevelName) . '</td>
                                        </tr>
                                        <tr>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 15%;">NO. K/P:</th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . $studentDetails['identificationNumber'] . '</td>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 25%;">PROGRAM: </th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . strtoupper($studentProgram) . '</td>
                                        </tr>
                                        <tr>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 15%;">ANGKA GILIRAN: </th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . strtoupper($studentDetails['matrixNumber']) . '</td>
                                            <th valign="top" style="font-size: 0.9em; text-align: left; width: 25%;">SEMESTER: </th>
                                            <td valign="top" style="font-size: 0.9em; width: 35%;">' . $semester .'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        ';
                        $courseGradesTopHTML = '
                            <div class="courseGrades">
                                    <table style="width: 80%;" align="center" autosize="1">
                                        <thead>
                                            <tr>
                                                <th valign="top" style="font-size: 0.9em; width:20%; text-align: left;">KOD KURSUS</th>
                                                <th valign="top" style="font-size: 0.9em; width:40%; text-align: left;">NAMA KURSUS</th>
                                                <th valign="top" style="font-size: 0.9em; width:20%; text-align: left;">JAM KREDIT</th>
                                                <th valign="top" style="font-size: 0.9em; width:20%; text-align: left;">NILAI GRED</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        ';
                        $courseGradesBottomHTML = '
                                        </tbody>
                                </table>
                            </div>
                        ';
                        $semesterGradeHTML = '
                            <div class="semesterGrade">
                                <table style="width: 80%; border-collapse: collapse;" align="center" autosize="1">
                                    <tbody>
                                        <tr>
                                            <th style="font-size: 0.9em; text-align: center; border: 1px solid black">KOMPONEN</th>
                                            <th style="font-size: 0.9em; text-align: center; 1px solid black">JUMLAH NILAI KREDIT</th>
                                            <th style="font-size: 0.9em; text-align: center; 1px solid black">PURATA NILAI GRED</th>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 0.9em; text-align: left; 1px solid black">PNG SEMESTER SEMASA (PNGS)</th>
                                            <td style="font-size: 0.9em; text-align: center; 1px solid black">' . $semesterGrade->total_credit_gpa . '</td>
                                            <td style="font-size: 0.9em; text-align: center; 1px solid black">' . $semesterGrade->gpa . '</td>
                                        </tr>
                                        <tr>
                                            <th style="font-size: 0.9em; text-align: left; 1px solid black">PNG KUMULATIF KESELURUHAN (PNGKK)</th>
                                            <td style="font-size: 0.9em; text-align: center; 1px solid black">' . $semesterGrade->total_credit_cgpa . '</td>
                                            <td style="font-size: 0.9em; text-align: center; 1px solid black">' . $semesterGrade->cgpa . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        ';
                        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
                        $mpdf->SetHTMLHeader('
                            <div class="header" align="center">
                                <h1>KOLEJ VOKASIONAL MALAYSIA</h1>
                            </div>
                        ');
                        $mpdf->SetHTMLFooter('
                            <div class="footer">
                                <p style="font-style: italic;">Transkrip ini adalah janaan komputer.</p>
                                <p style="font-style: italic;">Tandatangan tidak diperlukan.</p>
                            </div>
                        ');
                        $mpdf->WriteHTML($studentDetailsHTML);
                        $mpdf->WriteHTML($courseGradesTopHTML);
                        foreach ($courseGrades as $courseGrade) {
                            $mpdf->WriteHTML('
                            <tr>
                                <td valign="top" style="font-size: 1em;">' . strtoupper($courseGrade->code) . '</td>
                                <td valign="top" style="font-size: 1em;">' . strtoupper($courseGrade->name) . '</td>
                                <td valign="top" style="font-size: 1em;">' . $courseGrade->credit_hour . '</td>
                                <td valign="top" style="font-size: 1em;">' . $courseGrade->grade_pointer . '</td>
                            </tr>');
                        }
                        $mpdf->SetHTMLHeader('
                            <div class="header" align="center">
                                <h1>KOLEJ VOKASIONAL MALAYSIA</h1>
                            </div>
                        ');
                        $mpdf->SetHTMLFooter('
                            <div class="footer">
                                <p style="font-style: italic;">Transkrip ini dijana menggunakan komputer.</p>
                                <p style="font-style: italic;">Tandatangan tidak diperlukan.</p>
                            </div>
                        ');
                        $mpdf->WriteHTML($courseGradesBottomHTML);
                        $mpdf->WriteHTML($semesterGradeHTML);
                        $mpdf->Output(ucwords($studentName) . ' - Transkrip Semester ' . $semester . ' ' . $studyLevelName, 'D');
                    }else{
                        abort(404, 'Transkrip untuk pelajar ini tidak dijumpai!');
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
