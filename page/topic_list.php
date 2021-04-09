<?php


if(isset($_POST['AddTopic']))
{
    $description = nl2br(htmlspecialchars($_POST['topic_body']));
    $title = htmlspecialchars($_POST['topic_name']);
}

if(empty($description) == true || empty($title) == true)
{
    $err = 'Uzupełnij pola!';
} else if($_POST['topiccmd'] == 'editTopic')
{
    $topicid = $_POST['tid'];
    edit_topic($topicid, $title, $description, $_SESSION['userid'], $_SESSION['nickname']);
} else
{
    $err = '';
    put_topic($title, $description, $_SESSION['userid'], $_SESSION['nickname']);
}
$topics = get_topics();
?>
<p>Możesz dodac nowy temat za pomocą <a href="#topic_form">formularza</a>.</p>

<?php 
    include("user_info.php");

    if(!empty($topics)){
        foreach($topics as $topic)
        { ?>
            <article class="topic">
                <header><br></header>
                <p><a href="?topic=<?=$topic['topicid']?>"><?=$topic['topic']?></a></p>
                <footer>
                    <nav>
                        <?php
                            if("admin" == $_SESSION['privilege'])
                            { ?>
                                <a href="?top=<?=$topic['topicid']?>&cmd=editTopic">EDYTUJ</a>  
                                <a class="danger" href="?top=<?=$topic['topicid']?>&cmd=delTopic">KASUJ</a>
                            <?php
                            } ?>
                        </nav> 
                    ID: <?=$topic['topicid']?>,
                    Autor: <?=$topic['nickname']?>,
                    Data: <?=$topic['date']?>,
                    Liczba wpisów: <?=count_posts($topic['topicid'])?>
                </footer>
            </article><?php 
        }
    }
?>
<form action="index.php" method="post">
        <a name="topic_form"></a>
        <header><h2>Dodaj nowy temat do dyskusji</h2></header>  
        <input class="formBlock" type="text" name="topic_name" placeholder='Nowy temat'; <?php 
                                                                            if(isset($_GET['top']))
                                                                            { ?>
                                                                                value = "<?=$topic_val; ?>" <?php  
                                                                            }
                                                                                  ?> autofocus \><br />
        <textarea class="formBlock" name="topic_body" cols="80" rows="10" placeholder="Opis nowego tematu" ><?=$descr_val; ?></textarea><br />
        <input class="formBlock" type="hidden" name="topiccmd" value="<?php
                                                                        if(isset($_GET['cmd']))
                                                                        {
                                                                            echo $_GET['cmd'];
                                                                        } else echo '';
                                                                    ?>" />
        <input class="formBlock" type="hidden" name="tid" value="<?php
                                                                        if(isset($_GET['top']))
                                                                        {
                                                                            echo $_GET['top'];
                                                                        } else echo '';
                                                                    ?>" />                                                             
        <div class="centered"><button name="AddTopic" type="submit" >Zapisz</button></div>
</form>




