$(document).ready(function(){
	//set up
	$('.ava-frame').mouseenter(function(){
		$('.avatar-upload').fadeIn(300);
	});
	$('.ava-frame').mouseleave(function(){
		$('.avatar-upload').fadeOut(300);
	});
	$('.table-avatar').click(function(){
		$('input[name=hideImg]').click();
		$('input[name=hideImg]').attr('id', $(this).attr('id'));
		$(this).attr('data-active-change-image', 'image');
	});
	window.WebSocket = window.WebSocket || MozWebSocket;
	var conn = new WebSocket('ws://127.0.0.1:1337');
	conn.onopen = function () {
	    // connection is opened and ready to use
	    console.log('Connection is created');
	};
	conn.onmessage = function(message) {
		console.log('message ', message);
		try {
			var main = JSON.parse(message.data);
		} catch (e) {
			// console.log('')
			return;
		}
		json = main.data;
		console.log(json);
		if (main.type === "newFoodData") {
			var waitingMess = parseInt($('#countNewFood1').text());
			$('#countNewFood1').text(`${waitingMess + 1}`);
			$('#countNewFood2').text(`${waitingMess + 1} new`);
			if ($('#mCSB_2_container').length > 5) {
				$('#mCSB_2_container')[4].remove();
			}

			$('#mCSB_2_container').prepend(
				$("<a>", {
					class: "list-group-item",
					attr: {
						href: baseUrl + "/details/f_" + json['id'],
					},
				}).append(
					$("<div>", {
						class: "list-group-status status-waiting"
					}),
					$("<img>", {
						class: "pull-left",
						attr: {
							src: baseUrl + "/public/images/" + json['image'],
							alt: json['food']
						}
					}),
					$("<span>", {
						class: "contacts-title"
					}).append(`<b>${json['food']}</b>`),
					$("<p>", {
						class: "description-line-for-new-food"
					}).append(`Has been added by <b>${json['user']}</b> on ${new Date(json['time']).toLocaleDateString("vi-AS", {timeZone: "Asia/Bangkok"})}`),
				),
			);
		}
	}

	$(document).on('change', 'input[name=hideImg]', function(){
		readURL(this);
		var here = $(this);
		var form = new FormData();

		form.append('_token', $('base').attr('token'));
		form.append('id', $(this).attr('id'));
		form.append('image', $(this)[0].files[0]);

		$.ajax({
			url: $('base').attr('href') + '/admin/food/changeAvatar',
			type: 'POST',
			cache: false,
		    contentType: false,
		    processData: false,
			data: form,
			success: function(res){
				$('td[id='+$(here).attr('id')+']').addClass('success-border');
				$(here).val('');
				setTimeout(function(){
					$('td[id='+$(here).attr('id')+']').removeClass('success-border');
				},3000);
			}
		});
		
	});

	$(document).on('change', '.edit-able', function(){
		let here = $(this);
		$.ajax({
			url: $('base').attr('href') + '/admin/food/editname',
			type: 'POST',
			data:{
				_token: $('base').attr('token'),
				name: $(this).val(),
				id: $(this).attr('id')
			},
			success: function(res){
				if(res)
				{
					$(here).parent().addClass('success-border');
					setTimeout(function(){
						$(here).parent().removeClass('success-border');
					},3000);
					// $(here).attr('id',rs['id']);
				}
			}
		});
	});

	$(document).on('change', '.foodtype-edit', function(){
		let here = $(this);
		var options = $(this).find('option:selected');
		var type = [];
		if(options.length > 0)
		{
			options.map(function(i,k){
				type.push($(k).val());
			});
		}

		$.ajax({
			url: $('base').attr('href') + '/admin/food/edittype',
			type: 'POST',
			data: {
				_token: $('base').attr('token'),
				id: $(this).attr('id'),
				types: type,
			},
			success: function(res){
				if(res)
				{
					$(here).parent().addClass('success-border');
					setTimeout(function(){
						$(here).parent().removeClass('success-border');
					},3000);
				}
				
			}
		});
	});

	$(document).on('click', '.status-cube', function(){

		var here = $(this);
		var parent = $(this).parent().parent().parent();
		var oldButton = $(this).parent().parent().parent().find('button');
		var span = `
			<p>
				<span id="${ $(oldButton).attr('btn-status-id') }" data-food-id="${ $(oldButton).attr('id') }" data-effect="${ $(oldButton).attr('btn-effect') }" class="label label-${ $(oldButton).attr('btn-effect') } label-font-9 status-cube">${ $(oldButton).text() }</span>
			</p>
		`;
		$(parent).find('button').attr('btn-status-id', $(this).attr('id'));
		$(parent).find('button').attr('btn-effect', $(this).attr('data-effect'));
		$(parent).find('button').text($(this).text());
		$(parent).find('button').attr('class', 'btn btn-sm btn-'+$(this).attr('data-effect'));
		$(this).remove();
		$(parent).find('div').prepend(span);

		$.ajax({
			url: $('base').attr('href') + '/admin/food/changeStatus',
			type: 'POST',
			data: {
				// _token: $('base').
				foodId: $(this).attr('data-food-id'),
				statusId: $(this).attr('id')
			},
			success: function(res){
				if(res)
				{
					$(parent).addClass('success-border');
					setTimeout(function(){
						$(parent).removeClass('success-border');
					},3000);
				}
			}
		});
	});

	$(document).on('click', 'button[name=btnSort]', function(){
		var arrType = [];
		var arrRating = [];
		var arrStatus = [];
		var linkType = '';
		var linkRate = '';
		var linkStatus = '';
		var sortAsType = $('.sortAsType:checked');		
		var sortAsRating = $('.sortAsRating:checked');		
		var sortAsStatus = $('.sortAsStatus:checked');
		if(sortAsType.length > 0)
		{
			for(let i = 0; i < sortAsType.length; i++)
			{
				linkType += $(sortAsType[i]).val();
				if(i < sortAsType.length - 1)
				{
					linkType += '_';
				}
			}
		}
		if(sortAsRating.length > 0)
		{
			for(let i = 0; i < sortAsRating.length; i++)
			{
				linkRate += $(sortAsRating[i]).val();
				if(i < sortAsRating.length - 1)
				{
					linkRate += '_';
				}
			}
		}
		if(sortAsStatus.length > 0)
		{
			for(let i = 0; i < sortAsStatus.length; i++)
			{
				linkStatus += $(sortAsStatus[i]).val();
				if(i < sortAsStatus.length - 1)
				{
					linkStatus += '_';
				}
			}
		}
		var url = $('base').attr('href') + '/admin/food/sort/'+ (linkType || 0) + '/' + (linkRate || 0) + '/' + (linkStatus || 0);
		window.location.href = url;
	});
	$(document).on('click', '.change-adr-avatar', function(){
		$(this).parent().find('input').click();
	});

	$(document).on('click', '.optionSaveAddress', function(){
		var here = $(this);
		var form = new FormData();

		var currentParent = $(this).parent().parent();
		form.append('id', $(this).val());
		form.append('address', $(currentParent).find('input[name=address]').val());
		form.append('price', $(currentParent).find('input[name=price]').val());
		form.append('avatar', $(currentParent).find('input[name=adrAvatar]')[0].files[0]);

		$.ajax({
			url: $('base').attr('href') + '/admin/food/editAddress',
			type: 'POST',
			cache: false,
		    contentType: false,
		    processData: false,
			data: form,
			success: function(res){
				if(res)
				{
					$(here).parent().parent().addClass('success-border');
					setTimeout(function(){
						$(here).parent().parent().removeClass('success-border');
					},3000);
				}
			}
		});
	});
	$(document).on('click', '.food-info', function(){
		$('.modal-body').html('');
		var url = baseUrl + '/admin/food/getAddress';
		var data = {
			food_id: $(this).val()
		};
		$.post(url, data, function(res){
			var rs = JSON.parse(res);
				rs = rs[0]['addresses'];
			var	food_id = rs[0]['id'];
			var html = '';
			if(rs)
			{
				for(let i = 0; i < rs.length; i++) 
				{
					html += `
						<div class="row">
							<div class="col-lg-4">
			                    <img src="${baseUrl}/public/images/${rs[i]['avatar']}" style="cursor:pointer" class="img-thumbnail avatar-img change-adr-avatar">
			                    <input type="file" name="adrAvatar" onchange="readURL2(this)" class="hide adrAvatar">
			                </div>
			                <div class="col-lg-8">
			                    <label>Address & Price:</label>
			                    <input type="text" name="address" class="form-control add-address" placeholder="Address" value="${rs[i]['address']}">
			                    <div class="row pt-1 mb-3">
				                    <div class="col-md-4 p-0">
				                        <input class="form-control" name="price" type="text" placeholder="Price" value="${rs[i]['price']}">
				                    </div>
				                    <div class="col-md-3">
				                        <select name="currency" class="form-control col-md-3">
				                            <option value="VND">VND</option>
				                            <option value="USD">USD</option>
				                        </select>
				                    </div>
			                    </div>
			                </div>
			                <div class="col-md-8">
				                <button class="btn btn-success optionSaveAddress" value="${rs[i]['id']}">Save</button>
				                <a class="btn btn-danger optionDelAddress" value="${rs[i]['id']}" href="${baseUrl}/admin/food/delItem/${food_id}/${rs[i]['id']}">Delete</a>
			                </div>
			            </div>
						<hr>
					`;
				}
			}
			else{
				html += '';
			}
			$('.modal-body').html(html);
		});
	});

	$(document).on('click', '.nutrifood-delete-line', function(){
		$(this).parent().parent().parent().parent().remove();
		var caloModel = $(this).find('option:selected').attr('data-nutri');
		var gram = $(this).parent().parent().find('.content-gram').val();
		caculateCalorie(caloModel, gram ,$(this));
	});

	$(document).on('click', '.edit-calorie', function(){
		$('button[name=food_id]').val($(this).attr('data-food'));
		var url = baseUrl + '/admin/food/getFoodNutrition/' + $(this).attr('data-food');
		$.getJSON(url, function(res){
			$('#addLineArea').html('');
			if(res)
			{
				var rs = res[0]['nutritions'];
				var tt_cal = 0;
				for(let i = 0; i < rs.length; i++)
				{
					var listCore = $('#coreListNutrition');
					var html = $(listCore)[0].innerHTML;
					$('#addLineArea').append(html);
					var newListNutri = $('.toSetSelect2');
					var contentGram = $('.content-gram');
					$(newListNutri[i]).find('option[value='+rs[i]['id']+']').attr('selected', '');
					$(contentGram[i]).val(rs[i]['pivot']['volume']);
					var calHtml = `
						<div class="col-md-12 caculate-info-calorie" data-caculated-calorie="${rs[i]['pivot']['calorie']}">
				            <h3 class="text-success">= ${rs[i]['pivot']['calorie']} Calo</h3>
				            <input type="number" class="hide hd_toCaculateCalo" name="hd_toCaculateCalo[]" value="${rs[i]['pivot']['calorie']}">
				        </div>
					`;
					$(contentGram[i]).parent().parent().find('.caculate-calo-area').append(calHtml);
					tt_cal += rs[i]['pivot']['calorie'];
				}
				var tt_html = `<p>
                        <b>Total:</b>
                        <b class="text-info ml-2">${tt_cal} Calo</b>
                    </p>`;
					$('.total-calo-when-caculated').html(tt_html);
			}
		});
	});

	$(document).on('change', '.toSetSelect2', function(){
		var caloModel = $(this).find('option:selected').attr('data-nutri');
		var gram = $(this).parent().parent().find('.content-gram').val();
		caculateCalorie(caloModel, gram ,$(this));
	});
	$(document).on('change', '.content-gram', function(){
		var caloModel = $(this).parent().parent().find('.toSetSelect2').find('option:selected').attr('data-nutri');
		var gram = $(this).val();
		caculateCalorie(caloModel, gram ,$(this));
			
	});
	$(document).on('click', '#addLineNutrition', function(){
		var html = $('#coreListNutrition')[0].innerHTML;
		$(this).parent().find('#addLineArea').append(html);
	});
});

function caculateCalorie(caloModel, gram, here)
{
	caloModel = parseInt(caloModel);
	gram = parseInt(gram);
	if(caloModel && gram){
		var calo = (caloModel/100) * gram;
			calo = Math.round(calo * 100) / 100;
		var calHtml = 
			`
				<div class="col-md-12 caculate-info-calorie" data-caculated-calorie="${calo}">
		            <h3 class="text-success">= ${calo} Calo</h3>
		            <input type="number" class="hide hd_toCaculateCalo" name="hd_toCaculateCalo[]" value="${calo}">
		        </div>
			`;
			$('button[name=submitChangeNutrition]').fadeIn(0);
	}
	else{
		var calHtml = 
			`
				<div class="col-md-3">
		            <h4 class="text-danger">Gram must be higher than 0</h4>
		        </div>
			`;
			$('button[name=submitChangeNutrition]').fadeOut(0);
	}
	$(here).parent().parent().find('.caculate-calo-area').html(calHtml);
	var hd_cal = $('.hd_toCaculateCalo');
	var tt_cal = 0;
	for(let i = 0; i < hd_cal.length; i++)
	{
		tt_cal += parseInt($(hd_cal[i]).val());
	}
	var tt_html = 
		`<p>
	        <b>Total:</b>
	        <b class="text-info ml-2">${tt_cal} Calo</b>
	        <input type="number" class="hide" name="hd_totalCaloToSave" value="${tt_cal}" />
	    </p>`;

	$('.total-calo-when-caculated').html(tt_html);
	
}
function readURL(input)
{
	if(input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function(e){
			$('td[data-active-change-image="image"]').html(`<img class="img-thumbnail" src="${e.target.result}" alt="" >`);
			$('td').removeAttr('data-active-change-image');
		};
		reader.readAsDataURL(input.files[0]);
	}
}
function readURL2(input)
{
	if(input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function(e){
			$(input).parent().find('img').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}
