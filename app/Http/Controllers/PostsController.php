<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Models\Post;
use App\Models\PostMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'create',
            'edit',
            'update',
            'destroy'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = DB::select('SELECT * FROM posts WHERE id= :id', ["id" => 1]);
        // $posts = DB::insert('INSERT INTO posts (title, excerpt, body, image_path, is_published, min_to_read) 
        // VALUE (?,?,?,?,?,?)', [
        //     "Test",
        //     "Test",
        //     "Test",
        //     "Test",
        //     true,
        //     1
        // ]);

        // $posts = DB::update('UPDATE posts SET body= ? WHERE id = ?', [
        //     "Body 2",
        //     101
        // ]);
        
        // $posts = DB::delete('DELETE FROM posts WHERE id = ?', [101]);

        // $posts = DB::table('posts')
        //     // ->select('title', 'body', 'is_published')
        //     // ->where('id', '>', 50)
        //     // ->where('is_published', true
        //     // ->whereBetween('min_to_read', [2, 6])
        //     // ->whereNotBetween('min_to_read', [2, 6])
        //     // ->whereIn('min_to_read', [2, 6, 8])
        //     // ->select('min_to_read')
        //     // ->distinct()
        //     // ->orderBy('id', 'desc')
        //     // ->skip(0)
        //     // ->take(10)
        //     ->inRandomOrder()
        //     ->get();

        // $posts = DB::table('posts')
        //     ->where('id', 100)
        //     ->first();

        // $posts = DB::table('posts')
        //     // ->find(90);
        //     ->where('id', 90)
        //     ->value('body');

        // $posts = DB::table('posts')
        //     ->where('id', '>', 50)
        //     ->count();
        // $posts = DB::table('posts')
            // ->min('min_to_read');
            // ->max('min_to_read');
            // ->sum('min_to_read');
            // ->avg('min_to_read');

        // $posts = DB::table('posts')->get();


        // return view('blog.index', [
        //     "posts" => $posts
        // ]);

        // Eloquent ORM

        // $posts = Post::orderBy('id', "desc")->take(10)->get();

        // $posts = Post::where('min_to_read', '!=', 2)->get();

        // dd($posts);

        // Post::chunk(25, function($posts) {
        //     foreach($posts as $post) {
        //         echo $post->title. "<br>";
        //     }
        // });

        // $posts = Post::get()->count();
        // $posts = Post::sum('min_to_read');
        // $posts = Post::avg('min_to_read');
        $posts = Post::orderBy('id', 'desc')->paginate(50);

        return view('blog.index', [
            "posts" => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFormRequest $request)
    {
        // $post = new Post();

        // $post->title = $request->title;
        // $post->excerpt = $request->excerpt;
        // $post->body = $request->body;
        // $post->image_path = 'temporary';
        // $post->is_published = $request->is_published === 'on';
        // $post->min_to_read = $request->min_to_read;

        // $post->save();

        // $request->validate([
        //     'title' => 'required|unique:posts|max:255',
        //     'excerpt' => 'required',
        //     'body' => 'required',
        //     'image' => ['required', 'mimes:jpg,png,jpeg', 'max:5048'],
        //     'min_to_read' => 'min:0|max:60',
            
        // ]);

        $request->validated();

         $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => $this->storeImage($request),
            'is_published' => $request->is_published === 'on',
            'min_to_read' => $request->min_to_read,
        ]);

        $post->meta()->create([
            'post_id' => $post->id,
            'meta_description' => $request->meta_description === null ? 'description': $request->meta_description,
            'meta_keywords' => $request->meta_keywords === null ? 'keywords': $request->meta_keywords,
            'meta_robots' => $request->meta_robots === null ? 'robots': $request->meta_robots,            
        ]);

        return redirect(route('blog.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('blog.show', [
            'post' => Post::findOrFail($id),
            'postmeta' => PostMeta::where('post_id', $id)->first() === null ? null : PostMeta::where('post_id', $id)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('blog.edit', [
            'post' => Post::where('id', $id)->first(),
            'postmeta' => PostMeta::where('post_id', $id)->first() === null ? null : PostMeta::where('post_id', $id)->first()
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostFormRequest $request, $id)
    {
        // $request->validate([
        //     'title' => 'required|max:255|unique:posts,title,' . $id,
        //     'excerpt' => 'required',
        //     'body' => 'required',
        //     'image' => ['mimes:jpg,png,jpeg', 'max:5048'],
        //     'min_to_read' => 'min:0|max:60', 
        // ]);
        
        $request->validated();

        $post = Post::where('id', $id)->update([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => $request->image === null ? $request->image_path : $this->storeImage($request),
            'is_published' => $request->is_published === 'on',
            'min_to_read' => $request->min_to_read,
        ]);

        // $post->meta()->where('id', $id)->update([
        //     'post_id' => $post->id,
        //     'meta_description' => $request->meta_description,
        //     'meta_keywords' => $request->meta_keywords,
        //     'meta_robots' => $request->meta_robots,            
        // ]);

        $test = PostMeta::where('post_id', $id)->first();

        // dd($test);

        if($test !== null) {
            PostMeta::where('post_id', $id)->update([
                'post_id' => $id,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_robots' => $request->meta_robots
            ]);
        } else {
            PostMeta::create([
                'post_id' => $id,
                'meta_description' => $request->meta_description === null ? 'description': $request->meta_description,
                'meta_keywords' => $request->meta_keywords === null ? 'keywords': $request->meta_keywords,
                'meta_robots' => $request->meta_robots === null ? 'robots': $request->meta_robots,
            ]);
        }
        


        return redirect(route('blog.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::destroy($id);

        return redirect(route('blog.index'))->with('message', 'Post has been deleted');
    }

    private function storeImage($request) 
    {
        $newImageName = uniqid() . '-' . $request->title . '.' . 
        $request->image->extension();
 
        return $request->image->move(public_path('image'), $newImageName);
    }
}
