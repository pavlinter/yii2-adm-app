var activeResponse = (function ($) {
	var pub = {
		/**
		 * Call ActiveResponse PHP controller
		 * @param {string} href controller/action to call
		 * @param {string|object} data $('#'+form_name).serialize()
		 * @param {object} ajaxOptions
		 */
		callAR: function (href, data, ajaxOptions) {
			var self = this;
			var data = data || {};

			// try to autodiscover current PHP file
			if (href === null) {
				href = location.href;
				if ((i = href.indexOf('?')) > 0) href = href.substring(0, i);
				if ((i = href.indexOf('&')) > 0) href = href.substring(0, i);
			}

			ajaxOptions = $.extend({
				type: $.isEmptyObject(data) ? "GET" : "POST",
				url: href,
				dataType: 'json',
				data: data
			}, ajaxOptions);

			if (ajaxOptions.type === "POST" && typeof ajaxOptions.data === 'object') {
				ajaxOptions.data[yii.getCsrfParam()] = yii.getCsrfToken();
			}

			var success = this.array_remove(ajaxOptions, 'success', function () {});
			var error = this.array_remove(ajaxOptions, 'error', false);
			var always = this.array_remove(ajaxOptions, 'always', function () {});

			var xhr = $.ajax(ajaxOptions).done(function (json) {
				if (json.disableActions === false) {
					self.parseActions(json.actions);
				}
				if (typeof(success) === 'function') {
					success(json, json.return2callback);
				} else {
					eval(success);
				}
			}).always(function (jqXHR, textStatus) {
				always(jqXHR, textStatus);
			}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
				if (typeof(error) === 'function') {
					error(XMLHttpRequest, textStatus, errorThrown);
				} else if (error === true) {
					alert('response:' + self.strip_tags(XMLHttpRequest.responseText) + ' error:' + textStatus + '. thrown:' + errorThrown);
				}
			});
			return xhr;
		},


        /**
         * Call ActiveResponse PHP controller
		 * @param {string} $form jquery element
         * @param {string|object} data $('#'+form_name).serialize()
         * @param {object} ajaxOptions
         */
        callARFile: function ($form, data, ajaxOptions) {
            var self = this;
            var data = data || {};
			var href = $form.attr("action");
            // try to autodiscover current PHP file
            if (href === null) {
                href = location.href;
                if ((i = href.indexOf('?')) > 0) href = href.substring(0, i);
                if ((i = href.indexOf('&')) > 0) href = href.substring(0, i);
            }

            ajaxOptions = $.extend({
                type: $.isEmptyObject(data) ? "GET" : "POST",
                url: href,
                dataType: 'json',
                data: data
            }, ajaxOptions);

            if (ajaxOptions.type === "POST" && typeof ajaxOptions.data === 'object') {
                ajaxOptions.data[yii.getCsrfParam()] = yii.getCsrfToken();
            }

            var success = this.array_remove(ajaxOptions, 'success', function () {});
            var error = this.array_remove(ajaxOptions, 'error', false);
            var always = this.array_remove(ajaxOptions, 'always', function () {});

            $form.ajaxSubmit(ajaxOptions);
            var xhr = $form.data('jqxhr');
            xhr.done(function (json) {
                if (json.disableActions === false) {
                    self.parseActions(json.actions);
                }
                if (typeof(success) === 'function') {
                    success(json, json.return2callback);
                } else {
                    eval(success);
                }
            }).always(function (jqXHR, textStatus) {
                always(jqXHR, textStatus);
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            	if (typeof(error) === 'function') {
                    error(XMLHttpRequest, textStatus, errorThrown);
                } else if (error === true) {
                    alert('response:' + self.strip_tags(XMLHttpRequest.responseText) + ' error:' + textStatus + '. thrown:' + errorThrown);
                }
            });
            return xhr;
        },

		parseActions: function (actions) {
			var self = this;
			for (var i in actions) {
				var action = actions[i];
				var r,
					funcName = "action" + self.ucfirst(action.act);
				if (self[funcName] !== 'undefined' && $.isFunction(self[funcName])) {
					if (action.condition !== false) {
						if (eval(action.condition)) {
							r = self[funcName](action);
						}
					} else {
						r = self[funcName](action);
					}
				} else {
					alert('Unknown action: ' + funcName);
				}
				if (r === false) {
					break;
				}
			}
		},
		array_remove: function (obj, el, def) {
			if (obj.hasOwnProperty(el)) {
				def = obj[el];
				delete obj[el];
			}
			return def;
		},
		ucfirst: function (string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		actionAlert: function (action) {
			alert(action.msg);
		},

		/**
		 * $(selector).method(val);
		 * @param {object}
		 */
		actionMethod: function (action) {
			var $el = $(action.selector);
			if ($el[action.method] !== 'undefined' && $.isFunction($el[action.method])) {
				return $el[action.method](action.val);
			}
		},
		actionMethodLoad: function (action) {
			var $el = $(action.selector);
			if ($el[action.method] !== 'undefined' && $.isFunction($el[action.method])) {
				var $html = $(action.val);
				$html.find("img").one("load", function(){
					$el[action.method]($html);
				});
			}
		},
		actionRedirect: function (action) {
			location.href = action.href;
			return false;
		},
		actionScript: function (action) {
			eval(action.script);
		},
		actionNotify: function (action) {
			$.notify(action.options, action.settings);
		},
		actionFormUpdateMessages: function (action) {
			$(action.form).yiiActiveForm('updateMessages', action.errors, action.summary);
		},
	}
	return pub;
})(jQuery);