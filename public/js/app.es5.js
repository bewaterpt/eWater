"use strict";var _vue=_interopRequireDefault(require("vue")),_laraform=_interopRequireDefault(require("laraform")),_this=void 0;function _interopRequireDefault(t){return t&&t.__esModule?t:{default:t}}function _typeof(t){return(_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function stopSpontaneousSrcolling(){$('a[href="#"]').click(function(t){t.preventDefault()})}require("./bootstrap"),_vue.default.use(_laraform.default),$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),stopSpontaneousSrcolling(),require("./config/tinymce/lang/pt_PT"),$('button[type="submit"]').on("click",function(t){return $(t.target).find('button[type="submit"]').attr("disabled",!0),!0}),$(function(){$('[data-toggle="popover"]').popover({html:!0,title:function(){return console.log(this),$(document).find("#"+this.id+" .popover").find("#title").html()},content:function(){return $(document).find("#"+this.id+" .popover").find("#content").html()}}),$("[data-onload]").each(function(){console.log(this),customOnload(this,$(this).attr("data-onload"))})}),$(function(){if($("textarea.text-editor").length>0){var t={path_absolute:"/",selector:"textarea.text-editor",language:"pt_PT",menubar:!1,statusbar:!1,plugins:["advlist autolink lists link image charmap print preview hr anchor pagebreak","searchreplace visualblocks visualchars code","insertdatetime media nonbreaking table directionality","emoticons template paste textpattern"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",relative_urls:!1,file_browser_callback:function(e,a,n,o){var r=window.innerWidth||document.documentElement.clientWidth||document.getElementsByTagName("body")[0].clientWidth,i=window.innerHeight||document.documentElement.clientHeight||document.getElementsByTagName("body")[0].clientHeight,l=t.path_absolute+"laravel-filemanager?field_name="+e;l+="image"==n?"&type=Images":"&type=Files",tinyMCE.activeEditor.windowManager.open({file:l,title:"Filemanager",width:.8*r,height:.8*i,resizable:"yes",close_previous:"no"})}};tinyMCE.init(t),window.mce=tinyMCE.init(t)}}),$(function(){$("table[id^=datatable] tfoot .dt-search").find("select, input").each(function(){$(_this).on("keyup change",function(){!function(t,e,a){var n=this;dataTable=$("#"+t).DataTable(),jQTable=$(t),jQTable.find("tfoot .dt-search").each(function(){dataTable.column($(n).index()).search("")}),dataTable.column(a).search(e).draw()}($(_this).parents("table")[0].id,_this.value,$(_this).parent("td").index())})})}),$(function(){var t=setInterval(function(){$("#datatable-users").length>0&&($("#datatable-users").dataTable({responsive:!0,order:[[1,"asc"]],columnDefs:[{targets:$("#datatable-users").find("thead tr:first th.actions").index(),orderable:!1}],language:{url:"/config/dataTables/lang/"+window.lang+".json"}}),clearInterval(t))},1e3)}),$(function(){$("#modalTeamUsers").on("show.bs.modal",function(t){var e="";void 0!==$(t.relatedTarget).data("id")&&(e=$(t.relatedTarget).data("id")),$(t.target).find("#content .body").html(""),$(t.target).find("#modal-spinner").removeClass("d-none"),$currAjax=$.ajax({method:"POST",url:"/teams/get-users",data:JSON.stringify({id:e,raw:!1}),contentType:"json",success:function(e){e=JSON.parse(e),$(t.target).find("#content .body").html(e.content),$(t.target).find("#modal-spinner").addClass("d-none")},error:function(e,a,n){$(t.target).find("#modal-spinner").addClass("d-none"),alert(n)},complete:function(){$(t.target).find("#modal-spinner").addClass("d-none")}})}),""!==$("#teams-colorpicker").find("input").val()?$("#teams-colorpicker").colorpicker({}):$("#teams-colorpicker").colorpicker({color:getRandomVibrantColor(20)})}),$(function(){var t=setInterval(function(){$("#datatable-teams").length>0&&($("#datatable-teams").dataTable({responsive:!0,order:[[1,"asc"]],columnDefs:[{targets:$("#datatable-teams").find("thead tr:first th.actions").index(),orderable:!1}],language:{url:"/config/dataTables/lang/"+window.lang+".json"}}),clearInterval(t))},1e3)}),$(function(){if($("#settings-permissions").length>0){$("#settings-permissions").on("submit",function(t){t.preventDefault(),$(t.target).find('button[type="submit"]').find("#spinner, #spinner-text").removeClass("d-none"),$(t.target).find('button[type="submit"]').find(".btn-text").addClass("d-none");var e=[];$("#settings-permissions .permission-value .square.green").each(function(t){if($(this).prop("checked")){var a=((n=(n=$(this).attr("name")).split("]["))[0]=n[0].replace("[",""),n[1]=n[1].replace("]",""),{id:n[0],route:n[1]});e.push({id:a.id,route:a.route})}var n}),function(t,e){$.ajax({url:window.origin+"/permissions/update",method:"POST",data:JSON.stringify({data:t}),dataType:"json"}).done(function(t){$(e.target).find('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$(e.target).find('button[type="submit"]').find(".btn-text").removeClass("d-none")})}(e,t)})}}),$(function(){$(".multiselect-listbox").length>0&&($(".multiselect-listbox #btnContainer #addItems").on("click",function(t){t.preventDefault(),console.log(t.target),$(t.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").appendTo($(t.target).closest(".multiselect-listbox").find("#selectRight")[0]).prop("selected",!1),$(t.target).closest(".multiselect-listbox").find("#selectLeft").find(":selected").remove()}),$(".multiselect-listbox #btnContainer #removeItems").on("click",function(t){t.preventDefault(),console.log(t.target),$(t.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").appendTo($(t.target).closest(".multiselect-listbox").find("#selectLeft")[0]).prop("selected",!1),$(t.target).closest(".multiselect-listbox").find("#selectRight").find(":selected").remove()}));var t=$($(".multiselect-listbox")[0]).parent("form");console.log(t),t.on("submit",function(e){e.preventDefault(),t.find(".multiselect-listbox").each(function(e,a){t.find("input#"+$(a).attr("data-field")).val(""),$(a).find("#selectRight option").each(function(e,n){e===$(a).find("#selectRight option").length-1?t.find("input#"+$(a).attr("data-field")).val(t.find("input#"+$(a).attr("data-field")).val()+n.value):t.find("input#"+$(a).attr("data-field")).val(t.find("input#"+$(a).attr("data-field")).val()+n.value+", ")})}),t[0].submit()})}),$(function(){$("a[data-toggle='tooltip']").tooltip({html:!0})}),$(function(){var t=!1,e=!1,a=new Date;if($("#daily-reports-create").length>0&&""===$("#inputDatetime").val()&&$("#inputDatetime").val(ISODateString(a)).attr("max",ISODateString(a)),$("#daily-reports-create").length>0||$("#daily-reports-edit").length>0){var n=function(t){$(t.target).closest("tr").remove()},o=function(t){$(t.target).closest(".card.work:not(#original-work)").remove()},r=function(t){var e=$(t.target).parents(".card.work").find("table#report-lines tbody tr:last-child").clone();console.log(e),e.find("input").val("").prop("readonly",!1),e.find(':not(td:first-child) input[type="number"]').val(0),e.removeClass("first"),e.find("#removeRow").on("click",function(t){n(t)}),e.find("#info").tooltip({html:!0,title:function(){return $(document).find("#"+this.id+"-tooltip .tooltip").find("#title").html()}}),console.log(e[0]),$(t.target).parents(".card.work").find("table#report-lines tbody").append(e),$('a[href="#"]').click(function(t){t.preventDefault()})},i=function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];if(!(window.verifyingWork&&4===window.verifyingWork.readyState||t))throw new Error($("#errors #waitForWorkCheck").text());var n={plate:$('input[name="plate"]').val(),km_departure:$('input[name="km-departure"]').val(),km_arrival:$('input[name="km-arrival"]').val(),comment:$("textarea").val(),datetime:$("#inputDatetime").val(),team:$("#inputTeam").children("option:selected").val()},o=$("div.card.work input.work-number").map(function(t,e){return e.value}).get();if(new Date(n.datetime)>a)throw new Error($("#errors #invalidDate").text());if($("[data-error=true]").length>0||o.indexOf("0")>-1)throw new Error($("#errors #invalidWorkNumber").text());t&&(n.id=$("#reportId").text());var r=n.km_arrival-n.km_departure,i=0,l={};if($("div.card.work").each(function(t,e){console.log("Work: ",e);var a=$(e).find("input.work-number").val();if(0===a)throw new Error($("#errors #unexpectedError"));l[a]={},$(e).find("tbody tr").each(function(t,n){l[a][t]={},l[a][t].driven_km=$(e).find("input.driven-km").val(),$(n).find("input:not(.work-number), select").each(function(e,n){"driven_km"!==n.name&&("quantity"===n.name?l[a][t][n.name]=parseFloat(parseFloat(n.value).toFixed(2)):l[a][t][n.name]=n.value)})})}),$(document).find(".card.work .card-header input[name=driven_km]").each(function(t,e){i+=parseInt(e.value)}),console.log("Total: ",r),console.log("Inserted: ",i),console.log(Math.abs(i-r)),i-r<0&&0==e)throw e=!0,new Error($("#errors #inferiorKm").text());if(i-r>0)throw new Error($("#errors #superiorKm").text());if(i-r<0&&$("#inputComment").val().length<15)throw new Error($("#errors #inferiorKmWarn").text());n.rows=l,window.createReportRequest||(window.createReportRequest=$.ajax({method:"POST",url:$("#report").attr("action"),data:JSON.stringify(n),contentType:"json",success:function(t){$('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$('#report button[type="submit"]').find(".btn-text").removeClass("d-none"),window.location.replace(t)},error:function(t,e,a){throw $('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$('#report button[type="submit"]').find(".btn-text").removeClass("d-none"),new Error(a.message)},complete:function(){$('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$('button[type="submit"]').find(".btn-text").removeClass("d-none")}}))},l=function(e){t=!1;var a={id:$(e.target).val()};""!==a.id?(window.verifyingWork&&4!==window.verifyingWork.readyState&&window.verifyingWork.abort(),window.verifyingWork=$.ajax({method:"POST",url:"/works/work-exists",data:JSON.stringify(a),contentType:"json",success:function(t){t=JSON.parse(t),console.log("Response: ",t),!1===t.value?($(e.target).parent().popover({html:!0,title:function(){return $(document).find("#"+this.id+" .popover").find("#title").html()},content:function(){return $(document).find("#"+this.id+" .popover").find("#content").html()}}),$(e.target).parent().find(".popover #content").html($("#errors ."+t.reason).html()),$(e.target).addClass("border-danger").addClass("bg-flamingo").attr("data-error",!0).focus(),$(".popover:not(.popover-data)").addClass("popover-danger")):($(e.target).removeClass("border-danger").removeClass("bg-flamingo").removeAttr("data-error"),$(e.target).parent().popover("dispose"))},error:function(t,e,a){}})):($(e.target).removeClass("border-danger").removeClass("bg-flamingo").removeAttr("data-error"),$(e.target).parent().popover("dispose"))};setInterval(function(){var t=0,e=$('input[name="km-arrival"]').val()-$('input[name="km-departure"]').val();$(document).find(".card.work .card-header input[name=driven_km]").each(function(e,a){t+=parseInt(a.value)}),t-e<0?($("#warnings #superiorKmErr").addClass("d-none"),$("#warnings #inferiorKmWarn").removeClass("d-none")):t-e>0?($("#warnings #inferiorKmWarn").addClass("d-none"),$("#warnings #superiorKmErr").removeClass("d-none")):($("#warnings #superiorKmErr").addClass("d-none"),$("#warnings #inferiorKmWarn").addClass("d-none"))},1e3),$("#addRow").on("click",function(t){r(t)}),$("a.remove-work").on("click",function(t){o(t)}),$("a#removeRow").on("click",function(t){n(t)}),$("a.add-work").on("click",function(t){console.log("Target: ",t.target);var e=$(t.target).parents(".card").find(".work").last().clone();e.removeAttr("id");var a=e.find("table#report-lines tbody tr");a.each(function(t,e){if(a.length-(t+1)==0)return!1;$(e).remove()}),e.find("#addRow").on("click",function(t){r(t)}),console.log("Work: ",e),e.find("a.remove-work").on("click",function(t){o(t)});var n=e.find("table#report-lines tbody tr:last-child");n.find("input").val("").prop("readonly",!1),n.find(':not(td:first-child) input[type="number"]').val(0),n.removeClass("first"),n.find("#removeRow").on("click",function(t){removeLine(t)}),n.find("#info").tooltip({html:!0,title:function(){return $(document).find("#"+this.id+"-tooltip .tooltip").find("#title").html()}}),$(e).find("input.work-number").on("keydown keyup",function(t){l(t)}),$(t.target).parents(".card").find(".work").last().after(e),$('a[href="#"]').click(function(t){t.preventDefault()})}),$(".info-tooltip").tooltip({html:!0,title:function(){return $(document).find("#"+this.id+"-tooltip .tooltip").find("#title").html()}}),$("div.card.work input.work-number").on("keyup",function(t){l(t)}),$("#report").on("submit",function(e){if(e.preventDefault(),e.stopPropagation(),$('button[type="submit"]').find("#spinner, #spinner-text").removeClass("d-none"),$('button[type="submit"]').find(".btn-text").addClass("d-none"),t)$('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$('button[type="submit"]').find(".btn-text").removeClass("d-none");else try{$("#daily-reports-edit").length>0?i(!0):i()}catch(t){return $('button[type="submit"]').find("#spinner, #spinner-text").addClass("d-none"),$('button[type="submit"]').find(".btn-text").removeClass("d-none"),void alert(t.message)}}),$(document).on("change keyup",'input[name="km-departure"], input[name="km-arrival"]',function(t){$("#total-km-holder #value").text(parseInt($('input[name="km-arrival"]').val()-$('input[name="km-departure"]').val())),parseInt($('input[name="km-arrival"]').val()-$('input[name="km-departure"]').val())<0?$("#total-km-holder").addClass("text-danger"):$("#total-km-holder").removeClass("text-danger")}),$(document).on("change keyup",'input[name="quantity"]',function(t){console.log("Fired");var e=0;$('input[name="quantity"]').each(function(t,a){e+=parseFloat($(a).val())||0}),e>0&&$("#total-hour-holder").find("#value").text(decimalToTimeValue(parseFloat(e).toFixed(2))),parseInt($('input[name="km-arrival"]').val()-$('input[name="km-departure"]').val())<0?$("#total-km-holder").addClass("text-danger"):$("#total-km-holder").removeClass("text-danger")})}$("#daily-reports-view").length>0&&$("#modalComment").on("show.bs.modal",function(t){var e="";void 0!==$(t.relatedTarget).data("id")&&(e=$(t.relatedTarget).data("id")),$(t.target).find("#content .body").html(""),$(t.target).find("#modal-spinner").removeClass("d-none"),$currAjax=$.ajax({method:"POST",url:"/daily-reports/process-status/get-comment",data:JSON.stringify({id:e}),contentType:"json",success:function(e){e=JSON.parse(e),$(t.target).find("#content .body").html(e.content),$(t.target).find("#modal-spinner").addClass("d-none")},error:function(e,a,n){$(t.target).find("#modal-spinner").addClass("d-none"),alert(n)},complete:function(){$(t.target).find("#modal-spinner").addClass("d-none")}})}),$("#cancel-report").on("click",function(t){t.preventDefault(),confirm($("#prompts .cancel-report").text())&&window.location.replace($(t.target).parent("a#cancel-report").attr("href"))})}),$(function(){$("#report-process-status").length>0&&$("#report-process-status").DataTable({responsive:!0,order:[[3,"desc"]],searching:!1,lengthChange:!1,language:{url:"/config/dataTables/lang/"+window.lang+".json"}}),$("#reports").length>0&&$("#reports").DataTable({responsive:!0,order:[[1,"desc"]],columnDefs:[{targets:"sorting-disabled",orderable:!1}],lengthChange:!0,language:{url:"/config/dataTables/lang/"+window.lang+".json"}})}),$(function(){if($("#calls-pbx-create").length>0&&$("#calls-pbx-create .show-password").on("mousedown mouseup",function(t){"password"===$(t.target).parent("a").siblings("input").attr("type")?$(t.target).parent("a").siblings("input").attr("type","text"):$(t.target).parent("a").siblings("input").attr("type","password")}),$("#calls-dashboard").length>0){var t=$("#monthlyWaitTimeInfo")[0].getContext("2d"),e=$("#monthlyCallNumberInfo")[0].getContext("2d"),a=$("#monthlyLostCallNumberInfo")[0].getContext("2d");queryData={inbound:!1,dates:!1},window.monthlyWaitTimeInfoAjax=$.ajax({method:"POST",url:"/calls/get_monthly_wait_time_info",contentType:"json",data:JSON.stringify(queryData),success:function(e){chartData=JSON.parse(e),console.log(chartData.test_values),window.monthlyWaitTimeInfoChart=new Chart(t,{type:"bar",data:{labels:chartData.labels,datasets:[{label:$("#labels #maxMonthlyWaitTime").text(),data:chartData.max,backgroundColor:"rgba(43, 132, 99, 0.2)",borderColor:"rgba(43, 132, 99, 0.2)",borderWidth:1},{label:$("#labels #averageMonthlyWaitTime").text(),data:chartData.avg,backgroundColor:"rgba(43, 34, 200, 0.2)",borderColor:"rgba(43, 34, 200, 1)",borderWidth:1,type:"line",fill:!1},{label:$("#labels #weightedAverageMonthlyWaitTime").text(),data:chartData.wavg,backgroundColor:"rgba(200, 34, 43, 0.2)",borderColor:"rgba(200, 34, 43, 1)",borderWidth:1,type:"line",fill:!1}]},options:{title:{display:!0,text:$("#titles #minMaxExternalMonthlyWaitTime").text()},scales:{yAxes:[{ticks:{beginAtZero:!0,userCallback:function(t){return decimalSecondsToTimeValue(t)}}}]}}})},error:function(t,e,a){}}),window.monthlyCallNumberAjax=$.ajax({method:"POST",url:"/calls/get_monthly_call_number_info",contentType:"json",data:JSON.stringify(queryData),success:function(t){console.log(t),chartData=JSON.parse(t),console.log(chartData.test_values),window.monthlyCallNumberInfoChart=new Chart(e,{type:"bar",data:{labels:chartData.labels,datasets:[{label:$("#labels #monthlyTotalCalls").text(),data:chartData.total,backgroundColor:"rgba(43, 132, 99, 0.2)",borderColor:"rgba(43, 132, 99, 0.2)",borderWidth:1},{label:$("#labels #monthlyFrontOfficeCalls").text(),data:chartData.frontOffice,backgroundColor:"rgba(43, 34, 200, 0.2)",borderColor:"rgba(43, 34, 200, 1)",borderWidth:1,type:"line",fill:!1},{label:$("#labels #monthlyGenericCalls").text(),data:chartData.generic,backgroundColor:"rgba(200, 34, 43, 0.2)",borderColor:"rgba(200, 34, 43, 1)",borderWidth:1,type:"line",fill:!1},{label:$("#labels #monthlyInternalCalls").text(),data:chartData.internal,backgroundColor:"rgba(200, 34, 140, 0.2)",borderColor:"rgba(200, 34, 140, 1)",borderWidth:1,type:"line",fill:!1}]},options:{title:{display:!0,text:$("#titles #totalCallsByTypeAndMonthExcludeLost").text()},scales:{yAxes:[{ticks:{beginAtZero:!0}}]}}}),window.monthlyLostCallNumberInfoChart=new Chart(a,{type:"bar",data:{labels:chartData.labels,datasets:[{label:$("#labels #monthlyTotalLostCalls").text(),data:chartData.totalLost,backgroundColor:"rgba(43, 132, 99, 0.2)",borderColor:"rgba(43, 132, 99, 0.2)",borderWidth:1},{label:$("#labels #monthlyFrontOfficeLostCalls").text(),data:chartData.frontOfficeLost,backgroundColor:"rgba(43, 34, 200, 0.2)",borderColor:"rgba(43, 34, 200, 1)",borderWidth:1,type:"line",fill:!1},{label:$("#labels #monthlyGenericLostCalls").text(),data:chartData.genericLost,backgroundColor:"rgba(200, 34, 43, 0.2)",borderColor:"rgba(200, 34, 43, 1)",borderWidth:1,type:"line",fill:!1},{label:$("#labels #monthlyInternalLostCalls").text(),data:chartData.internalLost,backgroundColor:"rgba(200, 34, 140, 0.2)",borderColor:"rgba(200, 34, 140, 1)",borderWidth:1,type:"line",fill:!1}]},options:{title:{display:!0,text:$("#titles #totalLostCallsByTypeAndMonth").text()},scales:{yAxes:[{ticks:{beginAtZero:!0}}]}}})},error:function(t,e,a){}}),$("#export a").on("click",function(){$.ajax({method:"GET",url:$(this).attr("href"),contentType:"json",success:function(t,e,a){var n=a.getResponseHeader("content-disposition"),o=/"([^"]*)"/.exec(n),r=null!=o&&o[1]?o[1]:a.getResponseHeader("X-ewater-filename"),i=new Blob([t],{type:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"}),l=document.createElement("a");l.href=window.URL.createObjectURL(i),l.download=r,document.body.appendChild(l),l.click(),document.body.removeChild(l),$("#modalExport").modal("hide")}})})}}),$(function(){$("#datatable-pbx").length>0&&$("#datatable-pbx").DataTable({responsive:!0,order:[[1,"desc"]],columnDefs:[{targets:"actions",orderable:!1}],lengthChange:!0,language:{url:"/config/dataTables/lang/"+window.lang+".json"}}),$("#datatable-calls").length>0&&(window.datatable_calls=$("#datatable-calls").DataTable({responsive:!0,searching:!0,order:[[0,"desc"]],columnDefs:[{targets:"actions",orderable:!1}],lengthChange:!0,language:{paginate:{previous:'<i class="fa fa-angle-left"></i>',next:'<i class="fa fa-angle-right"></i>'},sProcessing:'<div class="loading-cpage"><svg id="load" x="0px" y="0px" viewBox="0 0 150 150"><circle id="loading-inner" cx="75" cy="75" r="60"/></svg></div>',sEmptyTable:"No Records",url:"/config/dataTables/lang/"+window.lang+".json"},autoWidth:!1,processing:!0,serverSide:!0,ajax:"/calls",columns:[{data:"timestart",name:"timestart",searchable:!0},{data:"callfrom",name:"callfrom",searchable:!0},{data:"callto",name:"callto",searchable:!0},{data:"callduration",name:"callduration",searchable:!0},{data:"talkduration",name:"talkduration",searchable:!0},{data:"waitduration",name:"waitduration",searchable:!0},{data:"status",name:"status",searchable:!0},{data:"type",name:"type",searchable:!0}],drawCallback:function(t){this.api().ajax.json()}}))}),$(function(){var t=setInterval(function(){$("#datatable-roles").length>0&&($("#datatable-roles").dataTable({responsive:!0,order:[[1,"asc"]],columnDefs:[{targets:$("#datatable-roles").find("thead tr:first th.actions").index(),orderable:!1}],language:{url:"/config/dataTables/lang/"+window.lang+".json"}}),clearInterval(t))},1e3)});
