function doAction(data){
    $.post("index.php", data, function(response){
        if(response.length) $(".ajax-success").show().stop().css("opacity", 1).fadeOut(17000).children().html(response);
    });
};
$(document).ajaxError(function(event, jqxhr, settings, thrownError){
    $(".ajax-error").show().children().html(jqxhr.responseText.replace(/\n/ig, "<br/>"));
}).ajaxStart(function(){
    $(".ajax-error, .ajax-success").hide();
}).on("keyup", function(ev){
    if(!$(ev.target).is("body")) return;
    var k = ev.keyCode.toString();
    for(var i in omxHotkeys){
        var s = omxHotkeys[i].key.split(",");
        if($.inArray(k, s) !== -1){
            $(".omx-buttons .button[data-shortcut='"+i+"']").trigger("click");
            break;
        }
    }
});
$(document).ready(function(){
    $(document).on("click", ".action[data-action]", function(ev){
        var el = $(ev.currentTarget);
        switch(el.attr("data-action")){
            case "toggle-next":
                el.next().toggle();
                break;
            case "save-paths":
                var data = {"action" : el.attr("data-action"), "text" : $("textarea.paths").val().trim()};
                doAction(data);
                break;
        }
    }).on("click", ".files .file", function(ev){
        $(".files .current").attr("data-path", $(this).attr("data-path")).children("span").html($(this).attr("data-path"));
        doAction({"action" : "shortcut", "shortcut" : "start", "path" : $(".files .current").attr("data-path")});
    }).on("click", ".omx-buttons .button[data-shortcut]", function(ev){
        doAction({"action" : "shortcut", "shortcut" : $(this).attr("data-shortcut"), "path" : $(".files .current").attr("data-path")});
    });
    $(".search").on("focus", function(){
        if(!$(this).attr("data-value")) $(this).attr("data-value", this.value);
        this.value = "";
    }).on("keyup blur", function(ev){
        if(ev.keyCode == 27){
            this.value = "";
            $(this).blur();
            return;
        }
        var v = this.value.trim();
        if(v.length <= 1){
            $(".results .file").show().each(function(){
                $(this).html($(this).attr("data-path"));
            });
            if(v.length == 0 && ev.type == "blur") $(this).val($(this).attr("data-value"));
            return;
        }
        var s = v.trim().split(" ");
        var sRegex = s;
        for(var i in sRegex) {
            sRegex[i] = {"regex" : new RegExp(sRegex[i].replace(/[^0-9a-z\/\*]/ig, "\\$&").replace(/\*/ig, ".*?"), "ig"), "val" : s[i]};
        }
        $(".results .file").hide().each(function(){
            var f = $(this);
            var p = f.attr("data-path");
            var html = p;
            var matches = [];
            sRegex.forEach(function(val){
                var m = p.match(val.regex);
                if(p.match(val.regex)){
                    f.show();
                    matches.push(m[0]);
                    html = html.replace(val.regex, "_"+(matches.length - 1)+"_");
                }
            });
            for(var i in matches){
                html = html.replace(new RegExp("_"+i+"_", "ig"), '<span class="match">'+matches[i]+'</span>');
            }
            f.html(html);
        });
    });
    $(".files .current").on("click", function(){
        $(".files .results").toggle();
    })
});