function CropAvatar($element, settings) {

  var settings = $.extend({
    cropOptions: {},
    cropEvents: {},
    popupSelector: '#avatar-modal-btn'
  }, settings);

  this.cropOptions = settings.cropOptions;
  this.cropEvents = settings.cropEvents;
  this.popupSelector = settings.popupSelector;
  this.$container = $element;
  this.$avatarView = this.$container.find('.avatar-view');
  this.$avatar = this.$avatarView.find('img');
  this.$avatarModalBtn = this.$container.find(this.popupSelector);

  this.$loading = this.$container.find('.loading');

  this.$avatarForm = this.$container.find('.avatar-form');
  this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
  this.$avatarMessage = this.$avatarForm.find('.avatar-message');

  this.$avatarSrc = this.$avatarForm.find('.avatar-src');
  this.$avatarData = this.$avatarForm.find('.avatar-data');
  this.$avatarInput = this.$avatarForm.find('.avatar-input');
  this.$avatarSave = this.$avatarForm.find('.avatar-save');
  this.$avatarBtns = this.$avatarForm.find('.avatar-btns');

  this.$avatarWrapper = this.$container.find('.avatar-wrapper');
  this.$avatarPreview = this.$container.find('.avatar-preview');

  this.init();
}

CropAvatar.prototype = {
  constructor: CropAvatar,

  support: {
    fileList: !!$('<input type="file">').prop('files'),
    blobURLs: !!window.URL && URL.createObjectURL,
    formData: !!window.FormData
  },

  init: function () {
    this.support.datauri = this.support.fileList && this.support.blobURLs;

    if (!this.support.formData) {
      this.initIframe();
    }

    this.initTooltip();
    this.initModal();
    this.addListener();
  },

  addListener: function () {
    this.$avatarView.on('click', $.proxy(this.click, this));
    this.$avatarInput.on('change', $.proxy(this.change, this));
    this.$avatarForm.on('submit', $.proxy(this.submit, this));
    this.$avatarBtns.on('click', $.proxy(this.rotate, this));
  },

  initTooltip: function () {
    this.$avatarView.tooltip({
      placement: 'bottom'
    });
  },

  initModal: function () {

  },

  initPreview: function () {
    var url = this.$avatar.attr('src');

    this.$avatarPreview.html('<img src="' + url + '">');
  },

  initIframe: function () {
    var target = 'upload-iframe-' + (new Date()).getTime();
    var $iframe = $('<iframe>').attr({
      name: target,
      src: ''
    });
    var _this = this;

    // Ready ifrmae
    $iframe.one('load', function () {

      // respond response
      $iframe.on('load', function () {
        var data;

        try {
          data = $(this).contents().find('body').text();
        } catch (e) {
          console.log(e.message);
        }

        if (data) {
          try {
            data = $.parseJSON(data);
          } catch (e) {
            console.log(e.message);
          }

          _this.submitDone(data);
        } else {
          _this.submitFail('Image upload failed!');
        }

        _this.submitEnd();

      });
    });

    this.$iframe = $iframe;
    this.$avatarForm.attr('target', target).after($iframe.hide());
  },

  click: function () {
    this.$avatarModalBtn.magnificPopup('open');
    this.initPreview();
  },

  change: function () {
    var files;
    var file;

    if (this.support.datauri) {
      files = this.$avatarInput.prop('files');

      if (files.length > 0) {
        file = files[0];

        if (this.isImageFile(file)) {
          if (this.url) {
            URL.revokeObjectURL(this.url); // Revoke the old one
          }

          this.url = URL.createObjectURL(file);
          this.startCropper();
        }
      }
    } else {
      file = this.$avatarInput.val();

      if (this.isImageFile(file)) {
        this.syncUpload();
      }
    }
  },

  submit: function () {
    if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
      return false;
    }
    if (this.support.formData) {
      this.ajaxUpload();
      return false;
    }
  },

  rotate: function (e) {
    var data;
    if (this.active) {
      data = $(e.target).data();
      if (data.cropmethod) {
        this.$img.cropper(data.cropmethod, data.option);
      }
    }
  },

  isImageFile: function (file) {
    if (file.type) {
      return /^image\/\w+$/.test(file.type);
    } else {
      return /\.(jpg|jpeg|png|gif)$/.test(file);
    }
  },

  startCropper: function () {
    var _this = this;

    if (this.active) {
      this.$img.cropper('replace', this.url);
    } else {
      this.$img = $('<img src="' + this.url + '">');
      this.$avatarWrapper.empty().html(this.$img);
      this.$img.cropper($.extend({
        zoom: function (e) {
          var cropBox = $(this).cropper('getCropBoxData');
          var data = {
            width: cropBox.width,
            height: cropBox.height
          };

          $.each(data, function (i, n) {
            data[i] = n / e.ratio;
          });
          if (data.width < 600 || data.height < 600) {
            e.preventDefault(); // Prevent zoom in again
          }
        },
        crop: function (e) {
          var json = [
            '{"x":' + e.x,
            '"y":' + e.y,
            '"height":' + e.height,
            '"width":' + e.width,
            '"rotate":' + e.rotate + '}'
          ].join();
          _this.$avatarData.val(json);
        }},this.cropOptions));
      this.active = true;
      for (var eventName in this.cropEvents) {
        this.$img.cropper(eventName, this.cropEvents[eventName]);
      }
    }

    this.$avatarModalBtn.one('mfpBeforeClose', function() {
      _this.$avatarInput.val("");
      _this.$avatarMessage.text("");
      _this.$avatarPreview.empty();
      _this.stopCropper();
    });
  },

  stopCropper: function () {
    if (this.active) {
      this.$img.cropper('destroy');
      this.$img.remove();
      this.active = false;
    }
  },

  ajaxUpload: function () {
    var url = this.$avatarForm.attr('action');
    var data = new FormData(this.$avatarForm[0]);
    data.append(yii.getCsrfParam(), yii.getCsrfToken());
    var _this = this;

    $.ajax(url, {
      type: 'post',
      data: data,
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function () {
        _this.submitStart();
      },
      success: function (data) {
        _this.submitDone(data);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        _this.submitFail(textStatus || errorThrown);
      },
      complete: function () {
        _this.submitEnd();
      }
    });
  },

  syncUpload: function () {
    this.$avatarSave.click();
  },

  submitStart: function () {
    this.$loading.fadeIn();
  },

  submitDone: function (data) {
    if ($.isPlainObject(data) && data.state === 200) {
      if (data.result) {
        this.url = data.result;
        if (this.support.datauri || this.uploaded) {
          this.uploaded = false;
          this.cropDone();
        } else {
          this.uploaded = true;
          this.$avatarSrc.val(this.url);
          this.startCropper();
        }

        this.$avatarInput.val('');
      } else if (data.message) {
        this.alert(data.message);
      }
    } else {
      this.alert('Failed to response');
    }
  },

  submitFail: function (msg) {
    this.alert(msg);
  },

  submitEnd: function () {
    this.$loading.fadeOut();
  },

  cropDone: function () {
    this.$avatarForm.get(0).reset();
    this.$avatar.attr('src', this.url);
    this.stopCropper();
    this.$avatarModalBtn.magnificPopup('close');
  },

  alert: function (msg) {
    var $alert = [
      '<div class="alert alert-danger avatar-alert alert-dismissable">',
      '<button type="button" class="close" data-dismiss="alert">&times;</button>',
      msg,
      '</div>'
    ].join('');
    this.$avatarMessage.html($alert);
  }
};