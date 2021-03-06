<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
    require_once('../includes/config.php');
?>

<?php
	// Create a new uesr # TODO make sure that it's no longer than 128 char
	$player = new User();
	$player->name = empty($_GET['name'])? "Player" : safe_input($_GET['name']);
	$player->save();

	// Select the room # TODO make sure the room exists.
	$room = Room::find_by_id(safe_input($_GET['room']));
	$judge = User::find_by_id($room->user_id);

	// Save this player room-user information
	$room_user = new RoomUser();
	$room_user->room_id = $room->id;
	$room_user->user_id = $player->id;
	$room_user->save();

	echo("You joined room " . $room->id);
	echo("<br/>The host of the room is " . $judge->name);

	// Show all user in this room
	$room_users = RoomUser::find_by_first_id($room->id);
	foreach ($room_users as $room_user) {
		$user = User::find_by_id($room_user->user_id);
		echo("<br/>Player :" . $user->name . " is in the room");
	}
	# $room is the ROOMID string given in createRoom.php
	# $name is a player chosen string, their name that will be visible to the judge
	# I could limit which characters are usable for $name on the front end, but it should be sanitized anyway
	// Player don't immediately get cards 

	// echo '{
	// 	"cards": {
	// 		"characters": [
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			},
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			}
	// 		],
	// 		"equipment": [
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			},
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			}
	// 		],
	// 		"statuses": [
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			},
	// 			{
	// 				"id": "CARDID",
	// 				"name": "Card Name",
	// 				"ability": "Put the ability of the card here (empty string if none)",
	// 				"image": "/img/path.whatever"
	// 			}
	// 		]
	// 	},
	// 	"player": "PLAYERID"
	// }';
	# PLAYERID is a generated string which uniquely identifies the player, as CARDID should be for each card
	# whitespace can be omitted in the JSON response (except in strings like card names, of course)
	# it is fine to start dealing out cards before all players have joined the room
	# we can decide on how many of each type of card to deal out later, but having some with 4 or more would be useful to test horizontal scrolling
	# ideally, cards never repeat within a room, but that isn't critical at the moment
?>
