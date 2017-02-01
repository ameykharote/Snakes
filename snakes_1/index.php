<?php
	//include "connection.php";
	//session_start();
	//if ((!isset($_SESSION['tek_emailid'])||empty($_SESSION['tek_emailid']))&&(!isset($_SESSION['tek_fname'])||empty($_SESSION['tek_fname']))) 
	//{
    //    echo '<script>window.top.location.href = "http://teknack.in"</script>';
    //}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Simple Snake Game</title>
	<script type="text/javascript" src="jquery.min.js"></script>
	<!-- Basic styling, centering of the canvas. -->
<style>
body 
{ 	
	background-color:black;
}
#header {
	line-height:30%;
	text-align:center;
	}

h1,h2{
    font-family: "Comic Sans MS", cursive, sans-serif;
	color: green;
	text-align: center;
	line-height:30%;
}
p{
    font-family: "Comic Sans MS", cursive, sans-serif;
	color: green;
}

#nav {
    line-height:30px;
    height:30%;
    width:40%;
    float:left;
	border-style: solid;	
    border-width: 5px;
	border-color:#00cc00;
	font-family: "Comic Sans MS", cursive, sans-serif;
	color: green;
}

#canvas { 
    float:right;
	border-style: solid;
    border-width: 5px;
	border-color:#00cc00;
	position:absolute;
	left:45%;
}
	
#startbutton{
	position: absolute;
	bottom:38%;
	margin-left:70%;
	margin-right:30%;
}
	
#time{
	right:60%;
	bottom:68%;
	left:42%;
	position:absolute;
	color:white;
	font-family: "Comic Sans MS", cursive, sans-serif;
	}

</style>
</head>
<body>
	<div id = "header">
	<h1><img src = "header.png" style = "width:900px;height:120px;">
	<h1 style = "color:#1aff1a;">We present to you one of the earliest and most popular classic video game!<h1>
	</div>

	<div id = "nav">
	<h1 style = "color: #1aff1a;">INSTRUCTIONS:</h1>
	<p><font size="3">
	<ul>
		<li>Control the snake's movements using up,down,left,right keys.</li>
		<li>Good food (Red) increases the length of the snake and the score.</li>
		<li>Bad food (Green)keeps the length of the snake the same but decreases the score.</li>
		<li>The player loses the game if the snake runs into the screen borders or its head touches its body.</li>
		<li>Your goal is to help the snake eat as much good food as possible and survive 2 minutes, achieving a score of 35 or more,succeeding in doing so would give you 1000 points bringing you one step closer to the MEGA EVENT!</li>
	</ul><br>
	</p>
	</div>
	
	<div id = "time">
	</div>
	
	<canvas id = "canvas" width = "700" height = "420"></canvas>
	
	<div id = "startbutton">
	<button type = "submit" id = "startgame">Play</button>
	</div>
	
	<div id = "update">
	</div>

<script>
var timer,minutes,seconds,duration;
var

/**
 * Constats
 */
COLS = 26,
ROWS = 26,

EMPTY = 0,
SNAKE = 1,
FRUIT = 2,
BAD = 3,

LEFT  = 0,
UP    = 1,
RIGHT = 2,
DOWN  = 3,

KEY_LEFT  = 37,
KEY_UP    = 38,
KEY_RIGHT = 39,
KEY_DOWN  = 40,

/**
 * Game objects
 */
canvas,	  /* HTMLCanvas */
ctx,	  /* CanvasRenderingContext2d */
keystate, /* Object, used for keyboard inputs */
frames,   /* number, used for animation */
score;	  /* number, keep track of the player score */
 
/**
 * Grid datastructor, usefull in games where the game world is
 * confined in absolute sized chunks of data or information.
 * 
 * @type {Object}
 */
grid = {

	width: null,  /* number, the number of columns */
	height: null, /* number, the number of rows */
	_grid: null,  /* Array<any>, data representation */

	/**
	 * Initiate and fill a c x r grid with the value of d
	 * @param  {any}    d default value to fill with
	 * @param  {number} c number of columns
	 * @param  {number} r number of rows
	 */
	init: function(d, c, r) {
		this.width = c;
		this.height = r;

		this._grid = [];
		for (var x=0; x < c; x++) {
			this._grid.push([]);
			for (var y=0; y < r; y++) {
				this._grid[x].push(d);
			}
		}
	},

	/**
	 * Set the value of the grid cell at (x, y)
	 * 
	 * @param {any}    val what to set
	 * @param {number} x   the x-coordinate
	 * @param {number} y   the y-coordinate
	 */
	set: function(val, x, y) {
		this._grid[x][y] = val;
	},

	/**
	 * Get the value of the cell at (x, y)
	 * 
	 * @param  {number} x the x-coordinate
	 * @param  {number} y the y-coordinate
	 * @return {any}   the value at the cell
	 */
	get: function(x, y) {
		return this._grid[x][y];
	}
}

/**
 * The snake, works as a queue (FIFO, first in first out) of data
 * with all the current positions in the grid with the snake id
 * 
 * @type {Object}
 */
snake = {

	direction: null, /* number, the direction */
	last: null,		 /* Object, pointer to the last element in
						the queue */
	_queue: null,	 /* Array<number>, data representation*/

	/**
	 * Clears the queue and sets the start position and direction
	 * 
	 * @param  {number} d start direction
	 * @param  {number} x start x-coordinate
	 * @param  {number} y start y-coordinate
	 */
	init: function(d, x, y) {
		this.direction = d;

		this._queue = [];
		this.insert(x, y);
	},

	/**
	 * Adds an element to the queue
	 * 
	 * @param  {number} x x-coordinate
	 * @param  {number} y y-coordinate
	 */
	insert: function(x, y) {
		// unshift prepends an element to an array
		this._queue.unshift({x:x, y:y});
		this.last = this._queue[0];
	},

	/**
	 * Removes and returns the first element in the queue.
	 * 
	 * @return {Object} the first element
	 */
	remove: function() {
		// pop returns the last element of an array
		return this._queue.pop();
	}
};

function startTimer(duration, display) {
    timer = duration, minutes, seconds;
    setInterval(function () {
//alert();
		minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;
        if (--timer < 0) {
            //timer = duration;
			}
    },1000);
}
/*window.onclick = function () {
    var threeMinutes = 60 * 3,
        display = document.querySelector('#section');
    startTimer(threeMinutes, display);
};*/	

/**
 * Set a food id at a random free cell in the grid
 */
function setFood() {
	var empty = [];
	// iterate through the grid and find all empty cells
	for (var x=0; x < grid.width; x++) {
		for (var y=0; y < grid.height; y++) {
			if (grid.get(x, y) === EMPTY) {
				empty.push({x:x, y:y});
			}
		}
	}
	var randpos = empty[Math.round(Math.random()*(empty.length - 1))];
	grid.set(FRUIT, randpos.x, randpos.y);
	if(score>5)
	{
	for (var x=0; x < grid.width; x++) {
		for (var y=0; y < grid.height; y++) {
			if (grid.get(x, y) === EMPTY) {
				empty.push({x:x, y:y});
			}
		}
	}
	var randpos = empty[Math.round(Math.random()*(empty.length - 1))];
	grid.set(BAD, randpos.x, randpos.y);
	}
};
	// chooses a random cell

/**
 * Starts the game
 */
function main() {
	// create and initiate the canvas element width=1372*768 
	canvas = document.getElementById("canvas");
	canvas.width = 700;//COLS*20
	canvas.height = 420;//ROWS*20
	ctx = canvas.getContext("2d");
	// add the canvas element to the body of the document
	//document.body.appendChild(canvas);
	// sets an base font for bigger score display 
	
	ctx.font = "16px Times New Roman";

	frames = 0;
	keystate = {};
	// keeps track of the keyboard input
	document.addEventListener("keydown", function(evt) 
	{
		keystate[evt.keyCode] = true;
	});
	document.addEventListener("keyup", function(evt) {
		delete keystate[evt.keyCode];
	});

	// intatiate game objects and starts the game loop
	init();
	loop();
}

/**
 * Resets and inits game objects
 */
function init() {
	score = 0;
	grid.init(EMPTY, COLS, ROWS);

	var sp = {x:Math.floor(COLS/2), y:ROWS-1};
	snake.init(UP, sp.x, sp.y);
	grid.set(SNAKE, sp.x, sp.y);

	setFood();
}

/**
 * The game loop function, used for game updates and rendering
 */
function loop() {
	update();
	draw();
	// When ready to redraw the canvas call the loop function
	// first. Runs about 60 frames a second
	window.requestAnimationFrame(loop, canvas);
}

/**
 * Updates the game logic
 */
function update() {
	frames++;
	// changing direction of the snake depending on which keys
	// that are pressed
	if (keystate[KEY_LEFT] && snake.direction !== RIGHT) {
		snake.direction = LEFT;
	}
	if (keystate[KEY_UP] && snake.direction !== DOWN) {
		snake.direction = UP;
	}
	if (keystate[KEY_RIGHT] && snake.direction !== LEFT) {
		snake.direction = RIGHT;
	}
	if (keystate[KEY_DOWN] && snake.direction !== UP) {
		snake.direction = DOWN;
	}

	// each five frames update the game state.
	if (frames%5 === 0) {
		// pop the last element from the snake queue i.e. the
		// head
		var nx = snake.last.x;
		var ny = snake.last.y;

		// updates the position depending on the snake direction
		switch (snake.direction) {
			case LEFT:
				nx--;
				break;
			case UP:
				ny--;
				break;
			case RIGHT:
				nx++;
				break;
			case DOWN:
				ny++;
				break;
		}

		// checks all gameover conditions
		if (0 > nx || nx > grid.width-1  ||
			0 > ny || ny > grid.height-1 ||
			grid.get(nx, ny) === SNAKE || timer < 0) 
		
		{
			//post the results
			var ajaxdata = "score="+score;
			//alert(ajaxdata);
			/*$.ajax({
				type: "POST",
				url: "update.php",
				data: ajaxdata,
				success: function(data){
					alert(data); // use to debugg
					$.("#update").load("update.php");
				}

			});*/
			$.ajax({
	    		url: "update.php",
	    		type: "POST",
	    		data: ajaxdata,
	    		success: function(data){
					//alert(data);
	    			$('#update').load('update.php');
					//draw();
	    		}
			});
			setTimeout(function(){
				//location.reload();
				alert('Your score is '+ajaxdata);
				//location.reload();
				window.location="leaderboard.php";
			},50);
			//return init();
		}

		// check wheter the new position are on the fruit item
		if (grid.get(nx, ny) === FRUIT) 
		{
				// increment the score and sets a new fruit position
			score++;
			setFood();
		} 
		else if(grid.get(nx, ny) === BAD) 
		{	
			score--;
			}		
		else	
			{
			// take out the first item from the snake queue i.e
			// the tail and remove id from grid
			var tail = snake.remove();
			grid.set(EMPTY, tail.x, tail.y);
			}
		

		// add a snake id at the new position and append it to 
		// the snake queue
		grid.set(SNAKE, nx, ny);
		snake.insert(nx, ny);
	}
}

/**
 * Render the grid to the canvas.
 */
function draw() {
	// calculate tile-width and -height
	var tw = canvas.width/grid.width;
	var th = canvas.height/grid.height;
	
	// Make sure the image is loaded first otherwise nothing will draw.
	// iterate through the grid and draw all cells
	for (var x=0; x < grid.width; x++) {
		for (var y=0; y < grid.height; y++) {
			// sets the fillstyle depending on the id of
			// each cell
			switch (grid.get(x, y)) {
				case EMPTY:
					ctx.fillStyle = "#000";
					//ctx.fillStyle = "rgba(0,255,255,0)";
					break;
				case SNAKE:
					ctx.fillStyle = "#0FF";
					break;
				case FRUIT:
					ctx.fillStyle = "#F00";
					//ctx.drawImage(food);
					break;
				case BAD:
					ctx.fillStyle = "#008000";
					break;
			}
			ctx.fillRect(x*tw, y*th, tw, th);
		}
	}
	
	window.onclick = function () {
    var threeMinutes = 60 * 2;
	//alert();
    display = document.querySelector('#time');
    startTimer(threeMinutes, display);
	};
	// changes the fillstyle once more and draws the score
	// message to the canvas
	ctx.fillStyle = "#FFF";
	ctx.fillText("SCORE: " +score, 10, canvas.height-10);
}
// start and run the game
//main();
</script>
<script>
	$("#startgame").click(function(e) {
		e.preventDefault();
		$("#startbutton").hide();
		main();
		//var start = setInterval(main,);
	});
</script>
</body>
</html>