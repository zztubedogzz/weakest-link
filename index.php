<?php

$_ENV = json_decode(file_get_contents(".env"), TRUE);

// echo "<pre>";
// print_r($_ENV);
// echo "</pre>";

$DB = new mysqli($_ENV["DB_HOST"],$_ENV["DB_USER"],$_ENV["DB_PASS"],$_ENV["DB_NAME"]);
if ($DB -> connect_errno) {
  echo "Failed to connect to MySQL: " . $DB -> connect_error;
  exit();
}

$variables = json_decode(file_get_contents(".data/variables.json"), TRUE);

?>

<!-- Weakest Link webapp -->
<!-- Created by TubeDog -->
<!-- 2023. June 25. -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Weakest Link</title>

	<script type="text/javascript">

		// ##################################
		// Change these to customize prizes!
		// ##################################
		var chain = <?php echo json_encode($variables["chain"]); ?>;
		var currency = <?php echo json_encode($variables["currency"]); ?>;
	</script>

	<style>
		* {
			font-family: Verdana;
			box-sizing: border-box;
		}
		input.bank {
			width: calc(100% - 128px) !important;
			text-shadow: 1px 1px 1px black;
		}
		button{
			margin: 2px;
		}
		#addChain{
			display: flex;
			justify-content: space-evenly;
			flex-wrap: wrap;
		}
		.remove{
			position: absolute;
			font-size: 48pt;
			top:0;
			right: 16px;
			cursor: no-drop;
			-webkit-user-select: none; /* Safari */
			-ms-user-select: none; /* IE 10 and IE 11 */
			user-select: none; /* Standard syntax */
		}
		.remove:active{
			color:black;
		}
		@keyframes shake{
			0%{top:0; right:16px}
			20%{top:2px; right:14px}
			40%{top:0px; right:14px}
			60%{top:2px; right:16px}
			80%{top:2px; right:14px}
			100%{top:0; right:16px}
		}
		.remove:hover{
			animation-name: shake;
			animation-duration: 0.1s;
			animation-iteration-count: infinite;
			text-shadow: 1px 1px 1px red;
		}
		.remove:hover:active{
			color:red;
		}
		#bank{
			font-size: 24px;
			font-family: Tahoma;
			font-weight: bold;
			color: gold;
		}
		.chain{
			padding: 16px;
			font-family: Arial;
			font-weight: bold;
			font-size: 16pt;
			color:white;
			text-shadow: 1px 1px 1px gold;
			background-color: #3366ffaa;
			border-radius: 50%;
			border: 2px solid black;
		}
		header{
			margin-bottom: 24px;
		}
		input{
			font-size: 24pt;
			border: none;
			background-color: transparent;
			text-shadow: 1px 1px 1px;
		}
		input:focus{
			outline: none;
		}
		#addPlayer {
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
		}
		.player {
			position: relative;
			margin: 8px;
			padding: 16px 64px 16px 16px;
			border: 2px solid transparent;
			background-color: #00000011;
		}
		.player input[type=number]{
			width: 48px;
			font-size: 14pt;
			font-weight: bold;
		}
		.correct{
			color: green;
			font-weight: bold;
		}
		.wrong{
			color:red;
			font-weight: bold;

		}
		.bank{
			color:gold;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div>
		<button onclick="addPlayer()">Add new Player</button>
		<button onclick="nextPlayer()">Next Player</button>
	</div>
	<div>
		<button onclick="clearBank()">Clear bank</button>
		<button onclick="clearScores()">Clear answers</button>
	</div>
	<div class="answers">
		<button class="correct" onclick="answer(true)">Correct</button>
		<button class="wrong" onclick="answer()">Wrong</button>
		<button class="bank" onclick="bank()">Bank</button>
	</div>
	<header>
		<div>
			<p>Bank: <span id="bank">0</span><span id="currency"></span></p>
		</div>
		<div>
			<div id="addChain"></div>
		</div>
	</header>
<hr>
<div id="players">
	<div id="addPlayer"></div>
</div>
</body>
</html>
<script type="text/javascript">

	var player_count = 0;
	var current_player = 1;
	var chainpos = 0;
	var bankval = 0;


	function skipRemoved(){
		var loop = false;
		//console.log("Skipcheck");
		while (document.getElementById("player_"+current_player)==null) {
		//console.log("Checking for player "+current_player);
			if (current_player+1>player_count) { 
				if (loop) {
					alert("No players left!");
					throw new Error("No players left!");
				} else {
					current_player=1; var loop=true;
					//console.log("Skipcheck looped!");
				}
			} else { current_player++; }
		}
	}

	function removePlayer(e,id){
		if (id==current_player) {
			nextPlayer();
		}
		e.parentNode.parentNode.removeChild(e.parentNode);
		console.log("Player "+id+" removed!");
	}

	function clearBank() {
		console.log("Bank cleared!")
		document.getElementById('bank').innerHTML = 0;
		for (var i = 1; i <= player_count; i++) {
			if (document.getElementById("bank"+i)!=undefined) {
				document.getElementById("bank"+i).value="";
			}
		}
		bankval=0
	}

	function bank() {
		bankval += chain[chainpos];
		console.log("Player "+document.getElementById("player_"+current_player+"_name").value+" saved "+chain[chainpos]+currency+" to the bank!");
		document.getElementById("bank").innerHTML = bankval;
		var playerbank = document.getElementById("bank"+current_player).value
		if (playerbank!=='') {
			playerbank=parseInt(document.getElementById("bank"+current_player).value);
		} else {playerbank = 0}
		document.getElementById("bank"+current_player).value = playerbank+chain[chainpos];
		if (chainpos>0) {
			document.getElementById("chain"+chainpos).style.backgroundColor = "#3366ffaa";
			document.getElementById("chain"+chainpos).style.color = "white";

		}
		chainpos = 0;
	}

	function clearScores(){
		if (chainpos>0) {
			document.getElementById("chain"+chainpos).style.backgroundColor = "#3366ffaa";
			document.getElementById("chain"+chainpos).style.color = "white";
		}
		chainpos = 0;
		for (var i=1; i<=player_count; i++) {
			if (document.getElementById("player_"+i)==null) {} else {
				console.log("Player "+i+" answers cleared!");
				document.getElementById("correct"+i).value="";
				document.getElementById("wrong"+i).value="";
			}
		}
	}

	function nextPlayer(){
		skipRemoved();
		document.getElementById("player_"+current_player).style.backgroundColor = "#00000011";
		if (current_player+1>player_count) { current_player=1; } else { current_player++; }
		skipRemoved();
		document.getElementById("player_"+current_player).style.backgroundColor = "#00AAFF66";

	}

	function answer(correct) {
		skipRemoved();
		if (correct) {
			
			if ((chainpos+1)>=chain.length) { } else {
				if (chainpos>0) {
					document.getElementById("chain"+chainpos).style.backgroundColor = "#3366ffaa";
					document.getElementById("chain"+chainpos).style.color = "white";
				}
			 	chainpos++;
				document.getElementById("chain"+chainpos).style.backgroundColor = "#0055FF66";
				document.getElementById("chain"+chainpos).style.color = "yellow";
			}
			var child = document.getElementById("correct"+current_player);
			console.log("Player "+document.getElementById("player_"+current_player+"_name").value+" answered correctly!");
			if (child.value==undefined) { child.value = 1; } else { child.value++; }
		} else {
			if (chainpos>0) {
				document.getElementById("chain"+chainpos).style.backgroundColor = "#3366ffaa";
				document.getElementById("chain"+chainpos).style.color = "white";

			}
			chainpos=0;
			var child = document.getElementById("wrong"+current_player);
			console.log("Player "+document.getElementById("player_"+current_player+"_name").value+" answered wrong!");
			if (child.value==undefined) { child.value = 1; } else { child.value++; }
		}
		nextPlayer();
	}

	function addPlayer(id) {
		player_count++;
		console.log("Player "+player_count+" added!");
		document.getElementById("addPlayer").insertAdjacentHTML('beforeend',
			'<div class="player" id="player_'+player_count+'">'+
				'<input id="player_'+player_count+'_name" type="text" placeholder="Player name" maxlength="20" size="20"/><br>'+
				'Correct Answers: <input type="number" min="1" class="correct" id="correct'+player_count+'"/><br>'+
				'Wrong Answers: <input type="number" min="1" class="wrong" id="wrong'+player_count+'"/></span><br>'+
				'Banked: <input type="number" min="1" class="bank" id="bank'+player_count+'"/></span><br>'+
				'<span class="remove" title="Remove player" onclick="removePlayer(this,'+player_count+')">&#9760;</span>'+
				'<div class"finale"></div>'+
			'</div>');
		skipRemoved();
		document.getElementById("player_"+current_player).style.backgroundColor = "#00AAFF66";
	}

	for (var i=1; i<chain.length;i++) {
		document.getElementById("addChain").insertAdjacentHTML('beforeend', '<span class="chain" id="chain'+i+'">'+chain[i]+'</span>');
	}
	addPlayer();
	document.getElementById("currency").innerHTML=currency;

</script>