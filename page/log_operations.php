<?php
function get_login_page()
{ ?>
<pre><code></code></pre>    
<section id="login">

    <form action="index.php" method="post">
     <a name="login_form"></a>
     <header><h2>Zaloguj się do forum</h2></header>  
     <input class="formBlock" type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input class="formBlock" type="password" name="pass" placeholder="Hasło" \><br />
     <div class="centered"><button name="Login" type="submit">Zaloguj się</button></div>
  </form>
  <pre><code></code></pre>  
  <form action="index.php" method="post">
     <a name="newuser_form"></a>
     <header><h2>Jesli nie jesteś zarejestrowany, to możesz zapisać się do forum.</h2></header>  
     <input class="formBlock" type="text" name="userid" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input class="formBlock" type="text" name="username" placeholder="Imię autora" \><br />
     <input class="formBlock" type="password" name="pass1" placeholder="Hasło" \><br />
     <input class="formBlock" type="password" name="pass2" placeholder="Powtórz hasło" \><br />
     <div class="centered"><button name="Register" type="submit">Zapisz się do forum</button></div>
  </form>

</section>  
<?php  
}

function get_users($datafile="txtFiles/users.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $users=array();
      foreach($data as $k=>$v)
      {
        $record = explode($separator, trim($v));
        $users[]=array( 
            "userid"  => hex2bin($record[0]),
            "nickname"   => hex2bin($record[1]),
            "pass"     => $record[2],
            "privilege"   => hex2bin($record[3])
        );
      }
      return $users;   
   } else
   {
      return FALSE;
   }
}

function get_user($userid, $datafile="txtFiles/users.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
        foreach($data as $k=>$v)
        {
            $record = explode($separator, trim($v));
            if($record[0] == bin2hex($userid))
            {
                $user=array( 
                    "userid"  => hex2bin($record[0]),
                    "nickname"   => hex2bin($record[1]),
                    "pass"     => $record[2],
                    "privilege"   => hex2bin($record[3])
                );
                return $user;
            }
            
        }   
    }
    return FALSE;
}

//------------------------------------------------------------------------------
function put_user($userid, $nickname, $pass, $prev = "user", $datafile="txtFiles/users.txt", $separator=":-:")
{
   if(is_file($datafile))
   {
      $data=file($datafile); 
   }
   $data = implode($separator, 
                     array( bin2hex($userid),
                            bin2hex($nickname), 
                            md5($pass),
                            bin2hex($prev)
                    )
                  );
   if( $fh = fopen( $datafile, "a+" ))
   {
      fwrite($fh, $data."\n");
      fclose($fh);
      return $userid;
   } else
   {
      return FALSE;
   }                              
}

function user_exists($userid, $datafile="txtFiles/users.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $post=array();
      foreach($data as $k=>$v)
      {
          $record = explode( $separator, trim($v));
          if($record[0] == bin2hex($userid))
          {
            return TRUE;
          }
      }
   }
    return FALSE;
}

function user_pass_check($userid, $pass, $datafile="txtFiles/users.txt", $separator=":-:")
{
   if($data=file($datafile))
   {
      $post=array();
      foreach($data as $k=>$v)
      {
          $record = explode( $separator, trim($v));
          if($record[0] == bin2hex($userid) && $record[2] == md5($pass))
          {
            return TRUE;
          }
      }
   }
    return FALSE;
}

function set_sesion($userid, $datafile="txtFiles/users.txt", $separator=":-:")
{
    $user = get_user($userid);

    $_SESSION['logged']=TRUE;
    $_SESSION['userid']=$user['userid'];
    $_SESSION['nickname']=$user['nickname'];
    $_SESSION['privilege']=$user['privilege'];
}

function deleteUsr($userid, $datafile="users.txt", $separator=":-:")
{
    if($data=file($datafile))
    {
       $users = array();
        foreach($data as $k=>$v)
        {
            $record = explode($separator, trim($v));
            if($record[0] == bin2hex($userid))
            {  
            } else
            {
                $users[]=array( 
                    "userid"      => $record[0],
                    "nickname"    => $record[1],
                    "pass"        => $record[2],
                    "privilege"   => $record[3]
                );
            }
            
        }  
        $data = '';
        for($i=0; $i < count($users); $i++ )
      {
            $data .= implode($separator, $users[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return TRUE;
      }
    }
    return FALSE;
}

function changePerm($userid, $datafile="txtFiles/users.txt", $separator=":-:")
{
    $user = get_user($userid);
    if($user['privilege'] == "admin")
    {
        $user['privilege'] = "user";
    } else
    {
        $user['privilege'] = "admin";
    }

    if($data=file($datafile))
    {
       $users = array();
        foreach($data as $k=>$v)
        {
            $record = explode($separator, trim($v));
            if($record[0] == bin2hex($userid))
            {  
                $users[]=array( 
                    "userid"     => bin2hex($user['userid']),
                    "nickname"   => bin2hex($user['nickname']),
                    "pass"       => $user['pass'],
                    "privilege"  => bin2hex($user['privilege'])
                );
            }
            else
            {
                $users[]=array( 
                    "userid"     => $record[0],
                    "nickname"   => $record[1],
                    "pass"       => $record[2],
                    "privilege"  => $record[3]
                );
            }
            
        }  
        $data = '';
        for($i=0; $i < count($users); $i++ )
      {
            $data .= implode($separator, $users[$i]);
            $data .= "\n";
      }
      if( $fh = fopen( $datafile, "w+" ))
      {
            fwrite($fh, $data);
            fclose($fh);
            return TRUE;
      }
    }
    return FALSE;

}


?>
