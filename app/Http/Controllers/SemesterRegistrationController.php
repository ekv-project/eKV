<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SemesterRegistrationController extends MainController
{
    public function registerView(Request $request)
    {
        return view('dashboard.semester.registration.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pendaftaran Semester']);
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
}
