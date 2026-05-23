<?php
$conn = mysqli_connect("localhost", "root", "", "badminton_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['save_match'])) {

    $team1 = mysqli_real_escape_string($conn, $_POST['team1']);
    $team2 = mysqli_real_escape_string($conn, $_POST['team2']);
    $score1 = mysqli_real_escape_string($conn, $_POST['score1']);
    $score2 = mysqli_real_escape_string($conn, $_POST['score2']);

    $sql = "INSERT INTO scorecard (team1, team2, score1, score2)
            VALUES ('$team1', '$team2', '$score1', '$score2')";

    if(mysqli_query($conn, $sql)) {

    echo "<script>alert('Match saved successfully!');</script>";

} else {

    echo "<script>alert('Error saving match: " . mysqli_error($conn) . "');</script>";

}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Badminton Scoreboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#0d1f12;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.container{
    width:100%;
    max-width:700px;
    background:#13281a;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 20px rgba(0,0,0,0.4);
}

h1{
    text-align:center;
    margin-bottom:25px;
    color:#f5c842;
}

.setup{
    display:flex;
    gap:15px;
    margin-bottom:25px;
}

.setup input{
    flex:1;
    padding:12px;
    border:none;
    border-radius:8px;
    outline:none;
    font-size:16px;
}

.setup button{
    padding:12px 20px;
    border:none;
    border-radius:8px;
    background:#f5c842;
    color:#111;
    font-weight:bold;
    cursor:pointer;
}

.match{
    display:none;
}

.scoreboard{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.team{
    background:#1d3825;
    border-radius:12px;
    padding:25px;
    text-align:center;
    cursor:pointer;
    transition:0.2s;
}

.team:hover{
    transform:scale(1.02);
}

.team h2{
    margin-bottom:20px;
    font-size:24px;
}

.score{
    font-size:90px;
    font-weight:bold;
    color:#f5c842;
}

.tap{
    margin-top:10px;
    color:#aaa;
}

.controls{
    margin-top:25px;
    display:flex;
    justify-content:center;
    gap:15px;
}

.controls button{
    padding:12px 20px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

.save-btn{
    background:#f5c842;
    color:#111;
}

.new-btn{
    background:#2e7d42;
    color:white;
}

.winner{
    margin-top:20px;
    text-align:center;
    font-size:22px;
    color:#4dff88;
    font-weight:bold;
}

@media(max-width:600px){

    .scoreboard{
        grid-template-columns:1fr;
    }

    .score{
        font-size:70px;
    }

    .setup{
        flex-direction:column;
    }
}

</style>
</head>
<body>

<div class="container">

    <h1>🏸 Badminton Scoreboard</h1>

    <!-- Setup -->
    <div class="setup" id="setup">
        <input type="text" id="team1Input" placeholder="Player 1">
        <input type="text" id="team2Input" placeholder="Player 2">
        <button onclick="startMatch()">Start Match</button>
    </div>

    <!-- Match -->
    <div class="match" id="match">

        <div class="scoreboard">

            <div class="team" onclick="addPoint(0)">
                <h2 id="team1Name">Player 1</h2>
                <div class="score" id="score1">0</div>
                <div class="tap">Tap to Score</div>
            </div>

            <div class="team" onclick="addPoint(1)">
                <h2 id="team2Name">Player 2</h2>
                <div class="score" id="score2">0</div>
                <div class="tap">Tap to Score</div>
            </div>

        </div>

        <div class="winner" id="winnerText"></div>

        <div class="controls">

            <!-- Save Match -->
            <form method="post" id="saveForm">

                <input type="hidden" name="save_match" value="1">

                <input type="hidden" name="team1" id="db_team1">
                <input type="hidden" name="team2" id="db_team2">
                <input type="hidden" name="score1" id="db_score1">
                <input type="hidden" name="score2" id="db_score2">

                <button type="button" class="save-btn" onclick="saveMatch()">
                    💾 Save Match
                </button>

            </form>

            <button class="new-btn" onclick="newMatch()">
                🔄 New Match
            </button>

        </div>

    </div>

</div>

<script>

let state = {
    team1: "Player 1",
    team2: "Player 2",
    score1: 0,
    score2: 0,
    matchOver: false
};

function startMatch(){

    state.team1 =
        document.getElementById("team1Input").value.trim() || "Player 1";

    state.team2 =
        document.getElementById("team2Input").value.trim() || "Player 2";

    state.score1 = 0;
    state.score2 = 0;
    state.matchOver = false;

    document.getElementById("team1Name").textContent = state.team1;
    document.getElementById("team2Name").textContent = state.team2;

    document.getElementById("score1").textContent = 0;
    document.getElementById("score2").textContent = 0;

    document.getElementById("winnerText").textContent = "";

    document.getElementById("match").style.display = "block";
}

function addPoint(team){

    if(state.matchOver) return;

    if(team === 0){
        state.score1++;
    } else {
        state.score2++;
    }

    document.getElementById("score1").textContent = state.score1;
    document.getElementById("score2").textContent = state.score2;

    checkWinner();
}

function checkWinner(){

    if(state.score1 >= 21){

        state.matchOver = true;

        document.getElementById("winnerText").textContent =
            "🏆 " + state.team1 + " Wins!";

    }

    if(state.score2 >= 21){

        state.matchOver = true;

        document.getElementById("winnerText").textContent =
            "🏆 " + state.team2 + " Wins!";

    }
}

function saveMatch(){

    document.getElementById("db_team1").value = state.team1;
    document.getElementById("db_team2").value = state.team2;

    document.getElementById("db_score1").value = state.score1;
    document.getElementById("db_score2").value = state.score2;

    document.getElementById("saveForm").submit();
}

function newMatch(){

    location.reload();
}

</script>

</body>
</html>