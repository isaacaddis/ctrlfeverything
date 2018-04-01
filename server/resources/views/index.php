<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.21.0/moment.min.js"></script>
    </head>
    <body>
        <style>
        html, body{
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        #visualizer {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            position:fixed;
        }

        path {
            stroke-linecap: square;
            stroke: white;
            stroke-width: 0.5px;
        }
        </style>
        <script>
        window.onload = function () {
            "use strict";
            var paths = document.getElementsByTagName('path');
            var visualizer = document.getElementById('visualizer');
            var mask = visualizer.getElementById('mask');
            var path;
            var report = 0;

            var soundAllowed = function (stream) {
                //Audio stops listening in FF without // window.persistAudioStream = stream;
                //https://bugzilla.mozilla.org/show_bug.cgi?id=965483
                //https://support.mozilla.org/en-US/questions/984179
                window.persistAudioStream = stream;
                var audioContent = new AudioContext();
                var audioStream = audioContent.createMediaStreamSource( stream );
                var analyser = audioContent.createAnalyser();
                audioStream.connect(analyser);
                analyser.fftSize = 1024;

                var frequencyArray = new Uint8Array(analyser.frequencyBinCount);
                visualizer.setAttribute('viewBox', '0 0 255 255');

                //Through the frequencyArray has a length longer than 255, there seems to be no
                //significant data after this point. Not worth visualizing.
                for (var i = 0 ; i < 255; i++) {
                    path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('stroke-dasharray', '4,1');
                    mask.appendChild(path);
                }
                var doDraw = function () {
                    requestAnimationFrame(doDraw);
                    analyser.getByteFrequencyData(frequencyArray);
                    var adjustedLength;
                    for (var i = 0 ; i < 255; i++) {
                        adjustedLength = Math.floor(frequencyArray[i]) - (Math.floor(frequencyArray[i]) % 5);
                        paths[i].setAttribute('d', 'M '+ (i) +',255 l 0,-' + adjustedLength);
                    }

                }
                doDraw();
            }

            var soundNotAllowed = function (error) {
                console.log(error);
            }

            navigator.getUserMedia({audio:true}, soundAllowed, soundNotAllowed);

        };
        </script>
        <div style="z-index: -1; width: 100%; height: 100%; background-color: #E0E0E0; position: fixed;" id="ma"></div>
        <svg preserveAspectRatio="none" id="visualizer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="z-index: -2; background-color: #E0E0E0">
            <defs>
                <mask id="mask">
                    <g id="maskGroup">
                  </g>
                </mask>
                <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:#ff0a0a;stop-opacity:1" />
                    <stop offset="20%" style="stop-color:#f1ff0a;stop-opacity:1" />
                    <stop offset="90%" style="stop-color:#d923b9;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#050d61;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="url(#gradient)" mask="url(#mask)"></rect>
        </svg>
        <div class="container" style="z-index: 1; padding-top: 300px;"  id="form">
            <div style="padding: 40px;">
                <h2 style="text-align: center; font-family: Helvetica; font-weight: bold; color: #616161">Use <span style="background-color: #EEEEEE; border: 1px solid #AAAAAA; border-radius: 10px; padding: 5px;">Ctrl</span> + <span style="background-color: #EEEEEE; border: 1px solid #AAAAAA; border-radius: 10px; padding: 5px;">F</span> to Find Anything!</h2>
            </div>
            <!--
            <div class="col-lg-6 offset-lg-3">
                <form>
                    <div class="form-group row">
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="object" aria-describedby="emailHelp" placeholder="Please enter the thing you want to find...">
                        </div>
                        <div class="col-lg-2">
                            <button class="float-right btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>                
                </form>
            </div>
            -->
            <div style="display: flex; justify-content:center">
                <form class="form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Object..." aria-label="object" aria-describedby="search-button" style="background-color: #cfd8dc; border-color: black; outline:none" id="keyword">
                        <div class="input-group-prepend">
                            <span class="input-group-text btn" id="search-button" style="border-radius: 0px 5px 5px 0px;background-color: #cfd8dc; border-color: black; border-left: 0px;" onclick="search()">     
                                <span class="fas fa-search"></span>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div style="display: flex; justify-content: center;" id="icon-button" onclick="start()">
                <div style="width: 64px; height: 64px; font-size: 48px; border: 1px solid black; border-radius: 32px; text-align: center; align-content: center; display:flex; justify-content: center; cursor: pointer">
                    <i id="icon" class="fas fa-microphone" style="display: inline-block; height: 100%"></i>
                </div>
            </div>
            <div id="result">
            </div>
        </div>

        <script>
            var recognition = new webkitSpeechRecognition();
            recognition.continuous = true;

            function start() {
                console.log("start");
                recognition.start();
                $("#icon").removeClass("fa-microphone");
                $("#icon").addClass("fa-stop");
                $("#icon-button").attr("onclick", "end()");
                $("#ma").css("z-index", -3);
            }
            function end() {
                console.log("end");
                recognition.stop();
                $("#icon").removeClass("fa-stop");
                $("#icon").addClass("fa-microphone");
                $("#icon-button").attr("onclick", "start()");
                $("#ma").css("z-index", -1);
            }
            
            recognition.onresult = function(event) {
                console.log(event.results[0][0].transcript);
                $("#keyword").val(event.results[0][0].transcript);
                search();
            };

            $(function() {
                $.ajax({url: "https://lahackhack-199707.appspot.com/api/objects"}).done(function(data) {
                    source = data;
                    $("#keyword").autocomplete({"source": data["data"]["objects"]});
                });
                window.addEventListener("keydown",function (e) {
                    console.log(e);
                    if (event.keyCode === 13) {
                        search();
                        e.preventDefault();
                    }
                    if (e.keyCode === 114 || ((e.ctrlKey || e.metaKey) && e.keyCode === 70)) {
                        console.log("Prevented");
                    }
                });
            });
            
            function search() {
                $.ajax({
                    url: "https://lahackhack-199707.appspot.com/api/search?object=" + $("#keyword").val()
                }).done(function(data) {
                    $("#form").animate({
                        "padding-top": "10px"
                      }, 300, function() {
                        // Animation complete.
                      });
                    renderResult(data["data"]);   
                });
            }
            function renderResult(data) {
                console.log(data);
                var elements = []
                for (var i = 0; i < data.length; i++) {
                    html = "";
                    html += '<div class="col-lg-12" style="padding: 20px; background-color: white;"><div class="row"><div class="col-lg-4"><img style="width: 100%;" src="data:image/jpeg;base64,' + data[i]["img"] + '"></div>';
                    html += '<div class="col-lg-8"><p style="font-size: 36px; font-weight: bold; color: #64b5F6;">' + data[i]["deviceName"] + '</p>';
                    html += '<p style="font-size: 24px; font-weight: bold; color: #666666;">Last appeared@ ' + moment(data[i]["takenAt"]).fromNow() + '</p>';
                    html += '<p style="font-size: 24px; font-weight: bold; color: #666666;">IP: ' + data[i]["ip"] + '</p></div>';
                    html += '</div></div>';
                    elements.push(html);
                }
                html = '<div style="padding-top: 20px; padding-bottom: 20px; margin-top: 30px; background-color: white; border-radius: 5px; box-shadow: 3px 3px 5px">';
                if (data.length != 0)
                    html += elements.join('<hr style="width: 90%">');
                else
                    html += '<p style="padding-left: 30px; font-size: 24px;">No records found.</p>';
                html += '</div>'
                $("#result").html(html);
            }
            
        </script>
    </body>
</html>
