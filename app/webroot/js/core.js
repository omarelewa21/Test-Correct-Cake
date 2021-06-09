$(function() {
	Core.initialise();
	var ajaxCompleteTimer;
	$.ajaxSetup({cache: false});
    $( document ).ajaxComplete(function() {
    	clearTimeout(ajaxCompleteTimer);
    	ajaxCompleteTimer = setTimeout(function(){
			if ('com' in window && 'wiris' in window.com && 'js' in window.com.wiris && 'JsPluginViewer' in window.com.wiris.js) {
				// With this method all non-editable objects are parsed.
				// com.wiris.js.JsPluginViewer.parseElement(element) can be used in order to parse a custom DOM element.
				// com.wiris.JsPluginViewer are called on page load so is not necessary to call it explicitly (I'ts called to simulate a custom render).
				com.wiris.js.JsPluginViewer.parseDocument();
			}
        },250);
    })
		.ajaxError(function(event,xhr){
			if (xhr.status === 401 || xhr.status === 403) {
				window.onbeforeunload = null;
				window.location.reload();
			}
		});
});


var Core = {

	dev : false,
	inApp : false,
	appType : '',
	cache : [],
	surpressLoading : false,
    lastLostFocusNotification : false,
    lastLostFocusNotificationDelay : 3 * 60, // 3 minutes
	unreadMessagesTimer : false,
	cheatIntervalInSeconds : 5,
	lastLostFocus: { notification: false, delay: 3*60, reported: {} },

	isChromebook: function(){
        return !!(window.navigator.userAgent.indexOf('CrOS') > 0);
	},

	initialise : function() {
		var isIOS = /(iPad|iPhone|iPod)/g.test(navigator.userAgent);
  		var isAndroid = /Android/g.test(navigator.userAgent);

  		if(isIOS) {
  			Core.isIpad();
  		}else if(isAndroid){
  			Core.isAndroid();
  		}


		$.datepicker.setDefaults({
			dateFormat: 'dd-mm-yy'
		});

		if(Core.dev) {
			Core.inApp = true;
		}

		if(window.isInApp) {
			Core.inApp = true;
			Core.appType = 'windows';
		}

		if(window.navigator.userAgent.indexOf('CrOS') > 0) {
			Core.appType = 'Chromebook';
		}

		$.ajax({
			url: '/test_takes/get_header_session',
			cache: false,
			type: 'POST',
			dataType: 'text',
			success: function(data) {
				if(data == 'NEEDSUPDATE' || data == 'OK') {
					Core.inApp = true;
					if(Core.appType !== 'ipad'){
						Core.appType = 'mac';
					}
				}
			}
		});

		// Notify.notify('<span style="font-size:42px;">'+ Core.appType +  ' - ' + Core.inApp + ' - ' + Core.header +'</span>');

		$(window).resize(function() {
			Core.setDimensions();
		}).resize();

		jQuery(document).ajaxStart(function (event, XMLHttpRequest, ajaxOptions) {
			clearTimeout(window.ajaxLoadTimeout);
			window.ajaxLoadTimeout = setTimeout(function() {
				if(!Core.surpressLoading) {
					Loading.show();
				}
			}, 1000);
		});

		jQuery(document).ajaxStop(function (event, XMLHttpRequest, ajaxOptions) {
			clearTimeout(window.ajaxLoadTimeout);
			Loading.hide();
			Dropdowns.initialise();
			Core.setDimensions();
			Core.afterHTMLload();

			$('input, textarea').attr('spellcheck', false);

		});

		$( document ).ajaxComplete(function( event,request, settings ) {
			if(request.responseText == 'logout') {
				User.logout();
			}

			setTimeout(function() {
				$('input, textarea, .redactor-editor').attr({
					autocapitalize : 'off',
					autocorrect : 'off',
					autocomplete : 'off',
					spellcheck : 'false'
				});
			}, 100);
		});

		setInterval(function(){
			Core.setDimensions();
		}, 100);

		setInterval(function() {
			Popup.calcPosition();
		}, 5000);

		Core.startCheckUnreadMessagesListener();

		// I think this runs in isolation on login. afterlogin already included an extra call;
		 //   Navigation.load('/users/welcome');

		User.checkLogin();
	},

	startCheckUnreadMessagesListener : function(){
        Core.unreadMessagesTimer = setInterval(function() {
            Core.checkUnreadMessages();
        }, 60000);
	},

	stopCheckUnreadMessagesListener : function(){
		if(Core.unreadMessagesTimer != false) {
            clearInterval(Core.unreadMessagesTimer);
            Core.unreadMessagesTimer = false;
        }
	},

	afterLogin : function() {
		Menu.initialise();
		User.initialise();

		if(window.isInApp) {
			Core.inApp = true;
			Core.appType = 'windows';
		}

		$('#header').show();
		Navigation.load('/users/welcome');

		setTimeout(function() {Core.checkUnreadMessages()}, 3000);

	},

	checkUnreadMessages : function() {
		$('#messages .counter').hide();
		// only ask for unread if not on login page.
		if(Utils.notOnLoginScreen()) {
            $.get('/messages/unread',
                function (unread) {
                    if (unread > 0) {
						//stuent
						$('#messages .counter').show().html(unread);
						//teacher
						$('#other .counter').show().html(unread);
                    }
                }
            );
        }
	},

	setDimensions : function() {
		var winW = $(window).width();
		var winH = $(window).height();

		$('#container').css({
			'width' : (winW - 50) + 'px'
		});

		$.each($('.block.autoheight'), function() {

			var fooH = $(this).find('.block-footer').height();

			var conH = winH;

			if(fooH != null) {
				conH -= fooH;
			}

			$(this).find('.block-content').css({
				'height' : (conH - 300) + 'px'
			});
		});
	},

	afterHTMLload : function() {
		setTimeout(function() {
			Core.initTabs();
			Dropdowns.initialise();
		}, 200);
	},

	initTabs : function() {
		$('.tabs a').unbind().click(function() {
			var page = $(this).attr('page');
			var tabs = $(this).attr('tabs');

			$(this).parent().children().removeClass('highlight');
			$(this).addClass('highlight');

			$('.page[tabs=' + tabs + ']').removeClass('active');
			$('.page[tabs=' + tabs + '][page=' + page + ']').addClass('active');

			setCookie('tab', page);
			console.log('set cookie: ' + page);
		});

		if(getCookie('tab') != undefined) {
			$('.tabs a[page="' + getCookie('tab') + '"]').click();
		}
	},

	lostFocus : function(reason) {
		if(TestTake.active) {
			if (reason == "printscreen") {
				Notify.notify('Het is niet toegestaan om een screenshot te maken, we hebben je docent hierover geïnformeerd', 'error');
			} else {
				Notify.notify('Het is niet toegestaan om uit de app te gaan', 'error');
			}

			if (Core.shouldLostFocusBeReported(reason)) {
				$.post('/test_takes/lost_focus', {reason: reason});
                TestTake.startHeartBeat(TestTake.heartBeatCallback,Core.cheatIntervalInSeconds);
			}
            TestTake.alert = true;
            TestTake.markBackground();
		}
	},

	shouldLostFocusBeReported: function(reason) {

		if (reason == null) {
			reason == "undefined";
		}

		if (!TestTake.active) {
			return false;
		}

		if (!(reason in Core.lastLostFocus.reported) || !TestTake.alert) {
			Core.lastLostFocus.reported[reason] = (new Date()).getTime()/1000;
			return true;
		}

		var now = (new Date()).getTime()/1000;
		var lastTime = Core.lastLostFocus.reported[reason];
		if (lastTime <= now - Core.lastLostFocus.delay) {
			Core.lastLostFocus.reported[reason] = (new Date()).getTime()/1000;
			return true;
		}

		return false;
	},

	screenshotnotify : false,

	screenshotDetected : function() {
		if(!Core.screenshotnotify) {
			Notify.notify('Het is niet toegestaan om een screenshot te maken,  we hebben je docent hierover geïnformeerd', 'info');
			$.get('/test_takes/screenshot_detected');
		}
		Core.screenshotnotify = true;
		setTimeout(function(){
			Core.screenshotnotify = false;
		}, 1000);
		TestTake.alert = true;
        TestTake.markBackground();
        TestTake.startHeartBeat(TestTake.heartBeatCallback,Core.cheatIntervalInSeconds);
	},

	isIpad : function() {
		var standalone = window.navigator.standalone,
	    userAgent = window.navigator.userAgent.toLowerCase(),
	    safari = /safari/.test( userAgent ),
	    ios = /iphone|ipod|ipad/.test( userAgent );

	    if( ios ) {
		    if ( !standalone && safari ) {
		        Core.appType = 'browser';
		        Core.inApp = false;
		    } else if ( standalone && !safari ) {
		        Core.appType = 'standalone';
				Core.inApp = true;
		    } else if ( !standalone && !safari ) {
		        Core.appType = 'ipad';
		        Core.inApp = true;
		    };
		}
	},

	isAndroid : function() {
		Core.inApp = true;
		Core.appType = 'android';
	},

	cacheLoad : function(path, container) {
		if(Core.cache[path] == undefined) {
			$.get(path, function(html) {
				Core.cache[path] = html;
				$(container).html(html);
			});
		}else{
			$(container).html(Core.cache[path]);
		}
	}
};

var Loading = {

	discard : false,

	show : function() {
		if(!Loading.discard) {
			$('#loading').fadeIn();
		}else{
			Loading.discard = false;
		}
	},

	hide : function() {
		$('#loading').fadeOut();
	}
};

var Utils = {
	onLoginScreen: function() {
		return ($('#UserLoginForm').length === 1);
	},

    notOnLoginScreen: function() {
        return !this.onLoginScreen();
    },
	urlContainsEduIx: function() {
		return (new URLSearchParams(window.location.search)).has('edurouteSessieID');
	},
	urlContainsRegisterNewTeacher:function() {
        return location.href.split('/').filter( function(item){ return item.includes('register_new_teacher')}).length;
	},
	urlContainsRegisterNewTeacherSuccessful:function() {
		return location.href.split('/').includes('register_new_teacher_successful');
	},
};

var Dropdowns = {

	parentSelector : null,

	initialise : function() {
		$.each($('.dropblock-owner'), function() {
			$(this).unbind().click(function() {
				var container = $('.dropblock[for=' + $(this).attr('id') + ']');

				var scrT = $(window).scrollTop();
				var top = ($(this).offset().top + $(this).outerHeight()) - scrT;
				var left = $(this).offset().left;
				var conH = $(container).outerHeight();
				var winH = $(window).height();
				var btnSelector = this;

				if(conH + top > winH) {
					top -= conH;
					top -= $(this).outerHeight();
				}

				Dropdowns.parentSelector = this;

				$(this).addClass('active');

				if ($(this).hasClass('dropblock-left')) {
					left -= $(container).width();
					left += $(this).outerWidth();
					left -= 30;
				}

				if($(container).is(':visible')) {
					$(container).slideUp();
				}else {
					$(container).css({
						'left': left + 'px',
						'top': top + 'px'
					}).slideDown();

					if($(container).hasClass('blur-close')) {
						$(container).unbind().mouseleave(function() {
							$(container).slideUp(function(){
								$(btnSelector).removeClass('active');
							});
						});

						setTimeout(function() {
							$('.dropblock-owner.active').removeClass('active');
							$('.dropblock.blur-close').slideUp();
						}, 4000);
					}else{
						$(container).find('.btn-close').unbind().click(function() {
							$(container).slideUp(function(){
								$(btnSelector).removeClass('active');
							});
						});
					}
				}
			});
		});
	}
};

var Message = {
	reply : function(user_id) {
		Popup.closeLast();
		setTimeout(function() {
			Popup.load('/messages/send/' + user_id, 500);
		}, 1000);
	}
};

var Organisation = {
	delete : function(id) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze koepelorganisatie wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/umbrella_organisations/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Organisatie verwijderd', 'info');
					Navigation.load('/school_locations');
				}
			});
		})
	}
}

var School = {
	delete : function(id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze schoolgemeenschap wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/schools/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('School verwijderd', 'info');
					Navigation.refresh();
				}
			});
		});
	}
};

var SchoolYear = {
	delete : function(id, view) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit schooljaar wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_years/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Schooljaar verwijderd', 'info');
					if (view) {
						Navigation.load('/school_years');
					} else {
						Navigation.refresh();
					}
				}
			});
		});
	}
};

var Teacher = {
	delete : function(id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze gebruiker wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_classes/delete_teacher/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Docent verwijderd', 'info');
					Navigation.refresh();
				}
			});
		});
	}
};

var SchoolLocation = {
	delete : function(id, source) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze locatie wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_locations/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Schoollocatie verwijderd', 'info');
					if(source == 0) {
						Navigation.refresh();
					}else{
						Navigation.back();
					}
				}
			});
		});
	},

	deleteLicense : function(location_id, license_id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit licentiepakket wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_locations/delete_licence/' + location_id + '/' + license_id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Licentiepakket verwijderd', 'info');
					Navigation.refresh();
				}
			});
		});
	}
};

var SchoolClass = {
	delete : function(id, source) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze klas wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_classes/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Klas verwijderd', 'info');
					if(source == 0) {
						Navigation.refresh();
					}else{
						Navigation.back();
					}
				}
			});
		});
	},

	removeMentor : function(class_id, id) {
		Popup.message({
				btnOk: 'Ja',
				btnCancel: 'Annuleer',
				title: 'Weet u het zeker?',
				message: 'Weet u zeker dat u dit persoon wilt verwijderen?'
			}, function() {
				$.ajax({
					url: '/school_classes/remove_mentor/' + class_id + '/' + id,
					type: 'DELETE',
					success: function(response) {
						Navigation.refresh();
					}
				});
			}
		);
	},

	removeManager : function(class_id, id) {
		Popup.message({
				btnOk: 'Ja',
				btnCancel: 'Annuleer',
				title: 'Weet u het zeker?',
				message: 'Weet u zeker dat u dit persoon wilt verwijderen?'
			}, function() {
				$.ajax({
					url: '/school_classes/remove_manager/' + class_id + '/' + id,
					type: 'DELETE',
					success: function(response) {
						Navigation.refresh();
					}
				});
			}
		);
	},

	removeTeacher : function(id) {
		$.ajax({
			url: '/school_classes/remove_teacher/' + id,
			type: 'DELETE',
			success: function(response) {
				Navigation.refresh();
			}
		});
	},

	removeStudent : function(class_id, id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze student wilt verwijderen?'
		}, function() {
				$.ajax({
					url: '/school_classes/remove_student/' + class_id + '/' + id,
					type: 'DELETE',
					success: function(response) {
						Navigation.refresh();
					}
				});
			}
		);
	}
};

var Section = {
	delete : function(id, view) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze sectie wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/sections/delete/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Sectie verwijderd', 'info');
					if (view) {
						Navigation.load('/sections')
					} else {
						Navigation.refresh();
					}
				}
			});
		});

	}
};

var Subject = {
	delete : function(id) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit vak wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/sections/delete_subject/' + id,
				type: 'DELETE',
				success: function(response) {
					Notify.notify('Vak verwijderd', 'info');
					Navigation.refresh();
				}
			});
		});
	}
};

var Contact = {
	delete : function(owner, owner_id, type, id) {


		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit contactpersoon wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/contacts/delete/' + owner + '/' + owner_id + '/' + type + '/' + id,
				type: 'DELETE',
				success: function(response) {
					Navigation.refresh();
					Notify.notify('Contact verwijderd');
				}
			});
		});
	}
};

var Period = {
	delete : function(id) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze periode wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_years/delete_period/' + id,
				type: 'DELETE',
				success: function(response) {
					Navigation.refresh();
					Notify.notify('Periode verwijderd');
				}
			});
		});
	}
};

var Ip = {
	delete : function(location_id, ip_id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit ip-adres wilt verwijderen?'
		}, function() {
			$.ajax({
				url: '/school_locations/delete_ip/' + location_id + '/' + ip_id,
				type: 'DELETE',
				success: function(response) {
					Navigation.refresh();
					Notify.notify('IP verwijderd');
				}
			});
		});
	}
};
