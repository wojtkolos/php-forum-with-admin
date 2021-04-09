<?php

if(isset($_POST['AddPost']))
{
    $description = nl2br(htmlspecialchars($_POST['topic_body']));
}
if(empty($description) == true)
{
    $err = 'Uzupełnij pole!';
} else if($_POST['postcmd'] == 'editPost')
{
    $postid = $_POST['pid'];
    edit_post($topicid, $postid, $description, $_SESSION['userid'], $_SESSION['nickname']);
} else
{
    $err = '';
    put_post($topicid, $description, $_SESSION['userid'], $_SESSION['nickname']);
}
$topics = get_topics();
$posts = get_posts($topicid);
?>

<nav class="nav">
    <ul class="menu1">
        <?php             
            if($topicid - 1 > 0)
            {?>
                <li><a href="index.php?topic=<?=$topicid - 1?>"><-- Poprzedni temat</a></li>
            <?php 
            }else
            {?>
                <li><a href="/" onclick="return false;"><-- Poprzedni temat</a></li>
      <?php } ?>
        <li><a href="index.php">Lista tematów</a></li>
        <?php
            $check = false;
            foreach($topics as $topic)
            { 
                if($topic['topicid'] == $topicid + 1)      
                {
                    $check = true;?>
                    <li><a href="index.php?topic=<?=$topicid + 1?>">Następny temat --></a></li>
                <?php 
                } 
            }
            if($check==false)
            {?>
                <li><a href="/" onclick="return false;">Następny temat --></a></li>
      <?php } ?>
            
    </ul>
</nav>


<?php 
    include("user_info.php");

    if(!empty($topics)){
        foreach($topics as $topic)
        { 
            if($topic['topicid'] == $_GET['topic']){
                ?>
                <article class="topic">
                    <header>Temat dyskusji: <b><?=$topic['topic']?></b></header>
                    <p><?=$topic['description']?></p>
                    <footer>
                        ID: <?=$topic['topicid']?>,
                        Autor: <?=$topic['nickname']?>,
                        Data: <?=$topic['date']?>, 
                    </footer>
                </article><?php 
            }
        }
    }
?>
<p>Możesz dodac nowy komentarz za pomocą <a href="#topic_form">formularza</a>.</p>

<?php 
    if(!empty($posts)){
        foreach($posts as $opost)
        { ?>
            <article class="topic">
                <header><br></header>
                <p><?=$opost['post']?></p>
                <footer>
                    <nav>
                        <?php
                            if($opost['userid'] == $_SESSION['userid'] or $_SESSION['privilege'] == 'admin')
                            { ?>
                                <a href="?topic=<?=$opost['topicid']?>&id=<?=$opost['postid']?>&cmd=editPost">EDYTUJ</a>  
                                <a class="danger" href="?topic=<?=$opost['topicid']?>&id=<?=$opost['postid']?>&cmd=delPost">KASUJ</a>
                            <?php
                            } ?>
                    </nav> 
                    ID: <?=$opost['postid']?>,
                    Autor: <?=$opost['nickname']?>,
                    Data: <?=$opost['date']?>
                </footer>
            </article><?php 
        }
    }
?>

<form action="index.php?topic=<?=$topicid?>" method="post">
    <a name="post_form"></a>
        <header><h2>Dodaj nowa wypowiedź do dyskusji</h2></header>  
        <textarea class="formBlock" name="topic_body" cols="80" rows="10" placeholder="Wpisz tu swoją wypowiedź." ><?=$descr_val; ?></textarea><br />
        <input class="formBlock" type="hidden" name="postcmd" value="<?php
                                                                        if(isset($_GET['cmd']))
                                                                        {
                                                                            echo $_GET['cmd'];
                                                                        } else echo '';
                                                                    ?>" />
        <input class="formBlock" type="hidden" name="pid" value="<?php
                                                                        if(isset($_GET['id']))
                                                                        {
                                                                            echo $_GET['id'];
                                                                        } else echo '';
                                                                    ?>" />                                                            
        <div class="centered"><button name="AddPost" type="submit" >Zapisz</button></div>
</form>
