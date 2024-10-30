var minutesArray = ["Any mins", "Less than 500", "500 or more", "700 or more", "1000 or more", "1200 or more", "2000 or more"];
var minutesArrayPass = ["", "less499", "more500", "more700", "more1000", "more1200", "more2000"];
var textsArray = ["Any texts", "100 or more", "300 or more", "400 or more", "500 or more", "750 or more", "1000 or more"];
var textsArrayPass = ["", "more100", "more300", "more400", "more500", "more750", "more1000"];
var costArray = ["Any cost", "&pound;20 or less", "&pound;25 or less", "&pound;30 or less", "&pound;40 or less", "&pound;50 or less", "&pound;50 or more"];
var costArrayPass = ["", "less20", "less25", "less30", "less40", "less50", "more51"];
var dataArray = ["Any data", "500 MB or less", "500 MB or more", "1GB or more", "2GB or more","Unlimited"];
var dataArrayPass = ["", "less499", "more500", "more999", "more1999","more49999"];
var sortArray = ["Monthly Cost", "Total Cost", "Phone Cost"];
var sortArrayPass = ["", "totalcost", "phoneCost"];
jQuery.ajaxSetup({
    cache: false
});
jQuery.extend({
    getUrlVars: function() {
        var vars = [],
            hash;
        var hashes = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split("=");
            vars.push(hash[0]);
            vars[hash[0]] = hash[1]
        }
        return vars
    },
    getUrlVar: function(name) {
        return jQuery.getUrlVars()[name]
    }
});
jQuery(document).ready(function(jQuery) {
    var phone = jQuery("#phone_name").val();
    var gift = jQuery("#gift").val();
    var nw = "";
    var contract = "contracts";
    var cpbaseurl = jQuery("#cpbaseurl").text();
    var manu = jQuery("#manu").text();
    var mins = "";
    var texts = "";
    var cost = "";
    var data = "";
    var sort = "";
    if (jQuery("#CP_mobiles_search_widget").length != 0) {
        contract = jQuery('select[name="contract_type"]').val();
        mins = jQuery('select[name="minutes"]').val();
        texts = jQuery('select[name="texts"]').val();
        cost = jQuery('select[name="line_rental"]').val();
        data = jQuery('select[name="data"]').val();
        sort = jQuery('select[name="sort"]').val();
        if (contract == "payg") {
            jQuery("#newnavcontrols").hide();
            nw = "PAYG"
        } else {
            jQuery("#newnavcontrols").show();
            nw = jQuery('select[name="network"]').val()
        }
    } else if (nw == "PAYG") {
        jQuery("#newnavcontrols").hide();
        nw = "";
        contract = "payg"
    } else {
        jQuery("#newnavcontrols").show();
        contract = "contracts"
    }
    jQuery("#CP_mobiles_search_tabs a").removeClass("selectedTab").addClass("normalTab");
    if (nw == "All" | nw == "" | nw == "-") jQuery("#CP_mobiles_search_tabs li:first a").removeClass("normalTab").addClass("selectedTab");
    else jQuery('#CP_mobiles_search_tabs li a:contains("' + nw + '")').removeClass("normalTab").addClass("selectedTab");
    jQuery("#CP_mobiles_search_tabs a").each(function() {
        this.href = ""
    });
    jQuery("#CP_mobiles_search_tabs a").click(function(e) {
        e.preventDefault();
        jQuery("#CP_mobiles_search_tabs a").removeClass("selectedTab").addClass("normalTab");
        jQuery(this).removeClass("normalTab").addClass("selectedTab");
        nw = jQuery(this).text();
        if (nw == "All" | nw == "") nw = "";
        if (nw == "PAYG") {
            jQuery("#newnavcontrols").hide();
            nw = "";
            contract = "payg"
        } else {
            jQuery("#newnavcontrols").show();
            contract = "contracts"
        }
        update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")], costArrayPass[jQuery("#cost-slider").slider("value")], dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
    });

    function update_results(mins, texts, cost, data, sort, cashback) {
        jQuery("#results").html('<div id="loading" style="width: 100%; padding: 0 0 75px 0; position: inherit;"><p style="margin: 75px 0 20px; text-align: center;"><img src="' +
            cpbaseurl + '/modules/UK/mobiles/search/ajax-loader.gif"></p><p style="text-align: center; font-family: verdana, arial; font-size: 11px;">Loading Results</p></div>');
        if (nw != "") network = "&network=" + nw;
        else network = ""; if (mins != "") minutes = "&tabs_minutes=" + mins;
        else minutes = ""; if (texts != "") texts = "&tabs_texts=" + texts;
        else texts = ""; if (data != "") data = "&tabs_data=" + data;
        else data = ""; if (sort != "") sort = "&tabs_sort=" + sort;
        else sort = ""; if (phone != "" && phone != null) phone = "&phone=" + phone;
        else phone = ""; if (gift != "" && gift !=
            null) gift = "&gift=" + gift;
        else gift = ""; if (cost != "") cost = "&tabs_cost_per_month=" + cost;
        else cost = ""; if (manu != "") manu = "&manu=" + manu;
        else manu = "&manu="; if (contract == "payg") {
            contract = "&contract_type=" + contract;
            args = phone + sort + contract
        } else {
            contract = "&contract_type=" + contract;
            args = manu + network + minutes + phone + cost + texts + data + gift + sort + contract
        }
        jQuery.post(cpbaseurl + "/modules/UK/mobiles/search/mobiles_search_results_ajax.php", args, function(data) {
            jQuery("#results").fadeOut("fast", function() {
                jQuery("#results").fadeIn().html(data)
            })
        })
    }
    jQuery(function() {
        jQuery("#minutes-slider").slider({
            handle: "#minutes-handle",
            animate: "true",
            min: 0,
            max: 6,
            step: 1,
            value: jQuery.inArray(mins, minutesArrayPass),
            orientation: "vertical",
            slide: function(event, ui) {
                var value = ui.value;
                jQuery("#minutes-value").html(minutesArray[value]);
                jQuery("#minutes-pass").html(minutesArrayPass[value])
            },
            stop: function(event, ui) {
                var value = ui.value;
                update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")],
                    costArrayPass[jQuery("#cost-slider").slider("value")], dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
            }
        });
        jQuery("#texts-slider").slider({
            handle: "#texts-handle",
            animate: "true",
            min: 0,
            max: 6,
            step: 1,
            value: jQuery.inArray(texts, textsArrayPass),
            orientation: "vertical",
            slide: function(event, ui) {
                var value = ui.value;
                jQuery("#texts-value").html(textsArray[value]);
                jQuery("#texts-pass").html(textsArrayPass[value])
            },
            stop: function(event, ui) {
                var value = ui.value;
                update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")], costArrayPass[jQuery("#cost-slider").slider("value")], dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
            }
        });
        jQuery("#cost-slider").slider({
            handle: "#cost-handle",
            animate: "true",
            min: 0,
            max: 6,
            step: 1,
            value: jQuery.inArray(cost, costArrayPass),
            orientation: "vertical",
            slide: function(event, ui) {
                var value = ui.value;
                jQuery("#cost-value").html(costArray[value]);
                jQuery("#cost-pass").html(costArrayPass[value])
            },
            stop: function(event, ui) {
                var value = ui.value;
                update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")], costArrayPass[jQuery("#cost-slider").slider("value")], dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
            }
        });
        jQuery("#data-slider").slider({
            handle: "#data-handle",
            animate: "true",
            min: 0,
            max: 5,
            step: 1,
            value: jQuery.inArray(data,
                dataArrayPass),
            orientation: "vertical",
            slide: function(event, ui) {
                var value = ui.value;
                jQuery("#data-value").html(dataArray[value]);
                jQuery("#data-pass").html(dataArrayPass[value])
            },
            stop: function(event, ui) {
                var value = ui.value;
                update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")], costArrayPass[jQuery("#cost-slider").slider("value")], dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
            }
        });
        jQuery("#sort-slider").slider({
            handle: "#sort-handle",
            animate: "true",
            min: 0,
            max: 2,
            step: 1,
            value: jQuery.inArray(sort, sortArrayPass),
            orientation: "vertical",
            slide: function(event, ui) {
                var value = ui.value;
                jQuery("#sort-value").html(sortArray[value]);
                jQuery("#sort-pass").html(sortArrayPass[value])
            },
            stop: function(event, ui) {
                var value = ui.value;
                update_results(minutesArrayPass[jQuery("#minutes-slider").slider("value")], textsArrayPass[jQuery("#texts-slider").slider("value")], costArrayPass[jQuery("#cost-slider").slider("value")],
                    dataArrayPass[jQuery("#data-slider").slider("value")], sortArrayPass[jQuery("#sort-slider").slider("value")])
            }
        })
    });
    jQuery("#minutes-value").html(minutesArray[jQuery.inArray(mins, minutesArrayPass)]);
    jQuery("#minutes-pass").html(minutesArrayPass[jQuery.inArray(mins, minutesArrayPass)]);
    jQuery("#texts-value").html(textsArray[jQuery.inArray(texts, textsArrayPass)]);
    jQuery("#texts-pass").html(textsArrayPass[jQuery.inArray(texts, textsArrayPass)]);
    jQuery("#cost-value").html(costArray[jQuery.inArray(cost, costArrayPass)]);
    jQuery("#cost-pass").html(costArrayPass[jQuery.inArray(cost, costArrayPass)]);
    jQuery("#data-value").html(dataArray[jQuery.inArray(data, dataArrayPass)]);
    jQuery("#data-pass").html(dataArrayPass[jQuery.inArray(data, dataArrayPass)]);
    jQuery("#sort-value").html(sortArray[jQuery.inArray(sort, sortArrayPass)]);
    jQuery("#sort-pass").html(sortArrayPass[jQuery.inArray(sort, sortArrayPass)])
});