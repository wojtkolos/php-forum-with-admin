<?php 
if(isset($_POST['Login']))
{
    $userid = isset($_POST['userid']) ? htmlspecialchars($_POST['userid']) : '';
    $password = isset($_POST['pass']) ? htmlspecialchars($_POST['pass']) : '';
    

    if (user_pass_check($userid, $password)){
        set_sesion($userid);

        header("Location: index.php");
        exit;
    } else 
    {
        echo "Niepoprawne dane logowania!";
        $msg="<span style='color:red'>Invalid Login Details</span>";
    }
}

if(isset($_POST['Register']))
{
    $userid = isset($_POST['userid']) ? htmlspecialchars($_POST['userid']) : '';
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
    $password1 = isset($_POST['pass1']) ? htmlspecialchars($_POST['pass1']) : '';
    $password2 = isset($_POST['pass2']) ? htmlspecialchars($_POST['pass2']) : '';

  
    if (!user_exists($userid) && $password1 == $password2)
    {
        put_user($userid, $username, $password1);
        set_sesion($userid);
        
        header("Location: index.php");
        exit;
    } else 
    {
        echo "Użytkownik o takim nicku już instnieje!";
        $msg="<span style='color:red'>Invalid Login Details</span>";
    }
}


?>
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
