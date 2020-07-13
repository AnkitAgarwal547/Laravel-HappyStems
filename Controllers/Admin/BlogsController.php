<?php

namespace App\Http\Controllers\Admin;
use App\BlogTags;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blogs;
use App\BlogCategories;
use App\BlogComments;
use App\Libraries\Slug;
use Session;
use Redirect;
use Auth;
use Validator;

class BlogsController extends Controller
{
    public function index(){
		$blogs = Blogs::orderBy('id', 'desc')->get();
		return view('admin.blog.blogs',compact('blogs'));
	}
	
	
	//Add Blog Form
	public function addBlog(){
		$categories = BlogCategories::where('parent', '=', 0)->get();
		$tags = BlogTags::where('status', '=', 1)->get();

		return view('admin.blog.add-blog',compact('categories', 'tags'));
	}
	
	//Save Blog Form
	public function saveBlog(Request $request){
			$validator = Validator::make($request->all(),[
    			 'title' => 'required|min:5',
				 'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);
			
			if ($validator->fails()) {
            		return redirect('admin/blogs/add')
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$title				= $request->input('title');
			$description		= $request->input('description');
			$allow_comments		= $request->input('allow_comments');
			$categories			= $request->input('categories');
			if($categories != '') {
				$comma_categories   = implode(",", $categories);
			}else{
				$comma_categories = '';
			}

            $tags			= $request->input('tags');
            if($tags != '') {
                $sep_tags   = implode(",", $tags);
            }else{
                $sep_tags = '';
            }

            $related_post	= $request->input('related_post');

			
			$visibility			= $request->input('status');
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');
			
			$slugClass = new Slug();
    		$blogSlug = $slugClass->make($title, 'blogs', 'slug');
			
			$blog  = new Blogs();

			$blog->title 			= $title;
			$blog->slug				= $blogSlug;
			$blog->description 		= $description;
			
			$blog->page_title 		= $page_title;
			$blog->page_keyword 	= $page_keyword;
			$blog->page_description = $page_description;
			
			$blog->allow_comments	= $allow_comments;
			$blog->categories		= $comma_categories;
			$blog->tags             = $sep_tags;
            $blog->related_post	    = ($related_post == 1) ? 1 : 0;
            $blog->visibility		= $visibility;
			
						
			if ($request->hasFile('featured_image'))
			{
				$image = $request->file('featured_image');
				
				$filename  = $blogSlug.'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/blog/'), $filename);
				$blog->featured_image = 'assets/uploads/blog/'.$filename;
			}
			
			$blog->created_at 	= date('Y-m-d H:i:s');
			$blog->updated_at 	= date('Y-m-d H:i:s');
			
			if($blog->save()){
				Session::flash('message', "Blog added successfully.");
				return redirect('/admin/blogs');
			}else{
				Session::flash('error', "Blog creation fails. Please try again after some time.");
				return redirect('/admin/blogs');
			}
			
	}
	
	//EDIT BLOG
	public function editBlog($id){
		$editBlog   = Blogs::find($id);
		if(!empty($editBlog)){
			$categories = BlogCategories::where('parent', '=', 0)->get();
            $tags = BlogTags::where('status', '=', 1)->get();

        	return view('admin.blog.edit-blog',compact('categories','editBlog', 'tags'));
		}else{
			return redirect('admin/blogs');
		}
	}
	
	public function updateBlog($id,Request $request){
		$editBlog   = Blogs::find($id);
		if(!empty($editBlog)){
			$validator = Validator::make($request->all(),[
    				'title' => 'required|min:5',
				 	'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/blogs/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			
			$title				= $request->input('title');
			$description		= $request->input('description');
			$allow_comments		= $request->input('allow_comments');
			$categories			= $request->input('categories');
			if($categories != '') {
				$comma_categories   = implode(",", $categories);
			}else{
				$comma_categories = '';
			}

            $tags			= $request->input('tags');
            if($tags != '') {
                $sep_tags   = implode(",", $tags);
            }else{
                $sep_tags = '';
            }

            $related_post	= $request->input('related_post');
			
			$visibility			= $request->input('status');
			$page_title     	= $request->input('page_title');
			$page_keyword   	= $request->input('page_keyword');
			$page_description 	= $request->input('page_description');		

			$editBlog->title 			= $title;
			$editBlog->description 		= $description;
			$editBlog->allow_comments	= $allow_comments;
			$editBlog->categories 		= $comma_categories;
			$editBlog->tags             = $sep_tags;
            $editBlog->related_post     = $related_post;
			$editBlog->visibility 		= $visibility;
			$editBlog->page_title 		= $page_title;
			$editBlog->page_keyword 	= $page_keyword;
			$editBlog->page_description = $page_description;
			
			
			
			if ($request->hasFile('featured_image'))
			{
				$image = $request->file('featured_image');
				
				$filename  = $editBlog->slug.'-'.rand(11111,99999) . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/blog/'), $filename);
				$editBlog->featured_image = 'assets/uploads/blog/'.$filename;
			}
			
			$editBlog->updated_at 	= date('Y-m-d H:i:s');
			
			if($editBlog->save()){
				
				Session::flash('message', "Blog updated successfully.");
				return redirect('/admin/blogs');
			}else{
				Session::flash('error', "Blog updation fails. Please try again after some time.");
				return redirect('/admin/blogs');
			}
		}else{
			return redirect('/admin/blogs');
		}
	}
	
	
	
	//Delete Blog
	public function deleteBlog($id){
		$deleteBlog   = Blogs::find($id);
		if(!empty($deleteBlog)){
				$deleteBlog->delete();
				Session::flash('message', "Blog deleted successfully.");
				return redirect('/admin/blogs');
		}else{
			return redirect('/admin/blogs');
		}
	}
	
	
	//Blog Categories
	public function blogCategories(){
		$categories = BlogCategories::where('parent', '=', 0)->get();
		$allCategories = BlogCategories::pluck('name','id','slug','thumbnail','description')->all();
		return view('admin.blog.blog-categories',compact('categories','allCategories'));
	}
	
	//ADD Blog Categories
	public function addBlogCategories(Request $request){
			$validator = Validator::make($request->all(),[
    			 'catname' => 'required|min:3',
				 'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/blogs/categories')
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$catname 			= $request->input('catname');
			$catslug 			= $request->input('slug');
			$catparent 			= $request->input('parent');
			$catDescription 	= $request->input('description');
			$catStatus			= $request->input('status');
			
			
			if($catslug == ''){
				$catslug = str_slug($catname, '-');
			}
			
			$slugClass = new Slug();
    		$newslug = $slugClass->make($catslug, 'blog_categories', 'slug');
			
			
			$filename = '';
			
			if ($request->hasFile('thumbnail'))
			{
				$image = $request->file('thumbnail');
				$filename  = $newslug . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/blog/categories/'), $filename);
				$id = DB::table('blog_categories')->insertGetId(
				['name' => $catname, 'slug' => $newslug, 'parent'=> ($catparent == '-1') ? 0 : $catparent, 'image'=>'assets/uploads/blog/categories/'.$filename, 'description' => $catDescription, 'visible_status'=>$catStatus, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]
			);
			}else{
				$id = DB::table('blog_categories')->insertGetId(
				['name' => $catname, 'slug' => $newslug, 'parent'=> ($catparent == '-1') ? 0 : $catparent, 'description' => $catDescription, 'visible_status'=>$catStatus, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]
			);
			}
			
			
			
			
			if($id){
				Session::flash('message', "Category created successfully.");
				return Redirect::back();
			}else{
				Session::flash('error', "Category creation fails. Please try again after some time.");
				return Redirect::back();
			}	
	}
	
	
	//EDIT BLOG CATEGORIES
	public function editBlogCategories($id)
    {
        $editCategory   = BlogCategories::find($id);
		if(!empty($editCategory)){
			$categories = BlogCategories::where('parent', '=', 0)->get();
        	return view('admin.blog.edit-blog-categories',compact('categories','editCategory'));
		}else{
			return redirect('admin/blogs/categories');
		}
    }
	
	
	//UPDATE BLOG CATEGORIES
	public function updateBlogCategories($id, Request $request){
		 $editCategory   = BlogCategories::find($id);
		 if(!empty($editCategory)){
			 $validator = Validator::make($request->all(),[
    			 'catname' 		=> 'required|min:3',
				 'thumbnail' 	=> 'image|mimes:jpeg,png,jpg,gif,svg|max:5000',
				 'slug'			=> 'unique:blog_categories,slug,'.$id
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/blogs/categories/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			
			$catname 			= $request->input('catname');
			$catslug 			= $request->input('slug');
			$catparent 			= $request->input('parent');
			$catDescription 	= $request->input('description');
			$catStatus 			= $request->input('status');
			
						
			if($catslug == ''){
				$catslug = str_slug($catname, '-');
				$slugClass = new Slug();
    			$catslug = $slugClass->make($catslug, 'blog_categories', 'slug');
			}
			
			if ($request->hasFile('thumbnail'))
			{
				$image = $request->file('thumbnail');
				$filename  = $catslug . '.' . $image->getClientOriginalExtension();
				$image->move(public_path('assets/uploads/blog/categories/'), $filename);
				DB::table('blog_categories')
            		->where('id',$id)
            		->update(['name' => $catname, 'slug' => $catslug, 'parent'=> ($catparent == '-1') ? 0 : $catparent, 'image'=>'assets/uploads/blog/categories/'.$filename, 'description' => $catDescription, 'visible_status'=>$catStatus, 'updated_at'=>date('Y-m-d H:i:s')]);
			}else{
				DB::table('blog_categories')
            		->where('id',$id)
            		->update(['name' => $catname, 'slug' => $catslug, 'parent'=> ($catparent == '-1') ? 0 : $catparent, 'description' => $catDescription, 'visible_status'=>$catStatus, 'updated_at'=>date('Y-m-d H:i:s')]);
			}
			
			
			
			Session::flash('message', "Category updated successfully.");
				return redirect('admin/blogs/categories');
			
		 }else{
			 return redirect('admin/blogs/categories');
		 }
		
	}
	
	public function deleteBlogCategories($id)
    {
        $delCategory   = BlogCategories::find($id);
		if(!empty($delCategory)){
			if($delCategory->{'parent'} == 0){
				
				DB::table('blog_categories')->where('parent', '=', $id)->delete();
			}
			DB::table('blog_categories')->where('id', '=', $id)->delete();
			unlink(public_path($delCategory->image));
			Session::flash('message', "Category deleted successfully.");
				return redirect('admin/blogs/categories');
		}else{
			return redirect('admin/blogs/categories');
		}
    }
	
	//LIST ALL COMMENTS
	public function commentsList(){
		$blogComments = BlogComments::orderBy('id', 'desc')->get();
		return view('admin.blog.blog-comments',compact('blogComments'));
	}
	
	//EDIT COMMENT FORM
	public function editCommentList($id){
		$editblogComment  = BlogComments::find($id);
		if(!empty($editblogComment)){
			return view('admin.blog.edit-blog-comment',compact('editblogComment'));
		}else{
			return redirect('admin/blogs/comments-list');
		}
		
	}
	
	//UPDATE BLOG COMMENT
	public function updateCommentList($id, Request $request){
		$editblogComment  = BlogComments::find($id);
		if(!empty($editblogComment)){
			 $validator = Validator::make($request->all(),[
			 	 'name'			=> 'required',
				 'email'		=> 'required|email',
    			 'comment' 		=> 'required'
    		]);	
			
			if ($validator->fails()) {
            		return redirect('admin/blogs/comments-list/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
        	}
			$editblogComment->name			= $request->input('name');
			$editblogComment->email			= $request->input('email');
			$editblogComment->website		= $request->input('website');
			$editblogComment->mobile		= $request->input('mobile');
			$editblogComment->status		= $request->input('status');
			$editblogComment->comment		= $request->input('comment');
			$editblogComment->updated_at 	= date('Y-m-d H:i:s');
			
			if($editblogComment->save()){
				
				Session::flash('message', "Comment updated successfully.");
				return redirect('admin/blogs/comments-list');
			}else{
				Session::flash('error', "Comment updation fails. Please try again after some time.");
				return redirect('admin/blogs/comments-list');
			}
		}else{
			return redirect('admin/blogs/comments-list');
		}
	}
	
	//DELETE COMMENT
	public function deleteCommentList($id){
		$delCommentList   = BlogComments::find($id);
		if(!empty($delCommentList)){
				$delCommentList->delete();
				Session::flash('message', "Comment deleted successfully.");
				return redirect('admin/blogs/comments-list');
		}else{
				return redirect('admin/blogs/comments-list');
		}
	}

    // List Blog Tags View

	public function tagsList(){
        $tags = BlogTags::orderBy('id', 'desc')->get();
        return view('admin.blog.tags', compact('tags'));
    }

    // Add Blog Tags View

    public function addTags(){
        return view('admin.blog.addTag');
    }

    // Save Blog Tags

    public function saveTags(Request $request){

        $validator = Validator::make($request->all(),[
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/blogs/tags/add')
                ->withErrors($validator)
                ->withInput();
        }
        $title				= $request->input('title');
        $description		= $request->input('description');
        $status 			= $request->input('status');


        $tagslug = str_slug($title, '-');


        $tag  = new BlogTags();

        $tag->name 				= $title;
        $tag->description 		= $description;
        $tag->slug              = $tagslug;
        $tag->status			= $status;

        $tag->created_at 	= date('Y-m-d H:i:s');
        $tag->updated_at 	= date('Y-m-d H:i:s');

        if($tag->save()){
            Session::flash('message', "Tag added successfully.");
            return redirect('admin/blogs/tags/add');
        }else{
            Session::flash('error', "Tag creation fails. Please try again after some time.");
            return redirect('admin/blogs/tags/add');
        }
    }

    // Edit Blog Tags

    public function editTags($id){
        $editTag  = BlogTags::find($id);
        if(!empty($editTag)){
            return view('admin.blog.editTag', compact('editTag'));
        }else{
            return view('admin.blog.editTag');
        }

    }

    // Update Blog Tags
    public function updateTags($id, Request $request){
        $editTag  = BlogTags::find($id);
        if(!empty($editTag)){
            $validator = Validator::make($request->all(),[
                'title' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('admin/blogs/tags/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $title				= $request->input('title');
            $description		= $request->input('description');
            $status 			= $request->input('status');

            $tagslug = str_slug($title, '-');

            $editTag->name 				= $title;
            $editTag->description 		= $description;
            $editTag->slug                  = $tagslug;
            $editTag->status			= $status;

            $editTag->updated_at 	= date('Y-m-d H:i:s');

            if($editTag->save()){
                Session::flash('message', "Tag updated successfully.");
                return redirect('admin/blogs/tags');
            }else{
                Session::flash('error', "Tag updation fails. Please try again after some time.");
                return redirect('admin/blogs/tags');
            }
        }else{
            return redirect('admin/blogs/tags');
        }
    }

    //DELETE BLOG TAG

    public function deleteTags($id){
        $deleteProductTag   = BlogTags::find($id);
        if(!empty($deleteProductTag)){
            $deleteProductTag->delete();
            Session::flash('message', "Blog Tag deleted successfully.");
            return redirect('admin/blogs/tags');
        }else{
            return redirect('admin/blogs/tags');
        }
    }
}
