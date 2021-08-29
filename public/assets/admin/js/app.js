if("undefined"==typeof jQuery)throw new Error("AdminLTE requires jQuery");function _init(){"use strict";$.AdminLTE.layout={activate:function(){var e=this;e.fix(),e.fixSidebar(),$(window,".wrapper").resize((function(){e.fix(),e.fixSidebar()}))},fix:function(){var e=$(".main-header").outerHeight()+$(".main-footer").outerHeight(),t=$(window).height(),i=$(".sidebar").height();if($("body").hasClass("fixed"))$(".content-wrapper, .right-side").css("min-height",t-$(".main-footer").outerHeight());else{var o;t>=i?($(".content-wrapper, .right-side").css("min-height",t-e),o=t-e):($(".content-wrapper, .right-side").css("min-height",i),o=i);var n=$($.AdminLTE.options.controlSidebarOptions.selector);void 0!==n&&n.height()>o&&$(".content-wrapper, .right-side").css("min-height",n.height())}},fixSidebar:function(){$("body").hasClass("fixed")?(void 0===$.fn.slimScroll&&window.console&&window.console.error("Error: the fixed layout requires the slimscroll plugin!"),$.AdminLTE.options.sidebarSlimScroll&&void 0!==$.fn.slimScroll&&($(".sidebar").slimScroll({destroy:!0}).height("auto"),$(".sidebar").slimscroll({height:$(window).height()-$(".main-header").height()+"px",color:"rgba(0,0,0,0.2)",size:"3px"}))):void 0!==$.fn.slimScroll&&$(".sidebar").slimScroll({destroy:!0}).height("auto")}},$.AdminLTE.pushMenu={activate:function(e){var t=$.AdminLTE.options.screenSizes;$(e).on("click",(function(e){e.preventDefault(),$(window).width()>t.sm-1?$("body").hasClass("sidebar-collapse")?$("body").removeClass("sidebar-collapse").trigger("expanded.pushMenu"):$("body").addClass("sidebar-collapse").trigger("collapsed.pushMenu"):$("body").hasClass("sidebar-open")?$("body").removeClass("sidebar-open").removeClass("sidebar-collapse").trigger("collapsed.pushMenu"):$("body").addClass("sidebar-open").trigger("expanded.pushMenu")})),$(".content-wrapper").click((function(){$(window).width()<=t.sm-1&&$("body").hasClass("sidebar-open")&&$("body").removeClass("sidebar-open")})),($.AdminLTE.options.sidebarExpandOnHover||$("body").hasClass("fixed")&&$("body").hasClass("sidebar-mini"))&&this.expandOnHover()},expandOnHover:function(){var e=this,t=$.AdminLTE.options.screenSizes.sm-1;$(".main-sidebar").hover((function(){$("body").hasClass("sidebar-mini")&&$("body").hasClass("sidebar-collapse")&&$(window).width()>t&&e.expand()}),(function(){$("body").hasClass("sidebar-mini")&&$("body").hasClass("sidebar-expanded-on-hover")&&$(window).width()>t&&e.collapse()}))},expand:function(){$("body").removeClass("sidebar-collapse").addClass("sidebar-expanded-on-hover")},collapse:function(){$("body").hasClass("sidebar-expanded-on-hover")&&$("body").removeClass("sidebar-expanded-on-hover").addClass("sidebar-collapse")}},$.AdminLTE.tree=function(e){var t=this,i=$.AdminLTE.options.animationSpeed;$(document).on("click",e+" li a",(function(e){var o=$(this),n=o.next();if(n.is(".treeview-menu")&&n.is(":visible"))n.slideUp(i,(function(){n.removeClass("menu-open")})),n.parent("li").removeClass("active");else if(n.is(".treeview-menu")&&!n.is(":visible")){var a=o.parents("ul").first();a.find("ul:visible").slideUp(i).removeClass("menu-open");var s=o.parent("li");n.slideDown(i,(function(){n.addClass("menu-open"),a.find("li.active").removeClass("active"),s.addClass("active"),t.layout.fix()}))}n.is(".treeview-menu")&&e.preventDefault()}))},$.AdminLTE.controlSidebar={activate:function(){var e=this,t=$.AdminLTE.options.controlSidebarOptions,i=$(t.selector);$(t.toggleBtnSelector).on("click",(function(o){o.preventDefault(),i.hasClass("control-sidebar-open")||$("body").hasClass("control-sidebar-open")?e.close(i,t.slide):e.open(i,t.slide)}));var o=$(".control-sidebar-bg");e._fix(o),$("body").hasClass("fixed")?e._fixForFixed(i):$(".content-wrapper, .right-side").height()<i.height()&&e._fixForContent(i)},open:function(e,t){t?e.addClass("control-sidebar-open"):$("body").addClass("control-sidebar-open")},close:function(e,t){t?e.removeClass("control-sidebar-open"):$("body").removeClass("control-sidebar-open")},_fix:function(e){var t=this;$("body").hasClass("layout-boxed")?(e.css("position","absolute"),e.height($(".wrapper").height()),$(window).resize((function(){t._fix(e)}))):e.css({position:"fixed",height:"auto"})},_fixForFixed:function(e){e.css({position:"fixed","max-height":"100%",overflow:"auto","padding-bottom":"50px"})},_fixForContent:function(e){$(".content-wrapper, .right-side").css("min-height",e.height())}},$.AdminLTE.boxWidget={selectors:$.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,icons:$.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,animationSpeed:$.AdminLTE.options.animationSpeed,activate:function(e){var t=this;e||(e=document),$(e).on("click",t.selectors.collapse,(function(e){e.preventDefault(),t.collapse($(this))})),$(e).on("click",t.selectors.remove,(function(e){e.preventDefault(),t.remove($(this))}))},collapse:function(e){var t=this,i=e.parents(".box").first(),o=i.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");i.hasClass("collapsed-box")?(e.children(":first").removeClass(t.icons.open).addClass(t.icons.collapse),o.slideDown(t.animationSpeed,(function(){i.removeClass("collapsed-box")}))):(e.children(":first").removeClass(t.icons.collapse).addClass(t.icons.open),o.slideUp(t.animationSpeed,(function(){i.addClass("collapsed-box")})))},remove:function(e){e.parents(".box").first().slideUp(this.animationSpeed)}}}$.AdminLTE={},$.AdminLTE.options={navbarMenuSlimscroll:!0,navbarMenuSlimscrollWidth:"3px",navbarMenuHeight:"200px",animationSpeed:200,sidebarToggleSelector:"[data-toggle='offcanvas']",sidebarPushMenu:!0,sidebarSlimScroll:!0,sidebarExpandOnHover:!1,enableBoxRefresh:!0,enableBSToppltip:!0,BSTooltipSelector:"[data-toggle='tooltip']",enableFastclick:!0,enableControlSidebar:!0,controlSidebarOptions:{toggleBtnSelector:"[data-toggle='control-sidebar']",selector:".control-sidebar",slide:!0},enableBoxWidget:!0,boxWidgetOptions:{boxWidgetIcons:{collapse:"fa-minus",open:"fa-plus",remove:"fa-times"},boxWidgetSelectors:{remove:'[data-widget="remove"]',collapse:'[data-widget="collapse"]'}},directChat:{enable:!0,contactToggleSelector:'[data-widget="chat-pane-toggle"]'},colors:{lightBlue:"#3c8dbc",red:"#f56954",green:"#00a65a",aqua:"#00c0ef",yellow:"#f39c12",blue:"#0073b7",navy:"#001F3F",teal:"#39CCCC",olive:"#3D9970",lime:"#01FF70",orange:"#FF851B",fuchsia:"#F012BE",purple:"#8E24AA",maroon:"#D81B60",black:"#222222",gray:"#d2d6de"},screenSizes:{xs:480,sm:768,md:992,lg:1200}},$((function(){"use strict";$("body").removeClass("hold-transition"),"undefined"!=typeof AdminLTEOptions&&$.extend(!0,$.AdminLTE.options,AdminLTEOptions);var e=$.AdminLTE.options;_init(),$.AdminLTE.layout.activate(),$.AdminLTE.tree(".sidebar"),e.enableControlSidebar&&$.AdminLTE.controlSidebar.activate(),e.navbarMenuSlimscroll&&void 0!==$.fn.slimscroll&&$(".navbar .menu").slimscroll({height:e.navbarMenuHeight,alwaysVisible:!1,size:e.navbarMenuSlimscrollWidth}).css("width","100%"),e.sidebarPushMenu&&$.AdminLTE.pushMenu.activate(e.sidebarToggleSelector),e.enableBSToppltip&&$("body").tooltip({selector:e.BSTooltipSelector}),e.enableBoxWidget&&$.AdminLTE.boxWidget.activate(),e.enableFastclick&&"undefined"!=typeof FastClick&&FastClick.attach(document.body),e.directChat.enable&&$(document).on("click",e.directChat.contactToggleSelector,(function(){$(this).parents(".direct-chat").first().toggleClass("direct-chat-contacts-open")})),$('.btn-group[data-toggle="btn-toggle"]').each((function(){var e=$(this);$(this).find(".btn").on("click",(function(t){e.find(".btn.active").removeClass("active"),$(this).addClass("active"),t.preventDefault()}))}))})),function(e){"use strict";e.fn.boxRefresh=function(t){var i=e.extend({trigger:".refresh-btn",source:"",onLoadStart:function(e){return e},onLoadDone:function(e){return e}},t),o=e('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');return this.each((function(){if(""!==i.source){var t=e(this);t.find(i.trigger).first().on("click",(function(e){e.preventDefault(),function(e){e.append(o),i.onLoadStart.call(e)}(t),t.find(".box-body").load(i.source,(function(){!function(e){e.find(o).remove(),i.onLoadDone.call(e)}(t)}))}))}else window.console&&window.console.log("Please specify a source first - boxRefresh()")}))}}(jQuery),function(e){"use strict";e.fn.activateBox=function(){e.AdminLTE.boxWidget.activate(this)}}(jQuery),function(e){"use strict";e.fn.todolist=function(t){var i=e.extend({onCheck:function(e){return e},onUncheck:function(e){return e}},t);return this.each((function(){void 0!==e.fn.iCheck?(e("input",this).on("ifChecked",(function(){var t=e(this).parents("li").first();t.toggleClass("done"),i.onCheck.call(t)})),e("input",this).on("ifUnchecked",(function(){var t=e(this).parents("li").first();t.toggleClass("done"),i.onUncheck.call(t)}))):e("input",this).on("change",(function(){var t=e(this).parents("li").first();t.toggleClass("done"),e("input",t).is(":checked")?i.onCheck.call(t):i.onUncheck.call(t)}))}))}}(jQuery),function(e,t,i){"use strict";t.BuzzyAdmin=t.BuzzyAdmin||{},t.BuzzyAdmin={return_error:function(e){var t=void 0!==e.responseJSON?e.responseJSON.errors:"An Unexpected Error Occurred";swal({title:"Error",text:t,type:"error",html:!0,showCancelButton:!1})},init:function(){var i=this;e(".permanently").on("click",(function(){var t=e(this).attr("href");return swal({title:"Are you sure?",text:"You will not be able to recover this.",type:"warning",showCancelButton:!0,closeOnConfirm:!1,closeOnCancel:!1,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes, delete it!",showLoaderOnConfirm:!0},(function(e){e?setTimeout((function(){location.href=t}),100):swal("Cancelled","All data is safe :)","error")})),!1})),e(".sendtrash").on("click",(function(){var t=e(this).attr("href");return swal({title:"Are you sure?",text:"Sending to trash!",type:"warning",showCancelButton:!0,closeOnConfirm:!1,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes!",showLoaderOnConfirm:!0},(function(){setTimeout((function(){location.href=t}),500)})),!1})),e(".download-item").off("click").on("click",(function(){var t=e(this).data("item-code"),o=e(this).data("item-id"),n=e(this).data("version");return swal({title:"Processing Update...",text:"It will take few seconds.",timer:82e3,allowClickOutside:!1,showConfirmButton:!1}),e.ajax({type:"POST",dataType:"json",url:buzzy_base_url+"/admin/handle-download",data:{item_code:t,item_id:o,item_version:n,_token:e("#requesttoken").val()},success:function(e){var t=e.status;return"redirect"==t?(location.href=e.redirect,!1):"error"==t?(swal({title:"Failed",text:e.message,showConfirmButton:!0}),!1):void swal({title:e.message,type:"success",showCancelButton:!1,allowClickOutside:!1,closeOnConfirm:!0},(function(e){location.reload()}))},error:i.return_error}),!1})),e(".activate-item").on("click",(function(){var t=e(this).parents(".item-actions"),o=t.data("item-code"),n=t.data("item-id"),a=t.data("item-type"),s=e(this).parents(".box-widget").find(".overlay");s.removeClass("hide"),e.ajax({type:"POST",dataType:"json",url:buzzy_base_url+"/admin/activate-"+a,data:{item_code:o,item_id:n,_token:e("#requesttoken").val()},success:function(e){var t=e.status;"error"==t?swal({type:"warning",title:"Error",text:e.message,timer:3e3,showConfirmButton:!1}):"success"==t&&location.reload()},error:i.return_error}).always((function(){s.addClass("hide")}))}));var o=new URL(t.location),n=o.searchParams.get("purchase_code"),a=o.searchParams.get("item_id"),s=o.searchParams.get("api_status");e(".register-item").on("click",(function(t){t.preventDefault();e(this).parents(".item-actions").data("item-code");var o=e(this).data("item-img"),a=e(this).data("item-id"),s=e(this).data("item-name"),r=e(this).data("item-buy");i.registerAction({title:"Activate Product!",text:'Please enter your <a href="'+r+'" target="_blank" style="color:blue;font-weight: bold">'+s+'</a> Envato Purchase Code or <a href="https://codecanyon.net/item/buzzy-bundle-viral-media-script/18754835" target="_blank" style="color:blue;font-weight: bold">Buzzy Bundle</a> purchase code to activate this product. ',showCancelButton:!0,imageUrl:o,item_id:a,inputValue:n})})),"error"===s?(n="",alert(o.searchParams.get("api_error"))):n>""&&(buzzy_item_id===a?(e(t).trigger("activate:toggle"),e(t).trigger("register:toggle")):e('a.register-item[data-item-id="'+a+'"]').trigger("click"),setTimeout((()=>{e(".sweet-alert button.confirm").trigger("click")}),1500)),e(t).on("activate:toggle",(function(){i.registerAction({title:"Activate your Buzzy!",text:'Unfortanately, System can\'t validate your purchase code. Please enter your <a href="https://codecanyon.net/item/buzzy-news-viral-lists-polls-and-videos/13300279" target="_blank" style="color:blue;font-weight: bold">Buzzy</a> or <a href="https://codecanyon.net/item/buzzy-bundle-viral-media-script/18754835" target="_blank" style="color:blue;font-weight: bold">Buzzy Bundle</a> Purchase Code to access admin panel functions and new updates.',showCancelButton:!1,item_id:buzzy_item_id,inputValue:n,imageUrl:null})})),e(t).on("register:toggle",(function(){i.registerAction({title:"Activate your Buzzy<br> to Start Install!",text:'Please enter your <a href="https://codecanyon.net/item/buzzy-news-viral-lists-polls-and-videos/13300279" target="_blank" style="color:blue;font-weight: bold">Buzzy</a> or <a href="https://codecanyon.net/item/buzzy-bundle-viral-media-script/18754835" target="_blank" style="color:blue;font-weight: bold">Buzzy Bundle</a> Purchase Code to get start to install.',showCancelButton:!1,item_id:buzzy_item_id,inputValue:n,imageUrl:null})}))},registerAction:function(t){var i=this;swal({title:t.title,text:t.text,type:"input",inputValue:t.inputValue,imageUrl:t.imageUrl,showCancelButton:t.showCancelButton,closeOnConfirm:!1,html:!0,animation:"slide-from-top",inputPlaceholder:"Enter Purchase Code",showLoaderOnConfirm:!0},(function(o){return!1!==o&&(""===o?(swal.showInputError("You need to write something!"),!1):void e.ajax({type:"POST",dataType:"json",url:buzzy_base_url+"/register_product",data:{item_id:t.item_id,code:o,_token:e("#requesttoken").val()},success:function(e){var n=e.status;if("error"==n)return swal.showInputError(e.message),!1;"auth"==n?i.initAuthAction(e.data.auth_url,e.data.auth_message,o,t.item_id):"success"==n&&location.reload()},error:function(e){var t=void 0!==e.responseJSON?e.responseJSON.errors:"An Unexpected Error Occurred";swal.showInputError(t)}}))}))},initAuthAction:function(e,t,i,o){var n=buzzy_current_url+"?purchase_code="+i+"&item_id="+o;location.href=e+"?redirectTo="+encodeURIComponent(n)+"&purchase_code="+i}},e(i).ready((function(){BuzzyAdmin.init()}))}(jQuery,window,document);
