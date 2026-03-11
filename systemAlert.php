<div id="systemOfflineOverlay" 
style="
    display:none; 
    position:fixed; 
    top:0; 
    left:0; 
    width:100%; 
    height:100%; 
    background:rgba(180, 0, 0, 0.98); 
    z-index:10000; 
    color:white; 
    text-align:center; 
    flex-direction:column; 
    justify-content:center; 
    align-items:center; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
">
    <div class="popUpContainer" 
    style="
        border: 10px solid white; 
        padding: 40px; 
        border-radius: 20px;
    ">
        <h1 class="header"
        style="
            font-size: 7vw; 
            margin: 0;
        ">SYSTEM CRITICAL</h1>
        <p id="offlineReason"
        style="
            font-size: 2.5vw; 
            background: white; 
            color: red; 
            display: inline-block; 
            padding: 10px 20px; 
            font-weight: bold; 
            margin-top: 20px;
        "></p>
        <p class="action"
        style="
            font-size: 1.5vw; 
            margin-top: 30px;
        ">Action Required: Check Laptop Connection & XAMPP Status</p>
    </div>
</div>

<audio id="systemDownSound" src="assets/sounds/alert.mp3" preload="auto"></audio>
<script src="js/systemWatchdog.js"></script>