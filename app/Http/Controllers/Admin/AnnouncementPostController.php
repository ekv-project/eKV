<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AnnouncementPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MainController;

class AnnouncementPostController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'content' => ['required']
        ]);
        $title = $request->title;
        $content = $request->content;
        $authorID = Auth::user()->id;
        AnnouncementPost::upsert([
            [
                'user_id' => $authorID,
                'title' => $title,
                'content' => $content
            ]
        ], ['user_id'], ['title', 'content']);
        session()->flash('announcementAddSuccess', 'Pengumuman berjaya ditambah!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(AnnouncementPost::where('id', $id)->first()){
            $announcementPost = AnnouncementPost::select('users.fullname', 'announcement_posts.id', 'announcement_posts.title', 'announcement_posts.content', 'announcement_posts.created_at', 'announcement_posts.updated_at')->join('users', 'announcement_posts.user_id', '=', 'users.id')->where('announcement_posts.id', $id)->first();
            return view('dashboard.admin.announcement.show')->with(['page' => 'Butiran Pengumuman', 'announcementPost' => $announcementPost, 'settings' => $this->instituteSettings]);
        }else{
            abort(404, 'Pengumuman tidak dijumpai!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
