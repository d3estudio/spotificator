/* -------------------------------------------------
VARIAVÉIS
------------------------------------------------------ */
var gifTransfer;
var clickbutton = true;
var captionLength;
var _onLoadStatus = false;
var error;
var _playlists;
var _playlistCurrentMusics = new Array();
var _currentPlaylistTotal = 0;
var _secretParams;
var _playlistsWasSliced = false;

$_fileEsq = $('.file-esq');
$_fileDir = $('.file-dir');
$_typeContainer = $('h1');
$_footer = $('footer');

$_init = $('.init');
$_choose = $('.choose');
$_listPlay = $('.list-playlist');
$_process = $('.process');
$_convert = $('.convert');
$_slicedPlaylists = $('.sliced-playlist');

$_cover = $('.cover');

/* -------------------------------------------------
   BROWSER EVENTS
------------------------------------------------------ */

  if (!!navigator.userAgent.match(/Trident\/7\./)) {
    $('html').addClass('ie');
  }

$( document ).ready(function() {
	$( window ).load( function() { indexLoad(); });
	setTimeout( indexLoad, 3000 );
});

function indexLoad(){
	if( _onLoadStatus == false ) {
		_onLoadStatus = true;

		fileTransferAnimate();
		$_choose.css({ 'margin-top': '50px','display':'none'});
		$_process.css({ 'margin-top': '50px','display':'none'});
		$_listPlay.css({ 'margin-top': '50px','display':'none'});
		$_convert.css({ 'margin-top': '50px','display':'none'});
		$_cover.css({ 'margin-top': '50px','display':'none', 'opacity':0});

		addListeners();
	}
}


/* -------------------------------------------------
LISTENERS
------------------------------------------------------ */

function addListeners() {
	$("#start").click(function(e) { navigationStart(e); });
	$(".rdio").click(function(e) { connectPopup(e, 'rdio'); waitingConnection($(this)); });
	$(".deezer").click(function(e) { connectPopup(e, 'deezer'); waitingConnection($(this)); });
	$("#back-playlists").click(function(e) { backToPlaylists(e); });
  $("#back-again").click(function(e) { _playlistsWasSliced = false; backToPlaylists(e); });
}


/* -------------------------------------------------
MOTION / NAVIGATION
------------------------------------------------------ */

function fileTransferAnimate() {
	for (var i = 1; i < 4; i++) {
		$('.line'+i).velocity("stop");
		$('.line'+i).velocity(
		  { translateX: 77, opacity: 1 },
		  { duration: 200, delay: 100*i }
		);
	}

	$('.line4').velocity("stop");
	$('.line4').velocity(
	{ translateX: 63, opacity: 1 },
	{ duration: 170, delay: 400,
	  	complete: function() {
	  		$('.line1').velocity({opacity: 0}, {duration:200, delay:1500});
	  		$('.line2').velocity({opacity: 0}, {duration:200, delay:1500});
	  		$('.line3').velocity({opacity: 0}, {duration:200, delay:1500});
	  		$('.line4').velocity({opacity: 0}, {duration:200, delay:1500,
	  			complete: function() {
	  				$('.line').velocity({translateX: 0}, {duration:0});
	  				$('.line').velocity({opacity: 1}, {duration:100});
		  		}
	  		});
		}
	});

	clearInterval(gifTransfer);
	gifTransfer = setInterval(fileTransferAnimate, 3500);
}

function type(container, string) {
	if (!captionLength && captionLength != 0) {
			captionLength = 0;
	}
    cuttext = string.substr(0, captionLength++);
    container.html(cuttext);
    if(captionLength < string.length+1) {
        setTimeout(function(){type(container, string)}, 50);
    } else {
        captionLength = null;
        string = '';
    }
}

function erase(container) {
	caption = container.text();
	if (!captionLength && captionLength != 0) {
			captionLength = caption.length;
	}
    container.html(caption.substr(0, captionLength--));
    if(captionLength >= 0) {
        setTimeout(function(){erase(container)}, 20);
    } else {
        captionLength = null;
        caption = '';
    }
}

function navigationStart(e) {
	e.preventDefault();

	if (clickbutton == true) {

		clickbutton = false;
   	   	clearInterval(gifTransfer);
   	   	$('.line').velocity("reverse", { duration: 100,
   	   		complete: function() {
   	   			$('.line').velocity('stop');
   	   			$_fileDir.velocity({opacity: 0}, {duration:100,
		   			complete: function() {
		   				$_fileEsq.velocity(
		   					{ translateX: 43, opacity: 1 },
		   					{ duration: 200 });
		   				$('.line').velocity(
		   					{ translateX: 43, opacity: 1 },
		   					{ duration: 200 });
		   			}
		   		});
   	   		}
   	   	});

   	   	$_init.velocity({opacity: 0, marginTop: +50 }, {duration:800,  display: 'none', delay: 200,
   	   		complete: function() {
   	   			clickbutton = true;
   	   			//Type Effect
   	   			erase($_typeContainer);
				setTimeout( function() { type($_typeContainer, IMPORT_TITLE ); }, 300);
   	   			//End Type effect
   	   			$_choose.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay: 200});
   	   		}
   	   	});

        popupWindow('php/spotify_connect.php', 'spotify_connect', 655, 350);

   	}
}

function waitingConnection (element) {
  if (clickbutton == true) {
    element.css({ 'background':'#494949', 'padding-left': '20px', 'font-size': '11px', 'display':'block', 'height' : '42px' } );
    element.html('<img src="img/icon-load.gif" style="opacity:0;">').delay(300);
    element.children('img').velocity({ opacity:1 }, { duration: 500, delay:300 });

    clickbutton = false;
  }
}

function waitingLoadMusics (element) {
  if (clickbutton == true) {
    element.css({ 'background':'#494949', 'position': 'relative', 'display':'block', 'height' : '40px' } );
    element.html('<img src="img/icon-load.gif" style="opacity:0; position:absolute; top: 9px; left: 135px;">').delay(300);
    element.children('img').velocity({ opacity:1 }, { duration: 500, delay:300 });

    clickbutton = false;
  }
}

function navigationChoose(e) {
	if(e) e.preventDefault();

    $_footer.velocity({opacity: 0}, {duration:500,  display: 'none', delay: 200});
		$_choose.velocity({opacity: 0, marginTop: +50 }, {duration:800,  display: 'none', delay: 200,
 	   		complete: function() {
 	   			clickbutton = true;
 	   			//Type Effect
 	   			erase($_typeContainer);
 	   			setTimeout( function() { type($_typeContainer, 'Playlists'); }, 50);
 	   			//End Type effect
 	   			$_listPlay.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay:200,
            complete: function() {
              $_footer.css({ 'position': 'relative', 'padding': '50px 5px 5px 5px', 'opacity': '1', 'display': 'block' });
            }
          });

 	   			$('.line').velocity('stop');
			$_fileEsq.velocity({opacity: 0}, {duration:100,
				complete: function() {
					$_fileDir.velocity(
						{ translateX: -36, opacity: 1 },
						{ duration: 200 });
					$('.line1').velocity( { translateX: 40, opacity: 1 }, { duration: 200, queue: false });
					$('.line2').velocity( { translateX: 40, opacity: 1 }, { duration: 200, queue: false });
					$('.line3').velocity( { translateX: 40, opacity: 1 }, { duration: 200, queue: false });
					$('.line4').velocity(
						{ translateX: 27, opacity: 1 },
						{ duration: 100, queue: false });
				}
			});
 	   		}
 	});
}

function navigationPlaylistsSliced(nameplaylist) {
    _playlistsWasSliced = true;

    erase($_typeContainer);
    $_footer.velocity({opacity: 0}, {duration:500,  display: 'none', delay: 200});
    $_listPlay.velocity({opacity: 0, marginTop: +50 }, {duration:800,  display: 'none', delay: 200,
        complete: function() {
          clickbutton = true;
          //Type Effect
          $_typeContainer.css('height', 'auto' );
          setTimeout( function() { type($_typeContainer, nameplaylist); }, 100);
          //End Type effect
          $_slicedPlaylists.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay:200,
            complete: function() {
              $_footer.css({ 'position': 'relative', 'padding': '50px 5px 5px 5px', 'opacity': '1', 'display': 'block' });
              $("#back-playlists").text("Voltar e converter outra parte");
            }
          });
        }
      });
}

function navigationConvert(nameplaylist) {

   	$('.wrap').velocity({opacity: 0 }, {duration:500,
   		complete: function() {
   			$('.gif-transition').css('display', 'none');
      $('.img-fallback').css('display', 'none');
   			$_cover.css({ 'display':'block', 'opacity' : '0' });
   			$_listPlay.css('display', 'none');
   			$_footer.css('display', 'none');
      $_slicedPlaylists.css('display', 'none');
   			$_typeContainer.css('opacity', '0');
   		}
   	});

   	$("html, body").animate({ scrollTop: 0 }, 200);

   	$('.wrap').velocity({opacity: 1}, {duration:200, delay: 600,
   		complete: function() {
   			clickbutton = true;
   			$_cover.velocity({ opacity: 1, marginTop: 0 }, {duration:300,  display: 'block', delay: 250 });
   			//Type Ajust
   			$_typeContainer.css('height', 'auto' );
   			$_typeContainer.text(nameplaylist);
      $_typeContainer.velocity({ opacity: 1 }, {duration:300,  display: 'block', delay: 250 });
   			//End type ajust
   			$_convert.velocity({ opacity: 1, marginTop: 0 }, {duration:300,  display: 'block', delay: 250,
   				complete: function() {
   					$_footer.css({ 'position': 'relative', 'padding': '50px 5px 5px 5px', 'opacity': '1', 'display': 'block' }).delay(300);
   				}
   			});
   		}
   	});
}

function musicListUpdate(_musics) {
    $('ul.musics li').each(function( index ) {
        if ($.inArray($( this ).text(), _musics['found']) >=0 ) {
            if (!$( this ).addClass('success')) {
                $( this ).addClass('success');
                $( this ).removeClass('error');
            }
        }
        else if ($.inArray($( this ).text(), _musics['not_found']) >=0 ) {
            if (!$( this ).addClass('error')) {
                $( this ).addClass('error');
                $( this ).removeClass('success');
            }
        }
    });

    if ($('#btnSaveToSpotify').hasClass('waiting')) {
        musicListButtonUpdate( _musics['found'].length + _musics['not_found'].length);
    }
}

function musicListButtonUpdate(_current) {
    var _total = _currentPlaylistTotal < 10 ? '0' + _currentPlaylistTotal : _currentPlaylistTotal;
    _current = _current < 10 ? '0' + _current : _current;

    $('#btnSaveToSpotify').text(_current + OF_TEXT + _total + MUSIC_TEXT);
}

function musicListButtonComplete(_link) {
    setTimeout(function(){
        $('#btnSaveToSpotify').text( BUTTON_OPEN );
        $('#btnSaveToSpotify').attr('href', _link);
        $('#btnSaveToSpotify').removeClass('waiting');
        $('#btnSaveToSpotify').addClass('end');
        $("html, body").animate({ scrollTop: 0 }, 500);
    }
    , 1000);

    $('.tip').velocity({opacity: 1}, { duration: 300, delay:500, display: 'block'});
    $('#instruction').velocity({opacity: 0}, { duration: 300, delay:300,
      complete: function() {
          $('#instruction').html( FINISH_TEXT );
          $('#instruction').velocity({opacity: 1}, { duration: 300, delay:200 });
      }
    });
}


/* -------------------------------------------------
BACK FUNCTIONS
------------------------------------------------------ */

function backToPlaylists(e) {
  if(e) e.preventDefault();

  if (clickbutton == true) {
    clickbutton = false;

    if(_playlistsWasSliced == true) {
      var $_backTo = $_slicedPlaylists;
      var textH1 = $_typeContainer.text();
      textH1 = textH1.split('-');
      textH1 = textH1[0];
    } else {
      var $_backTo = $_listPlay;
      var textH1 = "Playlists";
    }

    $("html, body").animate({ scrollTop: 0 }, 200);
        $('.wrap').velocity({opacity: 0 }, {duration:500,
          complete: function() {
            $_cover.css('display', 'none');
            if (!!navigator.userAgent.match(/Trident\/7\./)) {
              $('.img-fallback').css({ 'display':'block', 'margin': '0 auto 10px auto' });
            } else {
              $('.gif-transition').css({ 'display':'block', 'margin': '0 auto 10px auto' });
            }
            $_convert.css('display', 'none');
            if(_playlistsWasSliced != true) $_typeContainer.css('height', '70px' );
            $_slicedPlaylists.css('display', 'none');
            $_footer.css('display', 'none');
            $('.tip').css('display', 'none');
            $('#instruction').html( WAINTING_TEXT );
            $('.transferMusic').html( BUTTON_TRASNFER );
            $('.transferpart').html( BUTTON_STEP );
            erase($_typeContainer);
          }
        });

        $('.wrap').velocity({opacity: 1}, {duration:200, delay: 600,
          complete: function() {
            clickbutton = true;
            //Type Effect
            setTimeout( function() { type($_typeContainer, textH1); }, 100);
            //End Type effect
            $_backTo.velocity({ opacity: 1, marginTop: 0 }, {duration:500,  display: 'block',
              complete: function() {
                $_footer.css({ 'position': 'relative', 'padding': '50px 10px 5px 5px', 'opacity': '1', 'display': 'block' }).delay(300);
              }
            });
          }
        });
    }
}


/* -------------------------------------------------
CONSTRUCTS
------------------------------------------------------ */

function constructPlaylists(playlists) {

  $("ul#playlists").empty();

  for (i = 0; i < playlists.length; ++i) {

    $_containerLi = $("<li/>", {
        id: 'playlist'+i,
    }).appendTo("ul#playlists");

    $("<div/>", {
        class: 'name',
        text: playlists[i]['name']
    }).appendTo($_containerLi);

    $("<a/>", {
        class: 'button transferMusic',
        text: BUTTON_TRASNFER,
        'data-music': i,
        'data-name': playlists[i]['name'],
        'click': function() {
          _playlistCurrentMusics = new Array();
          waitingLoadMusics($(this));
          loadMusics(_playlists[$(this).data('music')]);
        }
    }).appendTo($_containerLi);

  }

  navigationChoose();
}

function constructPlaylistsSliced() {
  $("ul#sliced-playlist").empty();

  for (i = 0; i < _playlistCurrentMusics.length; ++i) {

    $_containerLi = $("<li/>", {
        id: 'sliced-playlist'+i,
    }).appendTo("ul#sliced-playlist");

    var numberPlaylist = i + 1;

    $("<div/>", {
        class: 'name',
        text: _playlistCurrentMusics[i]['name']+ " - Parte "+numberPlaylist+"/"+_playlistCurrentMusics.length
    }).appendTo($_containerLi);

    $("<a/>", {
        class: 'button transferpart',
        text: BUTTON_STEP,
        'data-music': i,
        'data-name': _playlistCurrentMusics[i]['name'],
        'click': function() {
          var _playlist = _playlistCurrentMusics[$(this).data('music')];
          constructMusics(_playlist, $(this));
          setTimeout(function() { loadFromSpotify(_playlist); }, 500);
        }
    }).appendTo($_containerLi);
  }

  navigationPlaylistsSliced(_playlistCurrentMusics[0]['name']);
}

function constructMusics(_playlist, element) {
  _currentPlaylistTotal = _playlist['musics'].length;

  $("ul.musics").empty();

  for (i = 0; i < _currentPlaylistTotal; ++i) {
    $("<li/>", {
      id: 'music'+i,
      class: 'name',
      text: _playlist['musics'][i]
    }).appendTo("ul.musics");
  }

  var cover = _playlist['picture'];
  if (cover != false) {
    $_cover.find('img').attr("src", cover);
  } else {
    $_cover.find('img').attr("src","img/cover-default.jpg");
  }


  if(_playlistsWasSliced == true) {
      navigationConvert(element.parent().children('.name').text());
      setTimeout(function() { element.parent().addClass('spotificada'); }, 300);
  } else {
      navigationConvert(_playlist['name']);
  }

  $('#btnSaveToSpotify').removeClass('end');
  $('#btnSaveToSpotify').addClass('waiting');
  musicListButtonUpdate(0);
}


/* -------------------------------------------------
LOADS
------------------------------------------------------ */

function connectPopup(e, _service)
{
	e.preventDefault();
	var _windowPoupup =  popupWindow( 'php/' + _service + '_connect.php', _service + '_connect', 655, 350 );
}

function connectPopupSuccess(jsonResp)
{
  var obj = JSON.parse(jsonResp);
  _secretParams = obj.paramers;
	setTimeout(function() { loadPlaylists(obj.service); }, 1000);
}

function loadPlaylists(_service)
{
	ajaxConnection = $.ajax(
	{
	  url: 'php/' + _service + '_playlist.php',
	 	type: 'POST',
	 	dataType: 'json',
	 	data: _secretParams

	}).done( function(_response)
	{
		if(_response['response'] && _response['response'] == 'success') {
      _playlists = _response['playlists'];
			constructPlaylists(_playlists);
		} else {
      window.location.href = 'erro.php';
    }
	}
	).fail( function (jqXHr, textStatus, errorThrown)
	{
		window.location.href = 'erro.php';
	});
}

function loadMusics(_playlist, _page)
{
  if (_page == undefined) _page = 1;

  var _data = _secretParams;
  _data.playlist = _playlist;
  _data.page = _page;

  ajaxConnection = $.ajax(
  {
      url: 'php/' + _playlist['type'] + '_music.php',
      type: 'POST',
      dataType: 'json',
      data: _data

  }).done( function(_response)
  {
      if(_response['response'] && _response['response'] == 'success') {
        var _playlist_id = _response['playlist']['id'];

        if (_response['playlist']['musics'].length > 0)
          _playlistCurrentMusics.push(_response['playlist']);

        if (_response['pagination'] == false) {
          if (_playlistCurrentMusics.length == 1) {
            constructMusics(_response['playlist']);
            setTimeout(function() { loadFromSpotify(_response['playlist']); }, 1000);
          }
          else {
            constructPlaylistsSliced();
          }
        }
        else {
          loadMusics(_playlist, _playlistCurrentMusics.length + 1);
        }
      } else {
        window.location.href = 'erro.php';
      }
  }
  ).fail( function (jqXHr, textStatus, errorThrown)
  {
      window.location.href = 'erro.php';
  });
}

function loadFromSpotify(_playlist)
{
    ajaxConnection = $.ajax(
    {
        url: 'php/spotify_musics.php',
        type: 'POST',
        dataType: 'json',
        data: {playlist: _playlist}

    }).done( function(_response)
    {
        if(_response['response'] && _response['response'] == 'success') {
            musicListButtonComplete(_response['link']);
        } else {
          window.location.href = 'erro.php';
        }

        clearInterval(_musicsStatusInterval);
        loadFromSpotifyStatus();
    }
    ).fail( function (jqXHr, textStatus, errorThrown)
    {
        clearInterval(_musicsStatusInterval);
        window.location.href = 'erro.php';
    });

    _musicsStatusInterval = setInterval(loadFromSpotifyStatus, 1500);
}

function loadFromSpotifyStatus()
{
    ajaxConnection = $.ajax(
    {
        url: 'php/spotify_musics_status.php',
        type: 'GET',
        dataType: 'json'

    }).done( function(_response)
    {
        if(_response['response'] && _response['response'] == 'success') {
            musicListUpdate(_response['musics'])
        }
    }
    ).fail( function (jqXHr, textStatus, errorThrown)
    {

    });
}

function popupWindow( popUpUrl, popUpName, pWidth, pHeight )
{
	var pLeft = (screen.width) ? (screen.width-pWidth)/2 : 0;
	var pTop = (screen.height) ? (screen.height-pHeight)/2 : 0;
	var popUpSettings =    'height=' + pHeight +
							',width=' + pWidth +
							',left=' + pLeft +
							',top=' + pTop +
							',toolbar=no' +
							',scrollbars=no' +
							',status=yes' +
							',resizable=no' +
							',location=no' +
							',menuBar=no';
	newPopupWindow = window.open( popUpUrl, popUpName, popUpSettings );
}
