<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    this is index page


    <form action="/post" method="post">
        <input type="text" placeholder="text" name="text">
        <input type="submit" value="Отправить">
    </form>
    <?php var_dump($users); ?>
    <div>
        this is text:<?php if (isset($_SESSION['message'])) {
                            echo $_SESSION['message'];
                        } ?>
    </div>
</body>

</html>