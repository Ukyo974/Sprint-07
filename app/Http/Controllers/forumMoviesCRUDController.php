<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class forumMoviesCRUDController extends Controller
{
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function home()
    {
        $data['movies'] = Movies::orderBy('id','desc')->paginate(1000);
        return view('movies.mainView', $data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {                       
        return view('movies.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'release' => 'required',
            'time' => 'required',
            'synopsis' => 'required',
            'genre' => 'required',
            'img' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $path = $request->file('img')->store('public/images');


        $movie = new Movies;
        $movie -> name = $request -> name;
        $movie -> release = $request -> release;
        $movie -> time = $request -> time;
        $movie -> synopsis = $request -> synopsis;
        $movie -> genre = $request -> genre;
        $movie->img = $path;        
        $movie -> likeplus = $request -> likeplus;
        $movie -> likemoins = $request -> likemoins;
        $movie -> save();
        
        return redirect()->route('home')
            -> with('success','Movie has been created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Movies  $movie
    * @return \Illuminate\Http\Response
    */
    public function show(Movies $movie)
    {
        return view('movies.home', compact('movie'));
    } 

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Movies  $movie
    * @return \Illuminate\Http\Response
    */
    // public function edit(Movies $movie)
    // {
    //     return view('movies.edit', compact('movie'));
    // }

    public function edit(Movies $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Movies  $movie
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'release' => 'required',
            'time' => 'required',
            'synopsis' => 'required',
            'genre' => 'required',
            'likeplus' => '',
            'likemoins' => '',
        ]);

        $movie = Movies::find($id);

        if($request->hasFile('img')){
            $request->validate([
              'img' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $path = $request->file('img')->store('public/images');
            $movie->img = $path;
        }
    
        $movie -> name = $request -> name;
        $movie -> release = $request -> release;
        $movie -> time = $request -> time;
        $movie -> synopsis = $request -> synopsis;
        $movie -> genre = $request -> genre;
        // $movie -> img = $request -> img;
        $movie -> likeplus = $request -> likeplus;
        $movie -> likemoins = $request -> likemoins;
        $movie -> save();
        
        return redirect() -> route('home')
            -> with('success', 'Movie Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Movies  $movie
    * @return \Illuminate\Http\Response
    */
    public function destroy(Movies $movie)
    {
        $movie->delete();

        DB::table('comments')->where('id_movie', '=', $movie->id)->delete();
    
        return redirect() -> route('home')
            -> with('success', 'Movie has been deleted successfully');
    }
}
