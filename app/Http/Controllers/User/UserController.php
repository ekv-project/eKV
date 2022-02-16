<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\MainController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class UserController extends MainController
{
    /**
     * Handling Views.
     */
    public function adminUserView(Request $request)
    {
        $pagination = 15;
        $user = User::paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                $user = User::where('username', 'LIKE', "%{$search}%")->orWhere('fullname', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('role', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            } else {
                $user = User::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.user.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengguna', 'user' => $user, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.user.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengguna', 'user' => $user]);
        }
    }

    public function adminAddUserView()
    {
        return view('dashboard.admin.user.add')->with(['page' => 'Tambah Pengguna']);
    }

    public function adminUpdateUserView($username)
    {
        if (User::where('username', $username)->first()) {
            $user = User::where('username', $username)->first();

            return view('dashboard.admin.user.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Pengguna', 'user' => $user]);
        } else {
            abort(404, 'Pengguna tidak dijumpai!');
        }
    }

    /**
     * Handling POST Request.
     */
    public function login(Request $request)
    {
        $username = strtolower($request->username);
        if (User::where('username', '=', $username)->count() > 0) {
            $credentials = [
                'username' => $username,
                'password' => $request->password,
            ];
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                LoginActivity::create([
                    'users_username' => $username,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->server('HTTP_USER_AGENT'),
                ]);
                $id = User::select('id')->where('username', '=', $username)->first()->id;
                $user = User::find($id);
                $token = $user->createToken('apitoken');
                // Add Bearer API Token to session
                $request->session()->put('bearerAPIToken', $token->plainTextToken);

                // Check if this is user first time login
                $firstTimeLoginStatus = User::select('first_time_login')->where('username', $username)->first()->first_time_login;
                if ('yes' == $firstTimeLoginStatus) {
                    $request->session()->flash('firstTimeLogin', 'yes');
                    User::where('id', $id)
                        ->update(['first_time_login' => 'no']);
                }

                return redirect()->intended('dashboard');
            } else {
                return back()->withInput()->withErrors([
                    'password' => 'Kata Laluan Salah.',
                ]);
            }
        } else {
            return back()->withInput()->withErrors([
                'username' => 'Pengguna tidak wujud.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (null != auth()->user()->tokens()) {
            auth()->user()->tokens()->delete();
        }
        // Remove Bearer API Token from session
        if ($request->session()->has('bearerAPIToken')) {
            $request->session()->forget('bearerAPIToken');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function adminAddUser(Request $request)
    {
        if ($request->has('addOne')) {
            /**
             * Add one user at a time.
             */
            $validated = $request->validate([
                'username' => ['required'],
                'fullname' => ['required'],
                'nric' => ['required', 'regex:/\d{6}-\d{2}-\d{4}/'],
                'gender' => ['required'],
                'email' => ['required', 'email:rfc'],
                'password' => ['required', 'confirmed'],
                'role' => ['required'],
            ]);

            // 'nric' => ['required', 'regex:/\d{6}-\d{2}-\d{4}/'],
            $username = strtolower($request->username);
            if (User::where('username', $username)->first()) {
                return back()->withInput()->withErrors([
                    'userExist' => 'Pengguna telah wujud!',
                ]);
            } else {
                if (User::where('email', strtolower($request->email))->first()) {
                    return back()->withInput()->withErrors([
                        'email' => 'Pengguna dengan e-mel tersebut telah wujud!',
                    ]);
                }

                switch ($request->input('gender')) {
                    case 'male':
                        $userGender = 0;
                        break;
                    case 'female':
                        $userGender = 1;
                        break;
                    case 'notapplicable':
                        $userGender = 2;
                        break;
                    default:
                        return redirect()->back()->withInput()->withErrors([
                            'gender' => 'Input tidak diketahui!',
                        ]);
                        break;
                }

                User::create([
                    'username' => strtolower($username),
                    'fullname' => strtolower($request->fullname),
                    'nric' => strtolower($request->nric),
                    'gender' => $userGender,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($request->password),
                    'role' => strtolower($request->role),
                ]);

                session()->flash('userAddSuccess', 'Pengguna berjaya ditambah!');

                return redirect()->back();
            }
        } elseif ($request->has('addBulk')) {
            /**
             * Add multiple users at a time with XLSX template
             * Handle XLSX with Phpspreadsheet library.
             */
            $validated = $request->validate([
                'user-xlsx' => ['required', 'mimes:xlsx'],
            ]);

            $spreadsheetErr = [];
            $inputFile = $request->file('user-xlsx');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly('1'); // Only read from worksheet named 1
            try {
                $spreadsheet = $reader->load($inputFile);
            } catch (PHPSpreadsheetReaderException $e) {
                exit('Error loading file: ' . $e->getMessage());
            }
            // Get all available rows. If a row is empty (in the username field), the rest of the row will be ignored. Warn the admin.
            $rows = $spreadsheet->getActiveSheet()->rangeToArray('A6:F106', null, false, false, true);

            if (empty($rows[6]['A']) || empty($rows[6]['B']) || empty($rows[6]['C']) || empty($rows[6]['D']) || empty($rows[6]['E']) || empty($rows[6]['F'])) {
                $error = 'Sekurang-kurangnya satu data pengguna diperlukan!';
                array_push($spreadsheetErr, $error);
                $request->session()->flash('spreadsheetErr', $spreadsheetErr);

                return back();
            }

            $availableRows = [];
            foreach ($rows as $row => $value) {
                if (!empty($value['A'])) {
                    array_push($availableRows, $row);
                } else {
                    // If one row is empty in the fullname field, all the following rows will be ignored.
                    break;
                }
            }

            $userData = [];
            foreach ($availableRows as $row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray('A' . $row . ':F' . $row, null, false, false, true);
                array_push($userData, $data);
            }

            $validUserList = [];
            foreach ($userData as $dataIndex) {
                foreach ($dataIndex as $data) {
                    $fullname = strtolower($data['A']);
                    $username = strtolower($data['B']);
                    $email = strtolower($data['C']);
                    $password = $data['D'];
                    $role = $data['E'];
                    $gender = $data['F'];
                    $currentRow = key($dataIndex);

                    // Check if user already existed with the username
                    if ('admin' == $username) {
                        // Check if adding user named 'admin'
                        $error = '[B' . $currentRow . '] ' . 'Anda tidak boleh menambah pengguna yang mempunyai username: admin!';
                        array_push($spreadsheetErr, $error);
                    } elseif (User::where('username', strtolower($username))->first()) {
                        $error = '[B' . $currentRow . '] ' . 'Pengguna dengan username tersebut telah wujud!';
                        array_push($spreadsheetErr, $error);
                    } else {
                        $usernameValid = $username;

                        if (empty($fullname)) {
                            $error = '[A' . $currentRow . '] ' . 'Ruangan nama penuh kosong!';
                            array_push($spreadsheetErr, $error);
                        } else {
                            $fullnameValid = $fullname;
                        }

                        if (empty($email)) {
                            $error = '[C' . $currentRow . '] ' . 'Ruangan alamat e-mel kosong!';
                            array_push($spreadsheetErr, $error);
                        } else {
                            // Check if email valid
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $error = '[C' . $currentRow . '] ' . 'Alamat e-mel mestilah merupakan alamat e-mel yang sah!';
                                array_push($spreadsheetErr, $error);
                            } else {
                                if (User::where('email', strtolower($email))->first()) {
                                    $error = '[C' . $currentRow . '] ' . 'Pengguna dengan e-mel tersebut telah wujud!';
                                    array_push($spreadsheetErr, $error);
                                } else {
                                    $emailValid = $email;
                                }
                            }
                        }

                        if (empty($password)) {
                            $error = '[D' . $currentRow . '] ' . 'Ruangan kata laluan kosong!';
                            array_push($spreadsheetErr, $error);
                        } else {
                            $passwordValid = $password;
                        }

                        if (empty($role)) {
                            $error = '[E' . $currentRow . '] ' . 'Ruangan peranan kosong!';
                            array_push($spreadsheetErr, $error);
                        } else {
                            // Check if role valid
                            // Pelajar = S
                            // Lecturer = L
                            // Admin = A
                            switch ($role) {
                                case 'S':
                                    $roleValid = 'student';
                                    break;
                                case 'L':
                                    $roleValid = 'lecturer';
                                    break;
                                case 'A':
                                    $roleValid = 'admin';
                                    break;
                                default:
                                    $error = '[E' . $currentRow . '] ' . 'Hanya masukkan huruf yang diperlukan di ruangan peranan!';
                                    array_push($spreadsheetErr, $error);
                                    break;
                            }
                        }

                        // User gender
                        if (empty($gender)) {
                            $error = '[F' . $currentRow . '] ' . 'Ruangan jantina kosong!';
                            array_push($spreadsheetErr, $error);
                        } else {
                            // Lelaki = L
                            // Perempuan = P
                            // Tidak Berkaitan = TB
                            switch ($gender) {
                                case 'L':
                                    $genderValid = 0;
                                    break;
                                case 'P':
                                    $genderValid = 1;
                                    break;
                                case 'TB':
                                    $genderValid = 2;
                                    break;
                                default:
                                    $error = '[F' . $currentRow . '] ' . 'Hanya masukkan huruf yang diperlukan di ruangan jantina!';
                                    array_push($spreadsheetErr, $error);
                                    break;
                            }
                        }

                        $validUser = [
                            'username' => $usernameValid,
                            'fullname' => $fullnameValid,
                            'email' => $emailValid,
                            'password' => Hash::make($passwordValid),
                            'role' => $roleValid,
                            'gender' => $genderValid,
                        ];
                        array_push($validUserList, $validUser);
                    }
                }
            }

            // If if there's no problem with the spreadsheet, if doesn't, proceed to add the users.
            if (count($spreadsheetErr) > 0) {
                $request->session()->flash('spreadsheetErr', $spreadsheetErr);

                return back();
            } else {
                User::upsert($validUserList, ['username'], ['fullname', 'email', 'password', 'role', 'gender']);
                $request->session()->flash('userBulkAddSuccess', count($validUserList) . ' pengguna berjaya ditambah secara pukal!');

                return back();
            }
        }
    }

    public function adminUpdateUser(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required'],
            'fullname' => ['required'],
            'gender' => ['required'],
            'email' => ['required', 'email:rfc'],
        ]);
        $username = $request->username;
        $fullname = $request->fullname;
        $email = $request->email;

        switch ($request->input('gender')) {
            case 'male':
                $userGender = 0;
                break;
            case 'female':
                $userGender = 1;
                break;
            case 'notapplicable':
                $userGender = 2;
                break;
            default:
                return redirect()->back()->withInput()->withErrors([
                    'gender' => 'Input tidak diketahui!',
                ]);
                break;
        }

        // Check if user existed
        if (User::where('username', $username)->first()) {
            User::where('username', $username)->update([
                'username' => strtolower($username),
                'fullname' => strtolower($fullname),
                'gender' => strtolower($userGender),
                'email' => strtolower($email),
            ]);
            session()->flash('userBulkAddSuccess', 'Pengguna berjaya dikemas kini!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Pengguna tidak wujud!',
            ]);
        }
    }

    public function remove(Request $request)
    {
        if (isset($request->username)) {
            $username = $request->username;
            if ('admin' == $username) {
                session()->flash('deleteError', 'Pengguna ini tidak boleh dibuang!');

                return redirect()->back();
            } else {
                if (User::where('username', $username)->first()) {
                    User::where('username', $username)->delete();
                    session()->flash('deleteSuccess', 'Pengguna berjaya dibuang!');

                    return redirect()->back();
                }
            }
        }
    }
}
