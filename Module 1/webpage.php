<html>
    <head>
        <title>My First PHP Page</title>
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                text-align: center;
                margin-top: 50px;
            }
            h1 {
                color: #0056b3;
            }
            .section {
                margin: 20px auto;
                max-width: 600px;
                padding: 15px;
                background-color: #ffffff;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            }
            img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <?php
            // Variables
            $name = "Ryan Kebe";
            $hobbies = ["Video Games", "Computers", "Pittsburgh Sports"];
            $interests = ["Technology", "Steelers", "Penguins", "Pirates"];
        ?>

        <div class="section">
            <h1>Welcome to <?php echo $name; ?>'s Webpage!</h1>
            <p>Hello! My name is <?php echo $name; ?>. Here is a little about my hobbies and interests.</p>
        </div>

        <div class="section">
            <h2>About Me</h2>
            <p>
                Hi, I'm <?php echo $name; ?>! I'm passionate about technology, gaming, and Pittsburgh sports. In my free time, I love to explore new tech trends, play video games, and be a yinzer!
                I currently live in Cranberry Twp and work as a Broadband Technician for Armstrong.  This is my final semester at Point Park and I look forward to finishing strong!
            </p>
        </div>

        <div class="section">
            <h2>Photos</h2>
            <img src="photos/Rico.jpg" alt="My dog, Rico">
        </div>

        <div class="section">
            <h2>My Hobbies</h2>
            <ul>
                <?php
                    foreach ($hobbies as $hobby) {
                        echo "<li>$hobby</li>";
                    }
                ?>
            </ul>
        </div>

        <div class="section">
            <h2>My Interests</h2>
            <ul>
                <?php
                    foreach ($interests as $interest) {
                        echo "<li>$interest</li>";
                    }
                ?>
            </ul>
        </div>
    </body>
</html>