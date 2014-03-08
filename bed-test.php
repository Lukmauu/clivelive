<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="http://api.html5media.info/1.1.6/html5media.min.js"></script>
        <!--<script type="text/javascript" src="http://www.serverroom.net/jwplayer/swfobject.js"></script>-->
        <script type="text/javascript" src="http://www.serverroom.net/jwplayer5/jwplayer.js"></script>
        
        <div style="position: relative; width: 560px; margin: 0 auto; height: 400px;">
        <div id="player_preview">
            This text will be replaced. Streaming solutions by <a href="http://www.serverroom.net">Server Room - Shoutcast hosting, Flash Streaming</a>
        </div>
        </div>
  
  <script type="text/javascript"> 
    jwplayer('player_preview')
                    .setup( {  
                        'id': 'playerID', 
                        'width': 560, 
                        'height': 400, 
                        'provider': 'rtmp', 
                        'rtmp.tunneling':'false', 
                        events: { 
                            onPause: function(event) { 
                                jwplayer('mediaspace').play();
                            } 
                        }, 
                        'autostart':'true',  
                        'streamer': 'rtmp://clivelive1.srfms.com:2173/live', 
                        'file': 'livestream', 
                        'skin': 'http://www.serverroom.net/jwplayer5/classic.zip', 
                        'duration': '0',  
                        'modes': [   {
                                type: 'flash', 
                                src: 'http://www.serverroom.net/jwplayer5/player.swf'
                            },  
                            { 
                                type: 'html5', 
                                config: { 
                                    'file': 'http://clivelive1.srfms.com:2173/live/livestream/playlist.m3u8', 
                                    'provider': 'video' 
                                }
                            },  
                            { 
                                type: 'download', 
                                config: { 
                                    'file': 'http://clivelive1.srfms.com:2173/live/livestream/playlist.m3u8', 
                                    'provider': 'video' 
                                } 
                            } ] 
                        });
                    </script> 
        
        <?php
        //http://lucas3.srfms.com:2075/live/livestream/playlist.m3u8
        
        
        // put your code here
        ?>
    <!--<object id="mediaPlayer" width="400" height="200" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"  codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"  standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">  <param name="fileName" value="mms://joseaccount2014.serverroom.net/joseaccount2014">  <param name="animationatStart" value="true">  <param name="transparentatStart" value="true">  <param name="autoStart" value="true">  <param name="showControls" value="true">  <param name="loop" value="true">  <embed type="application/x-mplayer2"  pluginspage="http://microsoft.com/windows/mediaplayer/en/download/"  id="mediaPlayer" name="mediaPlayer" displaysize="4" autosize="-1"   bgcolor="darkblue" showcontrols="true" showtracker="-1"   showdisplay="0" showstatusbar="-1" videoborder3d="-1" width="400" height="200"  src="mms://joseaccount2014.serverroom.net/joseaccount2014" autostart="true" designtimesp="5311" loop="true">  </embed></object>-->
    <!--<img src="//home-ip-camera.dyndns-server.com/videostream.cgi" />-->
  
  
    </body>
</html>
