/* AJAX Search Kit (ASK) */
var ajax = new Array();

function getmodels(manu, siteroot, type)
{
    if(manu.length>0){
        var index = ajax.length;
        ajax[index] = new sack();

        ajax[index].requestFile = siteroot+'/wp-content/plugins/comparepress/modules/UK/mobiles/search/getmodels.php?manufacturer='+manu+'&type='+type;	// Specifying which file to get
        ajax[index].onCompletion = function(){
            createList(index)
        };	// Specify function that will be executed after file has been found
        ajax[index].runAJAX();		// Execute AJAX function
    } else {
        return false;
    }
}

function createList(index)
{
    eval(ajax[index].response);	// Executing the response from Ajax as Javascript code
}

function hidesearch(status) {
    //document.getElementById('manufacturer').selectedIndex=0;
    document.getElementById('manufacturer').options.selectedIndex = 0;
    document.getElementById('CP_mobiles_select_model').innerHTML = '<select name="phone"><option value="-">Model</option></select>';

    if (status == 'contracts') {
        document.getElementById('additionalsearch').style.display = 'inline';
    } else  {
        document.getElementById('additionalsearch').style.display = 'none';
    }

}

function returnSelection(theRadio) {
    var selection=null;
    for(var i=0; i < theRadio.length; i++) {
        if(theRadio[i].checked) {
            selection=theRadio[i].value;
            return selection;
        }
    }
    return selection;
}