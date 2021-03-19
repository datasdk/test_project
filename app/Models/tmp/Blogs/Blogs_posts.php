<?php

    namespace App\Models\Blogs;

    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use App\Models\Cloudinary\Cloudinary;
    use App\Models\Blogs\Blogs_categories as Category;


    Class Blogs_posts extends Model{


        use HasFactory;
        
        
        public static function insert_posts($props) {

            $res = Category::where("name","sko")
            ->first()
            ->getPosts()
            ->get();   
            
            echo "<blog_categories :res='".$res."'></blog_categories>";
    
        }


        public static function insert_categories($props) {

            $res = Category::where("name",$props["name"])->get();
    
            echo "<blog_categories :res='".$res."'></blog_categories>";
    
        }


        public function post(){

            return $this->morphTo();

        }


        public function get_categories(){

            return $this->belongsToMany(Blogs_categories::class,"blogs_privots");
            
        }


/*

        public function scopeFrom($query, $timestamp) {
        
            $timestamp = strtotime($timestamp);
            
            return $query->where('created_at', '>=', date('Y-m-d H:i:s', $timestamp));

        }


        public function scopeOnlyActive() {

            return $this->where('active', 1);

        }

        public static function category($id){

            return Category::find($id);
            
        }
 

        public function limited(){

            return $this->where("active","=","0");

        }
        
*/

    }


?>