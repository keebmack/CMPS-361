<html>
    <head>
        <title>My first Php page</title>
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                text-align: center;
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <!-- add Php to the body -->
         <?php
            $name = "Ryan";
            echo $name;

            $string = "RandomName";
            $int = 123456;
            echo $string;
            echo $int;
         ?>
    </body>
</html>