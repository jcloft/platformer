<!doctype html>
<html>
  
  <head>
      <title>My fancy game</title>
    <style>
      img {
        height: 50px;
        width: 50px;
        visibility: hidden;
        
      }
      #canvas {
        background: url('moon.png') center no-repeat;
        background-size: cover;
      } 
    </style>
    
    <script src='http://code.jquery.com/jquery-2.1.4.min.js'></script>
  </head>
  <body>
    <form action="lobby.php" method="POST">
      <input type='text' name="name" id="username">
<!--      <button type='submit'></button>-->
<!--      <button type='submit' name="start">Start</button>-->
    </form>
    <canvas id="canvas" style="border:1px solid #000"></canvas>
    <img src="mario.png" id="mario">
    <img src="leftmario.png" id="leftmario">
    <img src="bullet.png" id="bullet">
    <img src="rightbullet.png" id="rightbullet">
    <img src="goomba.png" id="goomba">
<!--    -->
    <script>
      // All of our JavaScript will go here.
      
      (function() {
        var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        window.requestAnimationFrame = requestAnimationFrame;
    })();
      
      
        var canvas = document.getElementById("canvas"),
          ctx = canvas.getContext("2d"),
          width = 700,
          height = 400,
          players = [],
//          player = {
//            x : width/2,
//            y : height - 75,
//            width : 40,
//            height : 40,
//            speed: 3,
//            velX: 0,
//            velY: 0,
//            facing: 'right',
//            jumping: false,
//            grounded: false,
//          },
          enemies = []
          
          i = 0;
          
          img = document.getElementById('mario');
          keys = [],
          friction = 0.9,
          gravity = .2;
      var ammo = []
      var mag = {
        i : 0
      }
      var dead = []
      var boxes = []
 
        // dimensions
        boxes.push({
            x: 0,
            y: 0,
            width: 10,
            height: height
        });
        boxes.push({
            x: 0,
            y: height - 75,
            width: width,
            height: 75
        });
        boxes.push({
            x: width - 10,
            y: 0,
            width: 50,
            height: height
        });
        boxes.push({
          x: 0,
          y: height - 150,
          width: 90,
          height: 150
        });
        boxes.push({
          x: 610,
          y: height - 150,
          width: 90,
          height: 150
        });
      
        //platforms
      
        boxes.push({
          x: 25,
          y: 50,
          width: 80,
          height: 15
        });
      
        boxes.push({
        x: 125,
        y: 175,
        width: 80,
        height: 15
        });
      
        boxes.push({
            x: 225,
            y: 100,
            width: 80,
            height: 15
        });
      
        boxes.push({
            x: 225,
            y: 250,
            width: 80,
            height: 15
        });
      
        boxes.push({
            x: 375,
            y: 100,
            width: 80,
            height: 15
        });
      
        boxes.push({
          x: 375,
          y: 250,
          width: 80,
          height: 15
        });
      
        boxes.push({
          x: 475,
          y: 175,
          width: 80,
          height: 15
        });
      
        boxes.push({
          x: 595,
          y: 50,
          width: 80,
          height: 15
        });
      
      enemies.push({
        x: 125,
        y: 140,
        width: 35,
        height: 35
      });
      
//      enemies.push({
//        x: 610,
//        y: 15,
//        width: 35,
//        height: 35
//      });
      
      enemies.push({
        x: 485,
        y: 140,
        width: 35,
        height: 35
      });
      
      enemies.push({
        x: 395,
        y: 215,
        width: 35,
        height: 35
      });
      
      enemies.push({
        x: 250,
        y: 65,
        width: 35,
        height: 35
      });
      

      canvas.width = width;
      canvas.height = height;
      var name = document.getElementById("username").value;
      
      var j = 0
      
      
      
      for (j=0; j < 2 ; j++) {
        
        var player = {
            user: j,
            x : width/2 - (5 * j),
            y : height - 75,
            width : 40,
            height : 40,
            speed: 3,
            velX: 0,
            velY: 0,
            facing: 'right',
            jumping: false,
            grounded: false
      }
        
      players.push(player);
      
      }


     function update() {
       
       var i = mag.i;
       if (player.facing == 'right'){
        var img = document.getElementById('mario');
        var bullets = document.getElementById('rightbullet');
       }
       if (player.facing == 'left'){
         var img = document.getElementById('leftmario');
         var bullets = document.getElementById('bullet');
       }
       
       // check keys
       if (keys[32]) {
         if (player.facing == 'right') {
           var facing = 'right';
                   var bullets = document.getElementById('rightbullet');
           

           
         }
         if (player.facing == 'left') {
           var facing = 'left';
                   var bullets = document.getElementById('rightbullet');

         }
         
         shoot(i, facing);
         mag.i = mag.i+1;
         
       }
         //Space
         
       
       if (keys[38]) {
           // up arrow
         if(!player.jumping && player.grounded){
           player.jumping = true;
           player.grounded = false;
           player.velY = -player.speed*2;
          }
       }
       if (keys[39]) {
           // right arrow
           if (player.velX < player.speed) {                         
               player.velX++;                  
           } 
         player.facing = 'right';
         var img = document.getElementById('mario');
         var bullets = document.getElementById('bullet');
       }          
       if (keys[37]) {                 
            // left arrow                  
           if (player.velX > -player.speed) {
               player.velX--;
           }
         player.facing = 'left';
         var img = document.getElementById('leftmario');
         var bullets = document.getElementById('rightbullet');
       }
       
       player.velX *= friction;
       player.velY += gravity;
       
       
       
       
        ctx.clearRect(0,0,width,height);
       
       ctx.fillStyle = "#696969";
       ctx.beginPath();
       player.grounded = false;
       
       var goomba = document.getElementById('goomba');
       
       for (var i=0; i< enemies.length; i++){
         ctx.drawImage(goomba, enemies[i].x, enemies[i].y, enemies[i].width, enemies[i].height);
        
         var dir = colCheck(player, enemies[i]);
         
         if (dir === "l" || dir === "r") {
             player.velX = 0;
             player.jumping = false;
         } else if (dir === "b") {
             player.grounded = true;
             player.jumping = false;
         } else if (dir === "t") {
             player.velY *= -1;
         }
         
         
       }
       
       for (var i = 0; i < boxes.length; i++) {
        ctx.rect(boxes[i].x, boxes[i].y, boxes[i].width, boxes[i].height);
         
         var dir = colCheck(player, boxes[i]);
         
         if (dir === "l" || dir === "r") {
             player.velX = 0;
             player.jumping = false;
         } else if (dir === "b") {
             player.grounded = true;
             player.jumping = false;
         } else if (dir === "t") {
             player.velY *= -1;
         }
       }
       
       if(player.grounded){
             player.velY = 0;
        }

        player.x += player.velX;
        player.y += player.velY;
  
 
       ctx.fill();
        //var bullets = document.getElementById('bullet');
        ctx.drawImage(bullets, bullet.x, bullet.y, player.width, player.height);
        //ctx.drawImage(bullets, 10, 10);
        ctx.drawImage(img, player.x, player.y, player.width, player.height);
        // run through the loop
       requestAnimationFrame(update);
     }
      
      function shoot(i, direction){
        bullet = {
            //startingX: player.x,
            //startingY: player.y,
            x: player.x,
            y: player.y,
            height: 35,
            width: 35,
            continue: true
          };
         ammo.push(bullet);
        console.log(direction);
          var bullets = document.getElementById('bullet');
          ctx.drawImage(bullets, player.x, player.y, player.width, player.height);
          
        function move(){
          
          setTimeout(function() {
             
            if (direction == 'left') {
              ammo[i].x -= 30;
              ammo[i].x = Math.floor(ammo[i].x);
              ammo[i].y = Math.floor(ammo[i].y);
              console.log(ammo[i].x);
              
              if (ammo[i].x < 500 && ammo[i].x > 450  && ammo[i].y > 120 && ammo[i].y < 160 && enemies[1].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[1]);
                enemies[1].y =500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x > 395 && ammo[i].x < 430 && ammo[i].y > 175 && ammo[i].y < 240 && enemies[2].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[2]);
                enemies[2].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x < 285 && ammo[i].x > 250 && ammo[i].y > 0 && ammo[i].y < 100 && enemies[3].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[3]);
                enemies[3].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x < 100 && ammo[i].x > 70 && ammo[i].y > 0 && ammo[i].y < 150 && enemies[0].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[0]);
                enemies[0].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              

              
            }
            if (direction == 'right'){
              ammo[i].x += 30;
              ammo[i].x = Math.floor(ammo[i].x);
              ammo[i].y = Math.floor(ammo[i].y);
              
//              if (ammo[i].x < 600 && ammo[i].y > 5 && ammo[i].y < 30 && enemies[1].y != 500){
//                dead.push(enemies[1]);
//                enemies[1].y = 500;
//                ammo[i].y = 500;
//                ammo[i].continue = false;
//              }
              
              if (ammo[i].x < 500 && ammo[i].x > 450 && ammo[i].y > 120 && ammo[i].y < 160 && enemies[1].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[1]);
                enemies[1].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x < 400 && ammo[i].x > 350 && ammo[i].y > 175 && ammo[i].y < 240 && enemies[2].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
              
                dead.push(enemies[2]);
                enemies[2].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x < 250 && ammo[i].x > 200 && ammo[i].y > 0 && ammo[i].y < 75 && enemies[3].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[3]);
                enemies[3].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
              if (ammo[i].x < 100 && ammo[i].x > 70 && ammo[i].y > 0 && ammo[i].y < 150 && enemies[0].y != 500){
                //ammo[i].pop(bullet);
                //.push(bullet);
                //ammo[i].x = player.x;
                dead.push(enemies[0]);
                enemies[0].y = 500;
                ammo[i].y = 500;
                ammo[i].continue = false;
              }
              
            }
            
            if (ammo[i].continue == true){
              requestAnimationFrame(move);
            }
              
          }, 50)
        }
        
        move();       
        
      }
      
      function colCheck(shapeA, shapeB) {
        // get the vectors to check against
        var vX = (shapeA.x + (shapeA.width / 2)) - (shapeB.x + (shapeB.width / 2)),
            vY = (shapeA.y + (shapeA.height / 2)) - (shapeB.y + (shapeB.height / 2)),
            // add the half widths and half heights of the objects
            hWidths = (shapeA.width / 2) + (shapeB.width / 2),
            hHeights = (shapeA.height / 2) + (shapeB.height / 2),
            colDir = null;

        // if the x and y vector are less than the half width or half height, they we must be inside the object, causing a collision
        if (Math.abs(vX) < hWidths && Math.abs(vY) < hHeights) {         // figures out on which side we are colliding (top, bottom, left, or right)         
          var oX = hWidths - Math.abs(vX),             
              oY = hHeights - Math.abs(vY);         
          if (oX >= oY) {
                if (vY > 0) {
                    colDir = "t";
                    shapeA.y += oY;
                } else {
                    colDir = "b";
                    shapeA.y -= oY;
                }
            } else {
                if (vX > 0) {
                    colDir = "l";
                    shapeA.x += oX;
                } else {
                    colDir = "r";
                    shapeA.x -= oX;
                }
            }
          }
        return colDir;
      }
      
      document.body.addEventListener("keydown", function(e) {
        keys[e.keyCode] = true;
      });
 
      document.body.addEventListener("keyup", function(e) {
        keys[e.keyCode] = false;
      });
      
      window.addEventListener("load", function() {
        update();
      });
      
      
    </script>
  </body>
</html>