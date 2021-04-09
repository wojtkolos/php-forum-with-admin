<?php 

function get_post($topicid, $postid, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $post=array();
      foreach($data as $k=>$v)
      {
          $record = explode( $separator, trim($v));
          if($record[1]==$postid && $record[0]==$topicid){
              $post=array( 
                 "topicid"       => $record[0],
                 "postid"        => $record[1],
                 "post"          => hex2bin($record[2]),
                 "userid"        => hex2bin($record[3]),
                 "nickname"      => hex2bin($record[4]),
                 "date"          => $record[5]
              );
          }
      }
      return $post;   
   } else
   {
      return FALSE;
   }
}

//------------------------------------------------------------------------------

function get_posts($topicid, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $posts=array();
      foreach($data as $k=>$v)
      {
          $record = explode($separator, trim($v));
          if( $record[0]==$topicid ){
              $posts[]=array( 
                 "topicid"  => $record[0],
                 "postid"   => $record[1],
                 "post"     => hex2bin($record[2]),
                 "userid"   => hex2bin($record[3]),
                 "nickname" => hex2bin($record[4]),
                 "date"     => $record[5]
              );
          }
      }
      return $posts;   
   } else
   {
      return FALSE;
   }
}

//------------------------------------------------------------------------------
function put_post($topicid, $post, $userid, $nickname, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if(is_file($datafile))
   {
      $postid = count_posts($topicid) + 1; 
      $data=file($datafile );
      /*
      if(!empty($data)){
         $record = explode( $separator, trim(array_pop($data))); 
         $postid = (int)$record[1]+1;
      }else
      {
         $postid = 1; 
      } */  
   }
   $data = implode($separator, 
                     array( $topicid,
                            $postid, 
                            bin2hex($post), 
                            bin2hex($userid), 
                            bin2hex($nickname), 
                            date("Y-m-d H:i:s") 
                    )
                  );
   if( $fh = fopen( $datafile, "a+" )){
      fwrite($fh, $data."\n");
      fclose($fh);
      return $postid;
   } else
   {
      return FALSE;
   }                              
}

//------------------------------------------------------------------------------
function get_topic($topicid, $datafile="txtFiles/topics.txt", $separator=":-:")
{
   if($data=file($datafile ))
   {
      foreach($data as $k=>$v)
      {
         $record = explode($separator, trim($v));
         if($record[0]== $topicid)
         {
            $topic=array( 
               "topicid"     => $record[0],
               "topic"       => hex2bin($record[1]),
               "description" => hex2bin($record[2]),
               "userid"      => hex2bin($record[3]),
               "nickname"    => hex2bin($record[4]),
               "date"        => $record[5]
            );
            return $topic;
         }
      } 
      return FALSE;
   }
}

//------------------------------------------------------------------------------
function get_topics($datafile="txtFiles/topics.txt", $separator=":-:")
{
   if($data=file($datafile ))
   {
      $topics=array();
      foreach($data as $k=>$v)
      {
         $record = explode($separator, trim($v));
         if($record[0]== true)
         {
            $topics[]=array( 
               "topicid"     => $record[0],
               "topic"       => hex2bin($record[1]),
               "description" => hex2bin($record[2]),
               "userid"      => hex2bin($record[3]),
               "nickname"    => hex2bin($record[4]),
               "date"        => $record[5]
            );
         }
      } 
      return $topics;  
   }
}
//------------------------------------------------------------------------------
function put_topic($topic, $description, $userid, $nickname, $datafile="txtFiles/topics.txt", $separator=":-:")
{
   if(is_file($datafile))
   {
   $data=file($datafile);
   $record = explode( $separator, trim(array_pop($data))); 
   $topicid = (int)$record[0]+1;
   } else
   {
      $topicid = 1;
   }
   
   $data = implode( $separator, 
                     array( $topicid, 
                           bin2hex($topic),
                           bin2hex($description), 
                           bin2hex($userid), 
                           bin2hex($nickname), 
                           date("Y-m-d H:i:s")
                     )
                  ); 
   if( $fh = fopen( $datafile, "a+" ))
   {
      fwrite($fh, $data."\n");
      fclose($fh);
      return $topicid;
   } else
   {
      return FALSE;
   };                               
}
//------------------------------------------------------------------------------
function count_posts($topicid, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   $counter = 0;
   if($data=file($datafile))
   {
      foreach($data as $k=>$v){
         $record = explode($separator, trim($v));
         if( $record[0]== $topicid){
            $counter++;
         }
      } 
      return $counter;  
   }
   return 0;
}

//------------------------------------------------------------------------------
function last_post($datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   $check = false;
   if( $data=file($datafile))
   {
      $post = array();
      $size = count($data);
      $record = explode($separator, $data[$size - 1]);
      if(!empty($record[5]))  
         return $record[5];
   }
   return "::brak-postów::";
}

//------------------------------------------------------------------------------
function edit_post($topicid, $postid, $description, $userid, $nickname, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $posts=array();
      foreach($data as $k=>$v)
      {
            $record = explode($separator, trim($v));
            if($record[1] == $postid && $record[0] == $topicid && $record[3] == bin2hex($userid))
            {
               $posts[]=array(
                  "topicid" => $record[0],
                  "postid" => $record[1],
                  "post" => bin2hex(trim($description)),
                  "userid"=> bin2hex(trim($userid)),
                  "nickname"=> bin2hex(trim($nickname)),
                  "date" => date("Y-m-d H:i:s")
               );
            } else
            {
               $posts[]=array(
                  "topicid" => $record[0],
                  "postid" => $record[1],
                  "post" => $record[2],
                  "userid"=> $record[3],
                  "nickname"=> $record[4],
                  "date" => $record[5]
               );
            }
      }
      $data = '';
      for($i=0; $i < count($posts); $i++ )
      {
            $data .= implode($separator, $posts[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return $postid;
      } else
      {
            return FALSE;
      }
      return $postid; 
   } else
   {
      return FALSE;
   }
}
//------------------------------------------------------------------------------
function delete_post($topicid, $postid, $userid, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if($data=file( $datafile))
   {
      $posts=array();
      foreach($data as $k=>$v)
      {
            $record = explode($separator, trim($v));
            if($record[1] === $postid && $record[0] == $topicid && $record[3] == bin2hex($userid))
            {

            } else
            {
               $posts[]=array(
                  "topicid" => $record[0],
                  "postid" => $record[1],
                  "post" => $record[2],
                  "userid"=> $record[3],
                  "nickname"=> $record[4],
                  "date" => $record[5]
               );
            }
      }
      $data = '';
      for($i=0; $i < count($posts); $i++ )
      {
            $data .= implode($separator, $posts[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return $postid;
      } else
      {
            return FALSE;
      };
      
      return $postid; 
   } else
   {
      return FALSE;
   }
}

//------------------------------------------------------------------------------
function delete_posts($topicid, $datafile="txtFiles/wypowiedzi.txt", $separator=":-:")
{
   if($data=file( $datafile))
   {
      $posts=array();
      foreach($data as $k=>$v)
      {
            $record = explode($separator, trim($v));
            if($record[0] == $topicid)
            {
            } else
            {
               $posts[]=array(
                  "topicid" => $record[0],
                  "postid" => $record[1],
                  "post" => $record[2],
                  "userid"=> $record[3],
                  "nickname"=> $record[4],
                  "date" => $record[5]
               );
            }
      }
      $data = '';
      for($i=0; $i < count($posts); $i++ )
      {
            $data .= implode($separator, $posts[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return $postid;
      } else
      {
            return FALSE;
      };
      
      return $postid; 
   } else
   {
      return FALSE;
   }
}

//------------------------------------------------------------------------------
function edit_topic($topicid, $topic, $description, $userid, $nickname, $datafile="txtFiles/topics.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $topics=array();
      foreach($data as $k=>$v)
      {
            $record = explode($separator, trim($v));
            if($record[0] == $topicid)
            {
               $topics[]=array(
                  "topicid"     => $record[0],
                  "topic"       => bin2hex($topic),
                  "description" => bin2hex($description),
                  "userid"      => bin2hex($userid),
                  "nickname"    => bin2hex($nickname),
                  "date"        => date("Y-m-d H:i:s")
               );
            } else
            {
               $topics[]=array(
                  "topicid"     => $record[0],
                  "topic"       => $record[1],
                  "description" => $record[2],
                  "userid"      => $record[3],
                  "nickname"    => $record[4],
                  "date"        => $record[5]
               );
            }
      }
   $data = '';
   for($i=0; $i < count($topics); $i++ )
   {
         $data .= implode($separator, $topics[$i]);
         $data .= "\n";
   }
   if( $fh = fopen( $datafile, "w+" ))
   {
         fwrite($fh, $data);
         fclose($fh);
         return $topicid;
   } else
   {
         return FALSE;
   }
      return $topicid; 
   }
   return FALSE;
}
//------------------------------------------------------------------------------
function delete_topic($topicid, $datafile="txtFiles/topics.txt", $separator=":-:")
{
   delete_posts($topicid);
   if($data=file( $datafile))
   {
      $topics=array();
      foreach($data as $k=>$v)
      {
            $record = explode($separator, trim($v));
            if($record[0] == $topicid)
            {

            } else
            {
               $topics[]=array(
                  "topicid"     => $record[0],
                  "topic"       => $record[1],
                  "description" => $record[2],
                  "userid"      => $record[3],
                  "nickname"    => $record[4],
                  "date"        => $record[5]
               );
            }
      }
      $data = '';
      for($i=0; $i < count($topics); $i++ )
      {
            $data .= implode($separator, $topics[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return TRUE;
      } 
   } else
   {
      return FALSE;
   }
}
?>