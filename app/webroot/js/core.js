$(function() {
	Core.initialise();
	$.ajaxSetup({cache: false});
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
			Core.inApp = true;
			Core.appType = 'Chromebook';
		}

		$.ajax({
			url: '/test_takes/get_header_session',
			cache: false,
			type: 'POST',
			dataType: 'text',
			success: function(data) {
				// Notify.notify(data, 'success', 10000);
				Core.header = data;
				if(Core.header.indexOf('secure app') > 0) {
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
		Core.checkUnreadMessages();
	},

	checkUnreadMessages : function() {
		$('#messages .counter').hide();
		// only ask for unread if not on login page.
		if(Utils.notOnLoginScreen()) {
            $.get('/messages/unread',
                function (unread) {
                    if (unread > 0) {
                        $('#messages .counter').show().html(unread);
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

	lostFocus : function() {
		if(TestTake.active) {
		    if(TestTake.alert == false) {
                $.get('/test_takes/lost_focus');
                Core.lastLostFocusNotification = (new Date()).getTime()/1000;
                TestTake.startHeartBeat(TestTake.heartBeatCallback,Core.cheatIntervalInSeconds);
            } else {
		        var ref = (new Date()).getTime()/1000;
		        console.log('ref '+ref);
		        console.log('last '+Core.lastLostFocusNotification);
                if(Core.lastLostFocusNotification == false || Core.lastLostFocusNotification <= (ref - Core.lastLostFocusNotificationDelay)){
                    $.get('/test_takes/lost_focus');
                    Core.lastLostFocusNotification = (new Date()).getTime()/1000;
                }
            }
            TestTake.alert = true;
            TestTake.markBackground();
		}
	},

	screenshotDetected : function() {
		$.get('/test_takes/screenshot_detected');
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
        return ! this.onLoginScreen();
    },
};

var Dropdowns = {

	parentSelector : null,

	initialise : function() {
		$.each($('.dropblock-owner'), function() {
			$(this).unbind().click(function() {
				console.log($(this).attr('id'))
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
			$.get('/umbrella_organisations/delete/' + id,
				function() {
					Notify.notify('Organisatie verwijderd', 'info');
					Navigation.load('/school_locations');
				}
			);
		});
			}
};

var School = {
	delete : function(id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze schoolgemeenschap wilt verwijderen?'
		}, function() {
			$.get('/schools/delete/' + id,
				function() {
					Notify.notify('School verwijderd', 'info');
					Navigation.refresh();
				}
			);
		});
	}
};

var SchoolYear = {
	delete : function(id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit schooljaar wilt verwijderen?'
		}, function() {
			$.get('/school_years/delete/' + id,
				function() {
					Notify.notify('Schooljaar verwijderd', 'info');
					Navigation.refresh();
				}
			);
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
			$.get('/school_classes/delete_teacher/' + id,
				function() {
					Notify.notify('Docent verwijderd', 'info');
					Navigation.refresh();
				}
			);
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
			$.get('/school_locations/delete/' + id,
				function() {
					Notify.notify('Schoollocatie verwijderd', 'info');
					if(source == 0) {
						Navigation.refresh();
					}else{
						Navigation.back();
					}
				}
			);
		});
	},

	deleteLicense : function(location_id, license_id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u dit licentiepakket wilt verwijderen?'
		}, function() {
			$.get('/school_locations/delete_licence/' + location_id + '/' + license_id,
				function() {
					Notify.notify('Licentiepakket verwijderd', 'info');
					Navigation.refresh();
				}
			);
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
			$.get('/school_classes/delete/' + id,
				function() {
					Notify.notify('Klas verwijderd', 'info');
					if(source == 0) {
						Navigation.refresh();
					}else{
						Navigation.back();
					}
				}
			);
		});
	},

	removeMentor : function(class_id, id) {
		Popup.message({
				btnOk: 'Ja',
				btnCancel: 'Annuleer',
				title: 'Weet u het zeker?',
				message: 'Weet u zeker dat u dit persoon wilt verwijderen?'
			}, function() {
				$.get('/school_classes/remove_mentor/' + class_id + '/' + id,
					function () {
						Navigation.refresh();
					}
				);
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
				$.get('/school_classes/remove_manager/' + class_id + '/' + id,
					function () {
						Navigation.refresh();
					}
				);
			}
		);
	},

	removeTeacher : function(id) {
		$.get('/school_classes/remove_teacher/' + id,
			function() {
				Navigation.refresh();
			}
		);
	},

	removeStudent : function(class_id, id) {
		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze student wilt verwijderen?'
		}, function() {
				$.get('/school_classes/remove_student/' + class_id + '/' + id,
					function () {
						Navigation.refresh();
					}
				);
			}
		);
	}
};

var Section = {
	delete : function(id) {

		Popup.message({
			btnOk: 'Ja',
			btnCancel: 'Annuleer',
			title: 'Weet u het zeker?',
			message: 'Weet u zeker dat u deze sectie wilt verwijderen?'
		}, function() {
			$.get('/sections/delete/' + id,
				function() {
					Notify.notify('Sectie verwijderd', 'info');
					Navigation.refresh();
				}
			);
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
			$.get('/sections/delete_subject/' + id,
				function() {
					Notify.notify('Vak verwijderd', 'info');
					Navigation.refresh();
				}
			);
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

			$.get('/contacts/delete/' + owner + '/' + owner_id + '/' + type + '/' + id,
				function() {
					Navigation.refresh();
					Notify.notify('Contact verwijderd');
				}
			);
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
			$.get('/school_years/delete_period/' + id,
				function() {
					Navigation.refresh();
					Notify.notify('Ip verwijderd');
				}
			);

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
			$.get('/school_locations/delete_ip/' + location_id + '/' + ip_id,
				function() {
					Navigation.refresh();
					Notify.notify('Contact verwijderd');
				}
			);
		});
	}
};
