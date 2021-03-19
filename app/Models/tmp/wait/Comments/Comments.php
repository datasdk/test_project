<?php

    namespace App\Models\Comments\Comments;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    Class Comments extends Model{


        public static function insert($options = []){


            $comment_ref_id = 0;
            $from = 0;
            $to = 0;


            extract($options);


            if(!$id){ return false; }


            $comment_ref_id = sha1($id);



            if(!is_numeric($from)){ $from = strtotime($from); }

            if(!is_numeric($to)){   $to = strtotime($to); }




            ob_start();

               

                include(__DIR__."/include/comments.php");

            
    
                echo "
                <div 
                class='comment_list comment_list_".$comment_ref_id."'
                data-comment_ref_id='".$comment_ref_id."'
                data-from='".$from."'
                data-to='".$to."'
                >";

                
                //echo Comments::load($id);


                echo "</div>";



            $content = ob_get_contents();

            ob_end_clean();


            return $content;

        }




        public static function post($comment_ref_id,$customer_ref_id,$comment){


         
            $comment = self::clean_text($comment);
            $comment = urlencode($comment);



            $arr = [
                "comment_ref_id"=>$comment_ref_id,
                "customer_ref_id"=>$customer_ref_id,
                "comment"=>$comment,
                "date"=>time(),
                "active"=>1,
            ];



            DB::insert("comments",$arr);


        }



        public static function get($options = []){
            

            $comment_ref_id = 0;
            $from = 0;
            $to = 0;


            extract($options);


   
            if(!is_numeric($from)){   $from = strtotime($from); }
            if(!is_numeric($to)){     $to = strtotime($to); }



            $sql = "
            select * from comments 
            where 
            comment_ref_id = '".$comment_ref_id."'
            and customer_ref_id != 0
            and comment != ''
            ";


            if($from){

              $sql .= " and from >= '".$from."' ";  

            }
            
            
            if($to){
                
                $sql .= " and to <= '".$to."' ";

            }


            $sql .= "order by id desc";


            $r = DB::select($sql);



            foreach($r as $val){


                $comment_ref_id     = $val["comment_ref_id"];
                $parent_id          = $val["parent_id"];
                $customer_ref_id    = $val["customer_ref_id"];
                $comment            = $val["comment"];
                $date               = $val["date"];


                $user               = Format::current( Customer::get($customer_ref_id) );

                $comment            = urldecode($comment);
                $comment            = self::clean_text($comment);


                if(empty($comment)){ continue; }
                

                echo "
                <div class='comment-item ' 
                data-comment_ref_id='".$comment_ref_id."'
                data-customer_ref_id='".$customer_ref_id."'
                data-parent_id='".$parent_id."'
                >";
                
                    $img = Cloudi::format($user["image"],100,100);

                    
                    if($img){

                        echo "<img src='".$img."'>";

                    }
                    


                    echo "<div class='comment-content'>";


                        echo "<div class='comment-date'>".date("d/m y H:i",$date)."</div>";

                        echo "<strong class='comment-username'>".$user["firstname"]."</strong>";
                        

                        if(!empty($comment)){

                            echo "<div class='comment'>".$comment."</div>";

                        }



                    echo "</div>";
                    
                echo "</div>";



            }

        }




        public static function clean_text($comment) {


            $comment = str_replace("  "," ",$comment);
            $comment = str_replace("\\n"," ",$comment);
            $comment = str_replace("\\r"," ",$comment);
            $comment = strip_tags($comment);
            $comment = DB::escape($comment);
            $comment = substr($comment,0,500);


            $do = true;

            while ($do) {
                $start = stripos($comment,'<script');
                $stop = stripos($comment,'</script>');
                if ((is_numeric($start))&&(is_numeric($stop))) {
                    $comment = substr($comment,0,$start).substr($comment,($stop+strlen('</script>')));
                } else {
                    $do = false;
                }
            }

            return trim($comment);


        }



    }

?>