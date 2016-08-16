<?php echo '' ?>
 '<!DOCTYPE html>
<html lang="en">

<head>
    <title>Help Me! Class Queue</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/style/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Help me! (DEV)</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <?php
                $pages = array("Home"=>'index.php',
                "Student"=>'student.php', 
                "Tutor"=>'tutor.php', 
                "Help"=>'help.php', 
                "About"=>'about.php');
                foreach($pages as $page => $pagel)
                {
                    $activetext  = '';
                    if ($currpage == $page)
                    {
                        $activetext = 'class="active " ';
                        if (!isset($title))
                        {
                            $title = $page;
                        }
                    }
                    echo('<li '.$activetext.'><a href="/'.$pagel.'">'.$page.'</a></li>');
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right ">
                <li><a href="/register.php"><span class="glyphicon glyphicon-user "></span> Sign Up</a></li>
                <li><a href="/login.php"><span class="glyphicon glyphicon-log-in "></span> Login</a></li>
            </ul>
            </div>
        </div>
        </h1>
    </nav>
    <div class="container">
        <div class="col-sm-12 well">
            <?php
            if (isset($title)) 
            {
                echo '<h1>'.$title.'</h1>';
            }
            ?>
        </div>
