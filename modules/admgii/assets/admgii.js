(function ($) {
    /*$("form").on("keypress", function(e){
        //Enter key
        if (e.which == 13) {
            return false;
        }
    });*/

    // model generator: translate table name to model class
    $('#modelmodule-generator #generator-tablename').on('blur', function () {
        var $el = $(this);
        var tableName = $el.val();
        console.log(tableName);

        var tmp = $('#generator-modelclass').attr("data-template") || "";


        if ($('#generator-modelclass').val() === tmp && tableName && tableName.indexOf('*') === -1) {
            var modelClass = tmp + "\\";
                    console.log(modelClass);
            $.each(tableName.split('_'), function() {
                if(this.length>0)
                    modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
            });
            $('#generator-modelclass').val(modelClass).blur();
        }
    });


    $('#model-generator #generator-tablename').on('blur', function () {
        var tableName = $(this).val();
        if (tableName && tableName.indexOf('*') === -1) {
            var modelClass = '';
            $.each(tableName.split('_'), function() {
                if(this.length>0)
                    modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
            });
            $('#generator-modelclass').val(modelClass);
            $('#generator-modellangclass').val('');

        }
        $('#generator-messagecategory').val("model/" + tableName).prev(".sticky-value").text("model/" + tableName);
        if(!$("#generator-enablei18n").is(":checked")){
            $("#generator-enablei18n").trigger("click");
        }
    });

    $('#model-generator #generator-modellangclass').on('keypress', function (e) {
        var k = e.keyCode;
        if(k == 13){
            var model = $("#generator-modelclass").val();
            if (model && model.indexOf('*') === -1) {
                $('#generator-modellangclass').val(model + "Lang");
            }
            e.preventDefault();
            return false;
        }
    });


})(jQuery);
