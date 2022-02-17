<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Program;
use App\Models\CourseSet;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\CourseSetCourse;
use App\Models\SemesterSession;
use App\Models\ClassroomStudent;
use App\Models\SemesterRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SemesterRegistrationController extends MainController
{
    public function registrationMainView($username)
    {
        // Checks if user existed
        if (User::where('username', $username)->first()) {
            // Checks if current user is accessing their page
            if (Gate::allows('authUser', $username)) {
                // Checks if authenticated user is student
                if (Gate::allows('authStudent')) {
                    // Checks if student is associated with a class
                    if (ClassroomStudent::where('users_username', Auth::user()->username)) {
                        $studentClassroom = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom;
                        $programsCode = $studentClassroom->programs_code;
                        $studyLevelsCode = $studentClassroom->study_levels_code;
                        // Checks if have semester session for that program
                        if (CourseSet::where('programs_code', $programsCode)->first()) {
                            // Only show previous, current and next year
                            $currentYear = Carbon::now()->format('Y');
                            $nextYear = Carbon::now()->addYear()->format('Y');
                            $previousYear = Carbon::now()->subYear()->format('Y');
                            $years = [$previousYear, $currentYear, $nextYear];
                            $courseSets = CourseSet::where('programs_code', $programsCode)->get();
                            $semesterSessionIDArr = [];
                            foreach ($courseSets as $courseSet) {
                                $csID = $courseSet->id;
                                if (SemesterSession::where('course_sets_id', $csID)->first()) {
                                    array_push($semesterSessionIDArr, SemesterSession::where('course_sets_id', $csID)->first()->toArray()['id']);
                                }
                            }
                            $semesterSessions = [];
                            foreach ($semesterSessionIDArr as $id) {
                                $session = SemesterSession::where('id', $id)->first();
                                foreach ($years as $y) {
                                    if ($session->year == $y) {
                                        if (SemesterRegistration::where('semester_sessions_id', $id)->where('users_username', Auth::user()->username)->first()) {
                                            $registrationStatus = 1;
                                        } else {
                                            $registrationStatus = 0;
                                        }
                                        array_push($semesterSessions, ['id' => $id, 'studyLevel' => $studyLevelsCode, 'program' => $programsCode, 'session' => $session->session, 'year' => $session->year, 'sessionStatus' => $session->status, 'registrationStatus' => $registrationStatus]);
                                    } else {
                                        continue;
                                    }
                                }
                            }

                            return view('dashboard.semester.registration.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pendaftaran Semester', 'semesterSessions' => $semesterSessions]);
                        } else {
                            $semesterSessions = [];

                            return view('dashboard.semester.registration.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pendaftaran Semester', 'semesterSessions' => $semesterSessions]);
                        }
                    } else {
                        abort(404, 'Pelajar tidak berada dalam mana-mana kelas!');
                    }
                } else {
                    // For now, only students can access registration page.
                    // Maybe later I'll allow admins maybe to edit or something.
                    abort(403, 'Anda tidak dibenarkan mengakses laman ini!');
                }
            } else {
                abort(403, 'Anda tidak dibenarkan mengakses laman ini!');
            }
        } else {
            abort(404, 'Pengguna tidak dijumpai!');
        }
    }

    public function registrationApplyView($username, $id)
    {
        if ($this->studentChecks($username, $id)) {
            $courseSetID = SemesterSession::where('id', $id)->first()->course_sets_id;
            $courseSetCourses = CourseSetCourse::where('course_sets_id', $courseSetID)
                ->join('courses', 'course_set_courses.courses_code', '=', 'courses.code')
                ->select('courses.code', 'courses.name', 'courses.credit_hour', 'courses.total_hour', 'courses.category')
                ->get();

            if (UserProfile::where('users_username', Auth::user()->username)->first()) {
                $userProfile = UserProfile::where('users_username', Auth::user()->username)->first();
            } else {
                $userProfile = '';
            }

            $courseSet = CourseSet::where('id', $courseSetID)->first();
            $programsCode = $courseSet->programs_code;
            $program = Program::where('code', $programsCode)->first();
            $semesterSession = SemesterSession::where('id', $id)->first();
            $semester = $courseSet->semester;
            if (SemesterRegistration::where('semester_sessions_id', $id)->where('users_username', Auth::user()->username)->first()) {
                $registrationStatus = 1;
            } else {
                $registrationStatus = 0;
            }

            return view('dashboard.semester.registration.apply')->with(['settings' => $this->instituteSettings, 'page' => 'Pendaftaran Semester', 'courseSetCourses' => $courseSetCourses, 'userProfile' => $userProfile, 'program' => $program, 'semesterSession' => $semesterSession, 'semester' => $semester, 'registrationStatus' => $registrationStatus]);
        }
    }

    public function adminSemesterRegistrationView(Request $request)
    {
        $pagination = 15;
        $semesterSessions = SemesterSession::select('semester_sessions.id', 'semester_sessions.course_sets_id', 'semester_sessions.session', 'semester_sessions.year', 'semester_sessions.status', 'course_sets.study_levels_code', 'course_sets.programs_code', 'course_sets.semester')
            ->join('course_sets', 'semester_sessions.course_sets_id', '=', 'course_sets.id')
            ->paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                switch (strtolower($search)) {
                    case 'buka':
                        $search = 'open';
                        break;
                    case 'tutup':
                        $search = 'close';
                        break;
                    default:
                        $search = strtolower($search);
                        break;
                }

                switch ($sortBy) {
                    case 'course_set_id':
                        $sortByColumn = 'semester_sessions.course_sets_id';
                        break;
                    case 'study_level':
                        $sortByColumn = 'course_sets.study_levels_code';
                        break;
                    case 'program':
                        $sortByColumn = 'course_sets.programs_code';
                        break;
                    case 'semester':
                        $sortByColumn = 'course_sets.semester';
                        break;
                    case 'status':
                        $sortByColumn = 'semester_sessions.status';
                        break;
                    case 'session':
                        $sortByColumn = 'semester_sessions.session';
                        break;
                    case 'year':
                        $sortByColumn = 'semester_sessions.year';
                        break;
                    default:
                        $sortByColumn = 'semester_sessions.course_sets_id';
                        break;
                }

                $semesterSessions = SemesterSession::select('semester_sessions.id', 'semester_sessions.course_sets_id', 'semester_sessions.session', 'semester_sessions.year', 'semester_sessions.status', 'course_sets.study_levels_code', 'course_sets.programs_code', 'course_sets.semester')
                    ->join('course_sets', 'semester_sessions.course_sets_id', '=', 'course_sets.id')
                    ->orderBy($sortByColumn, $sortOrder)
                    ->where('semester_sessions.course_sets_id', 'LIKE', "%{$search}%")->orWhere('semester_sessions.session', 'LIKE', "%{$search}%")->orWhere('semester_sessions.year', 'LIKE', "%{$search}%")
                    ->orWhere('semester_sessions.status', 'LIKE', "%{$search}%")->orWhere('course_sets.study_levels_code', 'LIKE', "%{$search}%")->orWhere('course_sets.programs_code', 'LIKE', "%{$search}%")->orWhere('course_sets.semester', 'LIKE', "%{$search}%")
                    ->paginate($pagination)->withQueryString();
            } else {
                switch ($sortBy) {
                    case 'course_set_id':
                        $sortByColumn = 'semester_sessions.course_sets_id';
                        break;
                    case 'study_level':
                        $sortByColumn = 'course_sets.study_levels_code';
                        break;
                    case 'program':
                        $sortByColumn = 'course_sets.programs_code';
                        break;
                    case 'semester':
                        $sortByColumn = 'course_sets.semester';
                        break;
                    case 'status':
                        $sortByColumn = 'semester_sessions.status';
                        break;
                    case 'session':
                        $sortByColumn = 'semester_sessions.session';
                        break;
                    case 'year':
                        $sortByColumn = 'semester_sessions.year';
                        break;
                    default:
                        $sortByColumn = 'semester_sessions.course_sets_id';
                        break;
                }

                $semesterSessions = SemesterSession::select('semester_sessions.id', 'semester_sessions.course_sets_id', 'semester_sessions.session', 'semester_sessions.year', 'semester_sessions.status', 'course_sets.study_levels_code', 'course_sets.programs_code', 'course_sets.semester')
                    ->join('course_sets', 'semester_sessions.course_sets_id', '=', 'course_sets.id')
                    ->orderBy($sortByColumn, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.semester.registration.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Sesi Pendaftaran Semester', 'semesterSessions' => $semesterSessions, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.semester.registration.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Sesi Pendaftaran Semester', 'semesterSessions' => $semesterSessions]);
        }
    }

    public function adminSemesterRegistrationAddView(Request $request)
    {
        $sessions = ['1', '2'];
        $currentYear = Carbon::now()->format('Y');
        $nextYear = Carbon::now()->addYear()->format('Y');
        $previousYear = Carbon::now()->subYear()->format('Y');
        $years = [$previousYear, $currentYear, $nextYear];

        return view('dashboard.admin.semester.registration.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Sesi Pendaftaran Semester', 'sessions' => $sessions, 'years' => $years]);
    }

    public function adminSemesterRegistrationUpdateView(Request $request, $id)
    {
        $sessions = ['1', '2'];
        $currentYear = Carbon::now()->format('Y');
        $nextYear = Carbon::now()->addYear()->format('Y');
        $previousYear = Carbon::now()->subYear()->format('Y');
        $years = [$previousYear, $currentYear, $nextYear];

        $semesterSession = SemesterSession::where('id', $id)->first();

        return view('dashboard.admin.semester.registration.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Sesi Pendaftaran Semester', 'sessions' => $sessions, 'years' => $years, 'semesterSession' => $semesterSession]);
    }

    public function registrationIndividualViewPDF($username, $id)
    {
        if ($this->studentChecks($username, $id)) {
            $courseSetID = SemesterSession::where('id', $id)->first()->course_sets_id;
            $courseSetCourses = CourseSetCourse::where('course_sets_id', $courseSetID)
                ->join('courses', 'course_set_courses.courses_code', '=', 'courses.code')
                ->select('courses.code', 'courses.name', 'courses.credit_hour', 'courses.total_hour', 'courses.category')
                ->get();

            if (UserProfile::where('users_username', Auth::user()->username)->first()) {
                $userProfile = UserProfile::where('users_username', Auth::user()->username)->first();
            } else {
                $userProfile = '';
            }

            $courseSet = CourseSet::where('id', $courseSetID)->first();
            $programsCode = $courseSet->programs_code;
            $studyLevelsCode = $courseSet->study_levels_code;
            $program = Program::where('code', $programsCode)->first();
            $semesterSession = SemesterSession::where('id', $id)->first();
            $semester = $courseSet->semester;

            $registrationDepartment = strtoupper($program->department_name);
            $registrationProgram = strtoupper($program->name);
            switch ($semester) {
                case '1':
                    $studyYear = '1';
                    break;
                case '2':
                    $studyYear = '1';
                    break;
                case '3':
                    $studyYear = '2';
                    break;
                case '4':
                    $studyYear = '2';
                    break;
                default:
                    $studyYear = '';
                    break;
            }
            $user = User::where('username', $username)->first();
            $registrationStudyYear = $studyYear;
            $registrationSemester = $semester;
            $registrationSessionYear = $semesterSession->session . '/' . $semesterSession->year;
            $studentName = $user->fullname;
            $studentICNo = $user->nric;
            $studentMatrixNumber = $user->username;

            $pdfTitle = 'Pendaftaran Semester ' . $semester . ' ' . strtoupper($studyLevelsCode) . ' ' . ucwords($studentName);
            PDF::SetCreator('eKV');
            PDF::SetAuthor('eKV');
            PDF::SetTitle($pdfTitle);
            PDF::AddPage();
            PDF::SetFont('helvetica', 'B', 10);
            $settings = $this->instituteSettings;
            if (isset($settings)) {
                if (empty($settings['institute_name'])) {
                    $instituteName = 'Kolej Vokasional Malaysia';
                } else {
                    $instituteName = ucwords($settings['institute_name']);
                }
            } else {
                $instituteName = 'Kolej Vokasional Malaysia';
            }
            if (Storage::disk('local')->exists('public/img/system/logo-300.png')) {
                $logo = '.' . Storage::disk('local')->url('public/img/system/logo-300.png');
            } elseif (Storage::disk('local')->exists('public/img/system/logo-def-300.png')) {
                $logo = '.' . Storage::disk('local')->url('public/img/system/logo-def-300.png');
            }
            // Header
            PDF::Image($logo, 10, 10, 26, 26);
            PDF::SetFont('helvetica', 'B', 12);
            PDF::SetXY(38, 10);
            PDF::Multicell(135, 0, strtoupper($instituteName), 0, 'L', 0, '', '', '');
            PDF::SetFont('helvetica', '', 7);
            PDF::Ln();
            PDF::Multicell(135, 0, 'ALAMAT KOLEJ: ' . strtoupper($settings['institute_address']), 0, 'L', 0, '', 38, '');
            PDF::Ln();
            PDF::Multicell(135, 0, 'E-MEL: ' . strtoupper($settings['institute_email_address']), 0, 'L', 0, '', 38, '');
            PDF::Ln();
            PDF::Multicell(135, 0, 'NO TELEFON: ' . strtoupper($settings['institute_phone_number']), 0, 'L', 0, '', 38, '');
            //PDF::Image($logo, 174, 10, 26, 26); // KPM logo *TBA if allowed by them
            PDF::SetXY(0, 37);
            PDF::writeHTML('<hr>', true, false, false, false, '');
            PDF::SetFont('helvetica', 'b', 10);
            PDF::Multicell(0, 5, 'PENDAFTARAN KURSUS', 0, 'C', 0, 2, 10, 38);
            PDF::Ln(1);
            PDF::writeHTML('<hr>', true, false, false, false, '');
            // Application Details
            PDF::SetXY(10, 46);
            PDF::SetFont('helvetica', 'B', 9);
            PDF::MultiCell(95, 13, 'A: MAKLUMAT PERMOHONAN', 0, 'L', 0, 0, '', '', true);
            PDF::Ln(5);
            PDF::SetFont('helvetica', '', 9);
            PDF::MultiCell(0, 0, 'NAMA KOLEJ:               ' . strtoupper($instituteName), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'JABATAN:                     ' . strtoupper($registrationDepartment), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'PROGRAM:                   ' . strtoupper($registrationProgram), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'TAHUN PENGAJIAN:    ' . strtoupper($registrationStudyYear), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'SEMESTER:                  ' . strtoupper($registrationSemester), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'SESI / TAHUN:              ' . strtoupper($registrationSessionYear), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'NAMA PELAJAR:          ' . strtoupper($studentName), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'NO K/P:                         ' . strtoupper($studentICNo), 0, 'L', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(0, 0, 'ANGKA GILIRAN:         ' . strtoupper($studentMatrixNumber), 0, 'L', 0, 0, '', '', true);
            // Course Registered List (Based on course set)
            // Maximum: 12 courses
            PDF::SetXY(10, 95);
            PDF::writeHTML('<hr>', true, false, false, false, '');
            PDF::SetXY(10, 97);
            PDF::SetFont('helvetica', 'B', 9);
            PDF::MultiCell(95, 13, 'B: MAKLUMAT KURSUS DIPOHON', 0, 'L', 0, 0, '', '', true);
            PDF::Ln(5);
            PDF::SetFont('helvetica', 'B', 9);
            PDF::MultiCell(25, 5, 'KOD KURSUS', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(72, 5, 'NAMA KURSUS', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(45, 5, 'KATEGORI KURSUS', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(24, 5, 'BIL KREDIT', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(24, 5, 'JUMLAH JAM', 1, 'C', 0, 0, '', '', true);
            PDF::SetFont('helvetica', '', 9);

            // Testing purposes
            // for ($i = 0; $i < 12; ++$i) {
            //     PDF::Ln();
            //     PDF::MultiCell(25, 10, 'DKB1113', 1, 'C', 0, 0, '', '', true);
            //     PDF::MultiCell(72, 10, 'INTRODUCTION TO COMPUTER SYSTEM AND NETWORK', 1, 'C', 0, 0, '', '', true);
            //     PDF::MultiCell(45, 10, 'ON-THE-JOB TRAINING', 1, 'C', 0, 0, '', '', true);
            //     PDF::MultiCell(24, 10, '10', 1, 'C', 0, 0, '', '', true);
            //     PDF::MultiCell(24, 10, '10', 1, 'C', 0, 0, '', '', true);
            // }

            for ($i = 0; $i < count($courseSetCourses); ++$i) {
                PDF::Ln();
                PDF::MultiCell(25, 10, strtoupper($courseSetCourses[$i]->code), 1, 'C', 0, 0, '', '', true);
                PDF::MultiCell(72, 10, strtoupper($courseSetCourses[$i]->name), 1, 'C', 0, 0, '', '', true);
                switch ($courseSetCourses[$i]->category) {
                    case 1:
                        $category = 'PENGAJIAN UMUM';
                        break;
                    case 2:
                        $category = 'TERAS';
                        break;
                    case 3:
                        $category = 'PENGKHUSUSAN';
                        break;
                    case 4:
                        $category = 'ELEKTIF';
                        break;
                    case 5:
                        $category = 'ON-THE-JOB TRAINING';
                        break;
                    default:
                        $category = '';
                        break;
                }
                PDF::MultiCell(45, 10, strtoupper($category), 1, 'C', 0, 0, '', '', true);
                PDF::MultiCell(24, 10, strtoupper($courseSetCourses[$i]->credit_hour), 1, 'C', 0, 0, '', '', true);
                PDF::MultiCell(24, 10, strtoupper($courseSetCourses[$i]->total_hour), 1, 'C', 0, 0, '', '', true);
            }

            // Signature
            PDF::SetXY(50, 238);
            PDF::writeHTML('<hr>', true, false, false, false, '');
            PDF::SetXY(10, 240);

            PDF::SetFont('helvetica', '', 9);
            PDF::MultiCell(105, 5, 'YANG BENAR,', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, 'DISAHKAN OLEH,', 0, 'C', 0, 0, '', '', true);
            PDF::Ln(12);
            PDF::MultiCell(105, 5, '...................................................', 0, 'C', 0, 0, '', '', true);
            PDF::MultiCell(85, 5, '...................................................', 0, 'C', 0, 0, '', '', true);
            PDF::Ln();
            PDF::MultiCell(105, 5, strtoupper($studentName), 0, 'C', 0, 0, '', '', true);

            PDF::SetXY(10, 268);
            PDF::Ln(3);
            PDF::SetFont('helvetica', 'B', 9);
            PDF::MultiCell(0, 5, '*PENGESAHAN HANYA BOLEH DIBUAT OLEH PENGARAH, TPA, KETUA JABATAN ATAU KETUA PROGRAM', 0, 'C', 0, 0, '', '', true);
            PDF::Output($pdfTitle . '.pdf', 'D');
        }
    }

    /**
     * Handling POST Request.
     */
    public function registrationApply($username, $id)
    {
        if ($this->studentChecks($username, $id)) {
            // idk why I even made a form if there's also the same data in the URL. Maybe just to have a POST request.
            SemesterRegistration::updateOrCreate(
                ['semester_sessions_id' => $id, 'users_username' => $username],
                ['semester_sessions_id' => $id, 'users_username' => $username]
            );
            session()->flash('semesterRegistrationSuccess', 'Pendaftaran Semester berjaya!');

            return redirect()->back();
        }
    }

    public function adminSemesterRegistrationAdd(Request $request)
    {
        $validated = $request->validate([
            'course_set_id' => ['required'],
            'session' => ['required', 'integer'],
            'year' => ['required', 'integer'],
        ]);
        $courseSetID = $request->course_set_id;
        $session = $request->session;
        $year = $request->year;
        $status = 'open';

        // Check if session existed
        if (!SemesterSession::where('course_sets_id', $courseSetID)->where('session', $session)->where('year', $year)->first()) {
            // Check if course set existed
            if (CourseSet::where('id', $courseSetID)->first()) {
                SemesterSession::upsert([
                    [
                        'course_sets_id' => strtolower($courseSetID),
                        'session' => $session,
                        'year' => $year,
                        'status' => $status,
                    ],
                ], ['course_sets_id', 'session', 'year'], ['course_sets_id', 'session', 'year', 'status']);
                session()->flash('semesterRegistrationSessionAddSuccess', 'Sesi Pendaftaran Semester berjaya ditambah!');
            } else {
                return redirect()->back()->withInput()->withErrors([
                    'course_set' => 'Set Kursus tidak wujud!',
                ]);
            }

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'existed' => 'Sesi Pendaftaran Semester telah wujud!',
            ]);
        }
    }

    public function adminSemesterRegistrationUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'course_set_id' => ['required'],
            'session' => ['required', 'integer'],
            'year' => ['required', 'integer'],
            'status' => ['required'],
        ]);
        $courseSetID = $request->course_set_id;
        $session = $request->session;
        $year = $request->year;
        $status = $request->status;

        // Checks if semester session existed
        if (SemesterSession::where('id', $id)->first()) {
            // Checks if requested course set, session and year already used by a semester session
            if (SemesterSession::where('course_sets_id', $courseSetID)->where('session', $session)->where('year', $year)->first()) {
                // Checks if that semester session is this semester session
                if (SemesterSession::where('course_sets_id', $courseSetID)->where('session', $session)->where('year', $year)->first()->id == $id) {
                    // Update
                    // Check if course set existed
                    if (CourseSet::where('id', $courseSetID)->first()) {
                        SemesterSession::where('id', $id)
                            ->update([
                                'course_sets_id' => $courseSetID,
                                'session' => $session,
                                'year' => $year,
                                'status' => $status,
                            ]);
                        session()->flash('semesterRegistrationSessionUpdateSuccess', 'Sesi Pendaftaran Semester berjaya dikemas kini!');

                        return redirect()->back();
                    } else {
                        return redirect()->back()->withInput()->withErrors([
                            'course_set' => 'Set Kursus tidak wujud!',
                        ]);
                    }
                } else {
                    // Return error
                    return redirect()->back()->withInput()->withErrors([
                        'existed' => 'Sesi Pendaftaran Semester dengan Set Kursus, Sesi dan Tahun yang sama telah wujud!',
                    ]);
                }
            } else {
                // Update
                // Check if course set existed
                if (CourseSet::where('id', $courseSetID)->first()) {
                    SemesterSession::where('id', $id)
                        ->update([
                            'course_sets_id' => $courseSetID,
                            'session' => $session,
                            'year' => $year,
                            'status' => $status,
                        ]);
                    session()->flash('semesterRegistrationSessionUpdateSuccess', 'Sesi Pendaftaran Semester berjaya dikemas kini!');

                    return redirect()->back();
                } else {
                    return redirect()->back()->withInput()->withErrors([
                        'course_set' => 'Set Kursus tidak wujud!',
                    ]);
                }
            }
        } else {
            return redirect()->back()->withInput()->withErrors([
                'not_existed' => 'Sesi Pendaftaran Semester tidak wujud!',
            ]);
        }

        return redirect()->back()->withInput()->withErrors([
            'existed' => 'Sesi Pendaftaran Semester dengan Set Kursus, Sesi dan Tahun yang sama telah wujud!',
        ]);
    }

    public function adminSemesterRegistrationRemove(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            SemesterSession::where('id', $id)->delete();

            session()->flash('deleteSuccess', 'Sesi Pendaftaran Semester berjaya dibuang!');

            return redirect()->back();
        }
    }

    /**
     * Resuable codes.
     */
    protected function studentChecks($username, $id)
    {
        // Checks if user existed
        if (User::where('username', $username)->first()) {
            // Checks if current user is accessing their page
            if (Gate::allows('authUser', $username)) {
                // Checks if authenticated user is student
                // Only students can apply
                if (Gate::allows('authStudent')) {
                    // Checks if user have set their profile
                    if (UserProfile::where('users_username', $username)->first()) {
                        // Check if student is associated with a class
                        if (ClassroomStudent::where('users_username', Auth::user()->username)) {
                            $studentClassroom = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom;
                            $programsCode = $studentClassroom->programs_code;
                            // Checks if have semester session for that program
                            if (CourseSet::where('programs_code', $programsCode)->first()) {
                                // Checks if semester registration for that session is still open
                                if (SemesterSession::where('id', $id)->first()) {
                                    if ('open' === SemesterSession::where('id', $id)->first()->status) {
                                        return true;
                                    } else {
                                        abort(500, 'Sesi Pendaftaran Semester tersebut sudah ditutup!');
                                    }
                                } else {
                                    return redirect()->route('semester.registration.view', ['username' => $username]);
                                }
                            } else {
                                return redirect()->route('semester.registration.view', ['username' => $username]);
                            }
                        } else {
                            abort(404, 'Pelajar tidak berada dalam mana-mana kelas!');
                        }
                    } else {
                        abort(404, 'Profil pengguna tidak dijumpai!');
                    }
                } else {
                    abort(403, 'Anda tidak dibenarkan mengakses laman ini!');
                }
            } else {
                abort(403, 'Anda tidak dibenarkan mengakses laman ini!');
            }
        } else {
            abort(404, 'Pengguna tidak dijumpai!');
        }
    }
}
