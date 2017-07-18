$(function(){
	InitLeftMenu();
	tabClose();
	tabCloseEven();
})
//Download by http://www.codefans.net
//初始化左侧
function InitLeftMenu() {

    $(".easyui-accordion").empty();
    var menulist = "";
   
    $.each(_menus.menus, function(i, n) {
        menulist += '<div title="'+n.menuname+'"  icon="'+n.icon+'" style="overflow:auto;">';
		menulist += '<ul>';
        $.each(n.menus, function(j, o) {
			menulist += '<li><div><a target="mainFrame" href="' + o.url + '" ><span class="icon '+o.icon+'" ></span>' + o.menuname + '</a></div></li> ';
        })
        menulist += '</ul></div>';
    })

	$(".easyui-accordion").append(menulist);
	
	$('.easyui-accordion li a').click(function(){
		var tabTitle = $(this).text();
		var url = $(this).attr("href");
		addTab(tabTitle,url);
		$('.easyui-accordion li div').removeClass("selected");
		$(this).parent().addClass("selected");
	}).hover(function(){
		$(this).parent().addClass("hover");
	},function(){
		$(this).parent().removeClass("hover");
	});

	$(".easyui-accordion").accordion();
}

function addTab(subtitle,url){
	if(!$('#tabs').tabs('exists',subtitle)){
		$('#tabs').tabs('add',{
			title:subtitle,
			content:createFrame(url),
			closable:true,
			width:$('#mainPanle').width()-10,
			height:$('#mainPanle').height()-26
		});
	}else{
		$('#tabs').tabs('select',subtitle);
	}
	tabClose();
}

function createFrame(url)
{
	var s = '<iframe name="mainFrame" scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
	return s;
}


