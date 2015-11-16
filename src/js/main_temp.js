/* -------------------------------------------------
VARIAVÉIS
------------------------------------------------------ */
var gifTransfer;
var clickbutton = true;
var captionLength;
var _onLoadStatus = false;
var error;
var _playlists;

$_fileEsq = $('.file-esq');
$_fileDir = $('.file-dir');
$_typeContainer = $('h1');
$_footer = $('footer');

$_init = $('.init');
$_choose = $('.choose');
$_listPlay = $('.list-playlist');
$_process = $('.process');
$_convert = $('.convert');


/* -------------------------------------------------
   BROWSER EVENTS
------------------------------------------------------ */

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

		addListeners();
	}
}


/* -------------------------------------------------
LISTENERS
------------------------------------------------------ */

function addListeners() {
	$("#start").click(function(e) { navigationStart(e); });
	$(".rdio").click(function(e) { connectPopup(e, 'rdio'); });
	$(".deezer").click(function(e) { connectPopup(e, 'deezer'); });
	$(".grooveshark").click(function(e) { rdioConnectPopup(e, 'grooveshark'); });
	$("#back-choice").click(function(e) { backToChoice(e); });
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
   	   			setTimeout( function() { type($_typeContainer, 'Importar'); }, 300);
   	   			//End Type effect
   	   			$_choose.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay: 200});
   	   		}
   	   	});
   	}
}

function navigationChoose(e, _service) {
	if(e) e.preventDefault();

	if (clickbutton == true) {
			clickbutton = false;

			$_choose.velocity({opacity: 0, marginTop: +50 }, {duration:800,  display: 'none', delay: 200,
   	   		complete: function() {
   	   			clickbutton = true;
   	   			//Type Effect
   	   			erase($_typeContainer);
   	   			setTimeout( function() { type($_typeContainer, _service + ' → Spotify'); }, 50);
   	   			//End Type effect
   	   			$_process.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay:200});

   	   			$('.line').velocity({translateX: 0}, { duration: 100, queue: false });
	   			$_fileEsq.velocity({translateX: 0, opacity: 1}, { duration: 100, queue: false });
	   			$_fileDir.velocity({translateX: 0, opacity: 1}, { duration: 200, queue: false,
	   				complete: function() {
	   					fileTransferAnimate();
	   				}
	   			});
   	   		}
   	   	});
	}
}

function showMusicWrong(arrayMusics) {

	$('.list-musics').empty();

	for (i = 0; i < arrayMusics.length; ++i) {

		$("<li/>", {
		    text: arrayMusics[i]['name']
		}).appendTo('.list-musics');

	}
}

function processMessages () {

	$_textTerminal = $('.terminal .text');

	$_textTerminal.typed({
			strings: ['iniciando processo...', 'embalando os álbuns...', 'contratando os artistas...', 'embrulhando para presente...', 'contratando a transportadora...', 'em trânsito para o novo endereço...', 'abrindo embalagens...', 'organizando os álbuns na estante...' ],
			startDelay: 200,
			typeSpeed: -20,
			backSpeed: -40,
			backDelay: 7000,
			loop: true,
	});
}

function finalStep() {

	if (clickbutton == true) {

		clickbutton = false;
   	   	clearInterval(gifTransfer);

		$('.line').velocity('stop');
		$_fileEsq.velocity({opacity: 0}, {duration:100,
			complete: function() {
				$_fileDir.velocity(
					{ translateX: -43, opacity: 1 },
					{ duration: 200 });
				$('.line1').velocity( { translateX: 34, opacity: 1 }, { duration: 200, queue: false });
				$('.line2').velocity( { translateX: 34, opacity: 1 }, { duration: 200, queue: false });
				$('.line3').velocity( { translateX: 34, opacity: 1 }, { duration: 200, queue: false });
				$('.line4').velocity(
					{ translateX: 20, opacity: 1 },
					{ duration: 100, queue: false });
			}
		});

		$_process.velocity({opacity: 0, marginTop: +50 }, {duration:200,  display: 'none',
   	   		complete: function() {
   	   			clickbutton = true;
   	   			//Type Effect
   	   			erase($_typeContainer);
   	   			setTimeout( function() { type($_typeContainer, 'Yeah!'); }, 300);
   	   			//End Type effect
   	   			$_footer.velocity({ opacity: 0 }, {duration:200 });
   	   			$_convert.velocity({ opacity: 1, marginTop: 0 }, {duration:500,  display: 'block', delay: 350,
   	   				complete: function() {
   	   					$_footer.css({ 'position': 'relative', 'padding': '50px 10px 5px 5px;', 'opacity': '1', 'display': 'block' });
   	   				}
   	   			});
   	   		}
   	   	});
   	}
}

function constructFinalStep(playlists) {

	for (i = 0; i < playlists.length; ++i) {

		if (playlists[i]['musics_not_found'].length == 0) {
			$_containerLi = $("<li/>", {
			    id: 'playlist'+i
			}).appendTo("ul.playlists");

			$("<div/>", {
		    	class: 'name',
		    	text: playlists[i]['name']
			}).appendTo($_containerLi);

		} else {
			arrayMusics = playlists[i]['musics_not_found'];
			$_containerLi = $("<li/>", {
			    id: 'playlist'+i,
			    class: 'error',
			}).appendTo("ul.playlists");

			$("<div/>", {
		    	class: 'name',
		    	text: playlists[i]['name'],
		    	onClick: 'showMusicWrong(arrayMusics)'
			}).appendTo($_containerLi);
		}

		$("<a/>", {
		    class: 'button',
		    text: 'Salvar no spotify',
		    href:  playlists[i]['musics_link']
		}).appendTo($_containerLi);

		if (i == 0) {
			$("<p/>", {
			    class: 'tip',
			    text: 'no Spotify selecione as faixas e clique em "Adiconar a" > "Nova Playlist"'
				}).appendTo($_containerLi);
		}
	}

	finalStep();
}


function backToChoice(e) {
	if(e) e.preventDefault();

	if (clickbutton == true) {

		clickbutton = false;
		$("html, body").animate({ scrollTop: 0 }, 200);
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

   	   	$_footer.velocity({opacity: 0 }, {duration:100 });
   	   	$_convert.velocity({opacity: 0, marginTop: +50 }, {duration:600,  display: 'none', delay: 200,
   	   		complete: function() {
   	   			clickbutton = true;
   	   			//Type Effect
   	   			erase($_typeContainer);
   	   			setTimeout( function() { type($_typeContainer, 'Importar'); }, 300);
   	   			//End Type effect
   	   			$_choose.velocity({ opacity: 1, marginTop: 0 }, {duration:800,  display: 'block', delay: 200});
   	   			$('.playlists').empty();
   	   			$_footer.css({ 'position': 'absolute', 'padding': '0 0 0 0', 'opacity': '1' }).delay(300);
   	   		}
   	   	});
   	}

}

/* -------------------------------------------------
SOCIALNETWORKS
------------------------------------------------------ */

function connectPopup(e, _service)
{
	e.preventDefault();
	var _windowPoupup =  popupWindow( 'php/' + _service + '_connect.php', _service + '_connect', 655, 350 );
}

function connectPopupSuccess(_service, _params)
{
	navigationChoose(null, _service);

	setTimeout(function() {
		loadPlaylists('php/' + _service + '_playlist.php', _params);
	}, 1000);
}

function loadPlaylists(_url, _data)
{
	ajaxConnection = $.ajax(
	{
	  	url: _url,
	 	type: 'POST',
	 	dataType: 'json',
	 	data: _data

	}).done( function(_response)
	{
		if(_response['response'] && _response['response'] == 'success') {
            _playlists = _response['playlists'];
			constructFinalStep(_playlists);
		} else {
            window.location.href = 'erro.html';
        }
	}
	).fail( function (jqXHr, textStatus, errorThrown)
	{
		window.location.href = 'erro.html';
	});

	processMessages();
}

function loadPlaylistsMusics(_playlist)
{
    ajaxConnection = $.ajax(
    {
        url: 'php/spotify_musics.php',
        type: 'GET',
        dataType: 'json',
        data: {playlist: _playlist}

    }).done( function(_response)
    {
        console.log( _response );
    }
    ).fail( function (jqXHr, textStatus, errorThrown)
    {
        alert('ERROR');
    });

    processMessages();
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