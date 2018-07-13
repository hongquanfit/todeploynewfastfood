$(document).ready(function(){
	$(document).on('change','.food-type', function(){
		// console.log($(this).val());
		// console.log(a);
		let here = $(this);
		let stt = $('.dd-item').length;

		$.ajax({
			url: $('base').attr('href') +'/admin/type/editType',
			type: 'POST',
			data: {
				_token: $('base').attr('token'),
				id: $(this).attr('id'),
				types: $(this).val(),
				orders: stt
			},
			success: function(response)
			{
				var rs = JSON.parse(response);
				if(response)
				{
					$(here).parent().addClass('success-border');
					setTimeout(function(){
						$(here).parent().removeClass('success-border');
					},3000);
					$(here).attr('id',rs['id']);
				}
			}
		})
	});

	$(document).on('click','button[name=addLine]', function(){
		var countLine = $('.dd-item').length;
		var html = `
			<tr class="dd-item" data-id="${countLine+1}">
				<td class="dd-handle"><i class="fa fa-minus"></i></td>
				<td class="text-center">${countLine+1}</td>
				<td><input type="text" name="type_name[]" id="" value="" class="edit-able food-type"></td>
				<td class="text-center">0</td>
				<td class="text-center"><button type="button" value="" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-remove"></span></button></td>
			</tr>
		`;
		$('.dd-list').append(html);
	});

    $('#nestable').nestable().on('change', function(){
    	console.log();
    	var listFoodType = $(this).find('.food-type');
    	var arr = [];
    	$(listFoodType).map((k,i)=>{
    		if($(i).attr('id'))
    			arr.push({id: $(i).attr('id'),orders:k+1});
    	});
    	$.ajax({
    		url: $('base').attr('href') + '/admin/type/sort',
    		type: 'POST',
    		data:{
    			_token: $('base').attr('token'),
    			arr: JSON.stringify(arr),
    		},
    		success: function(response)
    		{

    		}
    	});
    });

    $(document).on('click','.confirm-delete', function(){
    	$('#mb-confirm-delete').addClass('open');
        $('button[name=typeId]').val($(this).val());
    	$.ajax({
    		url: $('base').attr('href') + '/admin/type/detectID',
    		type: 'POST',
    		data: {
    			_token: $('base').attr('token'),
    			id: $(this).val(),
    		},
    		success: function(response)
    		{
    			$('#warn').text(response);
    		}
    	});
    });
});