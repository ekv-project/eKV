<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AnnouncementPost;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MainController;

class AnnouncementPostController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = 15;
        $announcementPost = AnnouncementPost::join('users', 'announcement_posts.user_id', '=', 'users.id')->select('announcement_posts.id', 'announcement_posts.title', 'announcement_posts.created_at', 'users.username')->paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                $announcementPost = AnnouncementPost::join('users', 'announcement_posts.user_id', '=', 'users.id')->select('announcement_posts.id', 'announcement_posts.title', 'announcement_posts.created_at', 'users.username')->where('announcement_posts.id', 'LIKE', "%{$search}%")->orWhere('title', 'LIKE', "%{$search}%")->orWhere('announcement_posts.created_at', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            } else {
                $announcementPost = AnnouncementPost::join('users', 'announcement_posts.user_id', '=', 'users.id')->select('announcement_posts.id', 'announcement_posts.title', 'announcement_posts.created_at', 'users.username')->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.announcement.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengumumuman', 'announcementPost' => $announcementPost, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.announcement.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengumumuman', 'announcementPost' => $announcementPost]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admin.announcement.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Pengumuman']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);
        $title = $request->title;
        $content = $request->content;
        $authorID = Auth::user()->id;
        AnnouncementPost::upsert([
            [
                'user_id' => $authorID,
                'title' => $title,
                'content' => $content,
            ],
        ], ['user_id'], ['title', 'content']);
        session()->flash('announcementAddSuccess', 'Pengumuman berjaya ditambah!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (AnnouncementPost::where('id', $id)->first()) {
            $announcementPost = AnnouncementPost::select('users.username', 'announcement_posts.id', 'announcement_posts.title', 'announcement_posts.content', 'announcement_posts.created_at', 'announcement_posts.updated_at')->join('users', 'announcement_posts.user_id', '=', 'users.id')->where('announcement_posts.id', $id)->first();

            return view('dashboard.admin.announcement.show')->with(['page' => 'Butiran Pengumuman', 'announcementPost' => $announcementPost, 'settings' => $this->instituteSettings]);
        } else {
            abort(404, 'Pengumuman tidak dijumpai!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $announcementPost = AnnouncementPost::select('users.username', 'users.id', 'announcement_posts.title', 'announcement_posts.content', 'announcement_posts.created_at', 'announcement_posts.updated_at')->join('users', 'announcement_posts.user_id', '=', 'users.id')->where('announcement_posts.id', $id)->first();

        return view('dashboard.admin.announcement.update')->with(['page' => 'Kemas Kini Pengumuman', 'announcementPost' => $announcementPost, 'settings' => $this->instituteSettings]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);
        $id = $id;
        $title = $request->title;
        $content = $request->content;
        $userID = $request->user;
        // Check if course existed
        if (AnnouncementPost::where('id', $id)->first()) {
            AnnouncementPost::upsert([
                [
                    'id' => $id,
                    'title' => strtolower($title),
                    'content' => strtolower($content),
                    'user_id' => $userID,
                ],
            ], ['id'], ['title', 'content', 'user_id']);
            session()->flash('announcementUpdateSuccess', 'Pengumuman berjaya dikemas kini!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Pengumuman tidak wujud!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            if (AnnouncementPost::where('id', $id)->first()) {
                AnnouncementPost::where('id', $id)->delete();
                session()->flash('deleteSuccess', 'Pengumuman berjaya dibuang!');

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
