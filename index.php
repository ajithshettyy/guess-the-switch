<?php

require_once('logic.php');

?>

<html>
    <head>
          <title>Guess-the-switch</title>
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
          <link rel="stylesheet" href="style.css">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <script src="https://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> 
    </head>
    <body class="container">
        <div>
        <div class="title text-center">
            <h2>GUESS THE SWITCH</h2>
        </div>
        <div class="bulbs">
            <div id="light-bulb"></div>
            <div id="light-bulb2" style="opacity: 0; "></div>
        </div>
        <div class="scoreboard text-center">
            Current Level: <span id="mylevel"><?php echo $_SESSION['level']; ?></span>
            Your Score: <span id="myscore"><?php echo $_SESSION['score']; ?></span>
        </div>
        <div>
        <?php for($i = 0; $i< $switches; $i++){ ?>
            <div class="cube-switch" onclick="clickswitch(this,<?php echo $i ?>)">
                <span class="switch">
                            <span class="switch-state off">Off</span>
                <span class="switch-state on">On</span>
                </span>
            </div>
        <?php } ?>
        
        </div>
        <div id="gameover" style="display:none;cursor: pointer;" onclick="javascript:window.location.reload();">
            <span style="color: #f31515;">Game Over </span>
            <a href=""><span class=reload>&#x21bb;</span></a>
        </div>
        </div>
        <div class="gitlink">
            <a href="https://github.com/ajithbshetty" target="_blank"><i class="fa fa-github" style="font-size:48px;color:red"></i>Source Code</a>
        <div>
    </body>
    <script>
        function clickswitch(ele, guessVal){
            if ($(ele).hasClass('active')) {
                $(ele).removeClass('active');
            } 
            else {
                $(ele).addClass('active');
                $.ajax({
                    type: "POST",
                    url: "logic.php",
                    data: {guess:guessVal},
                    success: function(data){
                        var res = JSON.parse(data);
                        if(res.gamestatus == 0){
                            $("#gameover").show();
                        }
                        if(res.status == "success"){
                            $('#light-bulb').css({
                                'opacity': '0'
                            });
                            $('#light-bulb2').css({
                                'opacity': '1'
                            });
                            setTimeout(function() {
                                alert("Level Completed..");
                                location.href = "";
                            }, 1000);
                        }
                        $("#myscore").text(res.score);
                        $("#mylevel").text(res.level);
                    },
                    error: function(){
                        alert("Something went wrong.!!");
                    }
                });
            }
        }
    </script>
</html>