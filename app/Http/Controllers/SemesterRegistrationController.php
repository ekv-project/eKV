<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\CourseSet;
use Illuminate\Http\Request;
use App\Models\SemesterSession;
use Illuminate\Support\Facades\Storage;

class SemesterRegistrationController extends MainController
{
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

    public function registrationIndividualViewPDF(Request $request)
    {
        $semester = 1;
        $studyLevelName = 'DVM';
        $studentID = 'Hanis Irfan';
        $pdfTitle = 'Pendaftaran Semester ' . $semester . ' ' . $studyLevelName . ' ' . ucwords($studentID);
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
        $registrationDepartment = 'Jabatan Teknologi Maklumat';
        $registrationProgram = 'Diploma Teknologi Maklumat';
        $registrationStudyYear = '1';
        $registrationSemester = '1';
        $registrationSessionYear = '1/2022';
        $studentName = 'Muhammad Ali Bin Abu';
        $studentICNo = '012345-67-8901';
        $studentMatrixNumber = 'K151GKSK001';
        PDF::MultiCell(0, 0, 'NAMA KOLEJ:               ' . strtoupper($instituteName), 0, 'L', 0, 0, '', '', true);
        PDF::Ln();
        PDF::MultiCell(0, 0, 'JABATAN:                     ' . strtoupper($registrationDepartment), 0, 'L', 0, 0, '', '', true);
        PDF::Ln();
        PDF::MultiCell(0, 0, 'PROGRAM:                   ' . strtoupper($registrationProgram), 0, 'L', 0, 0, '', '', true);
        PDF::Ln();
        PDF::MultiCell(0, 0, 'TAHUN PENGAJIAN:   ' . strtoupper($registrationStudyYear), 0, 'L', 0, 0, '', '', true);
        PDF::Ln();
        PDF::MultiCell(0, 0, 'SEMESTER:                 ' . strtoupper($registrationSemester), 0, 'L', 0, 0, '', '', true);
        PDF::Ln();
        PDF::MultiCell(0, 0, 'SESI / TAHUN:             ' . strtoupper($registrationSessionYear), 0, 'L', 0, 0, '', '', true);
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

        for ($i = 0; $i < 12; ++$i) {
            PDF::Ln();
            PDF::MultiCell(25, 10, 'DKB1113', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(72, 10, 'INTRODUCTION TO COMPUTER SYSTEM AND NETWORK', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(45, 10, 'ON-THE-JOB TRAINING', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(24, 10, '10', 1, 'C', 0, 0, '', '', true);
            PDF::MultiCell(24, 10, '10', 1, 'C', 0, 0, '', '', true);
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
        PDF::Output($pdfTitle . '.pdf', 'I');
    }

    /**
     * Handling POST Request.
     */
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
}
