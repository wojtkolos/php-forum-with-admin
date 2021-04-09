<section class="user-info">
<?php

    if($_SESSION['privilege'] == 'admin'){ ?>
        
        <p class="user"><a href="?cmd=userlist">Lista uczestników</a>Zalogowany jako: <?=$_SESSION['userid']?> (<?=$_SESSION['nickname']?>) <a href="?cmd=logout" >WYLOGUJ</a></p>
    <?php } else{ ?>
        <p style="text-align: right;">Zalogowany jako: <?=$_SESSION['userid']?> (<?=$_SESSION['nickname']?>) <a href="?cmd=logout" >WYLOGUJ</a></p>
        <?php
    }
            if(isset($_GET['cmd']) && $_GET['cmd'] == 'userlist'){
                if($_SESSION['privilege'] == 'user')
                {
                    header("Location: index.php");
                } else 
                {
                    ?>
                    <table><tr><th>Identyfikator</th><th>Nazwa</th><th>Poziom</th><th></th></tr>
                    <tr>
                        <?php
                            $users = get_users();
                            foreach($users as $user)
                            {?>
                                <tr>
                                <td><?=$user['userid']; ?></td>
                                <td><?=$user['nickname']; ?></td>
                                <td><?=$user['privilege']; ?></td>
                                <td>
                                    <?php if($user['userid'] != 'admin'){ ?>
                                    <a href="?cmd=changeuser&userid=<?=$user['userid']; ?>">Zmień</a>&nbsp;
                                    <a class="danger" href="?cmd=deluser&userid=<?=$user['userid']; ?>">Kasuj</a>
                                    <?php } ?>
                                </td>
                                </tr>
                            <?php } ?>
                    </tr></table>
                <?php  
                }
            }
        
    ?></section>