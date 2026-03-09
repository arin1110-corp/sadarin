<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maintenance</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f3f4f6;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:50px;
    border-radius:15px;
    box-shadow:0 10px 40px rgba(0,0,0,0.1);
    text-align:center;
    width:450px;
}

.gear{
    font-size:60px;
    animation:spin 6s linear infinite;
}

@keyframes spin{
    from{transform:rotate(0deg)}
    to{transform:rotate(360deg)}
}

h1{
    margin-top:15px;
}

p{
    color:#666;
}

.timer{
    display:flex;
    justify-content:center;
    gap:15px;
    margin-top:25px;
}

.time-box{
    background:#f1f1f1;
    padding:15px;
    border-radius:8px;
    width:70px;
}

.time-number{
    font-size:22px;
    font-weight:600;
}

.label{
    font-size:12px;
    color:#777;
}

.progress{
    margin-top:25px;
    background:#eee;
    border-radius:10px;
    overflow:hidden;
}

.bar{
    height:12px;
    width:0%;
    background:#4f46e5;
}

.support{
    margin-top:30px;
    font-size:13px;
    color:#999;
}

.support span{
    color:#4f46e5;
    font-weight:600;
}

</style>
</head>

<body>

<div class="box">

<div class="gear">⚙️</div>

<h1>Sistem Sedang Maintenance</h1>

<p>
Mohon maaf, sistem sedang dalam proses pemeliharaan.<br>
Silakan kembali beberapa saat lagi.
</p>

<div class="timer">

<div class="time-box">
<div id="hours" class="time-number">00</div>
<div class="label">Jam</div>
</div>

<div class="time-box">
<div id="minutes" class="time-number">00</div>
<div class="label">Menit</div>
</div>

<div class="time-box">
<div id="seconds" class="time-number">00</div>
<div class="label">Detik</div>
</div>

</div>

<div class="progress">
<div class="bar" id="progressBar"></div>
</div>

<div class="support">
Support by <span>ARIN</span>
</div>

</div>

<script>

const finishTime = new Date().getTime() + (3 *60* 60 * 1000); // 3 jam

const interval = setInterval(function(){

    const now = new Date().getTime();
    const distance = finishTime - now;

    const hours = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
    const minutes = Math.floor((distance % (1000*60*60))/(1000*60));
    const seconds = Math.floor((distance % (1000*60))/1000);

    document.getElementById("hours").innerHTML = hours;
    document.getElementById("minutes").innerHTML = minutes;
    document.getElementById("seconds").innerHTML = seconds;

    const total = 10 * 60 * 1000;
    const progress = ((total - distance) / total) * 100;
    document.getElementById("progressBar").style.width = progress + "%";

    if(distance < 0){
        clearInterval(interval);
        location.reload();
    }

},1000);

</script>

</body>
</html>