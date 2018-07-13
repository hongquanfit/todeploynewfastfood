$(document).ready(function() {
    var worker = null;
    var loaded = 0;
    var listStarToVote;
    caculateBMI([]);
    
    //setup library
    if ($('#avatarImage').length > 0) {
        $('.avatar-upload').height('100%');
        $('.avatar-upload').width($('#avatarImage')[0].width * 0.63 || 0);
    }

    $('[data-toggle-tooltip="tooltip"]').tooltip();

    $('.select2').select2({
        tags: true,
        placeholder: 'Choose some types for the food above',
    });
    $('.icheckbox').iCheck({
        checkboxClass: 'icheckbox_flat-red',
        radioClass: 'iradio_flat-red'
    });
    //event
    $('.ava-frame').mouseenter(function() {
        $('.avatar-upload').fadeIn(300);
    });
    $('.ava-frame').mouseleave(function() {
        $('.avatar-upload').fadeOut(300);
    });
    $('.avatar-upload').click(function() {
        $('input[name=hideImg]').click();
    });
    $('.vote-frame').mouseenter(function() {
        $(this).find('.vote-frame-main').fadeOut(0);
        $(this).find('.vote-frame-o-star').fadeIn(0);
    });
    $('.vote-frame-o-star i').mouseover(function() {
        var level = $(this).attr('level');
        var papa = $(this).parent().find('i');
        for (let i = 0; i < level; i++) {
            $(papa[i]).attr('class', 'fa fa-star mr-1 vote-star');
        }
    });
    $('.vote-frame-o-star i').mouseleave(function() {
        $(this).attr('class', 'fa fa-star-o mr-1 vote-star');
    });

    $('.vote-frame').mouseleave(function() {
        $(this).find('.vote-frame-main').fadeIn(0);
        $(this).find('.vote-frame-o-star').fadeOut(0);
    });
    //ajax
    $(document).on('change', 'input[name=heightBMI]', function() {
        caculateBMI($(this));
    });
    $(document).on('change', 'input[name=weightBMI]', function() {
        caculateBMI($(this));
    });
    $('input[name=sex]').on('ifChecked', function(event) {
        caculateBMI($(this));
    });
    $(document).on('change', 'input[name=age]', function() {
        caculateBMI($(this));
    });
    $(document).on('click', '.favorite-icon', function() {

        var here = $(this);
        var url = baseUrl + '/user/addFavorite';
        var data = {
            id: $(this).attr('id'),
            type: $(this).attr('data-like')
        }
        $.post(url, data, function(res) {
            if (res === 'ajax') {
                window.location.href = baseUrl + '/login';
            }
            var html = '';
            if (data.type == 'like') {
                html = `
					<span class="favorite-icon" id="${data.id}" data-like="unlike">
						<i class="fa fa-heart-o"></i>
						</span> Add to favorite
				`;
            } else {
                html = `
					<span class="favorite-icon" id="${data.id}" data-like="like">
						<i class="fa fa-heart text-danger"></i>
						</span> You liked this
				`;
            }
            $(here).parent().html(html);
        });
    });
    $(document).on('click', 'button[name=btnSort]', function() {
        var arrType = [];
        var arrRating = [];
        var arrStatus = [];
        var linkType = '';
        var linkRate = '';
        var linkStatus = '';
        var sortAsType = $('.sortAsType:checked');
        var sortAsRating = $('.sortAsRating:checked');
        var sortAsStatus = $('.sortAsStatus:checked');
        if (sortAsType.length > 0) {
            for (let i = 0; i < sortAsType.length; i++) {
                linkType += $(sortAsType[i]).val();
                if (i < sortAsType.length - 1) {
                    linkType += '_';
                }
            }
        }
        if (sortAsRating.length > 0) {
            for (let i = 0; i < sortAsRating.length; i++) {
                linkRate += $(sortAsRating[i]).val();
                if (i < sortAsRating.length - 1) {
                    linkRate += '_';
                }
            }
        }
        if (sortAsStatus.length > 0) {
            for (let i = 0; i < sortAsStatus.length; i++) {
                linkStatus += $(sortAsStatus[i]).val();
                if (i < sortAsStatus.length - 1) {
                    linkStatus += '_';
                }
            }
        }
        console.log('sortAsType => ', linkType || 'zero');
        console.log('sortAsRating => ', linkRate || 'zero');
        console.log('sortAsStatus => ', linkStatus || 'zero');
        var url = $('base').attr('href') + '/show/' + (linkType || 0) + '/' + (linkRate || 0) + '/' + (linkStatus || 0);
        window.location.href = url;
        // console.log(url);
    });

    $(document).on('keyup', 'input[name=searchBox]', function(e) {
        if (e.keyCode === 13) {
            url = baseUrl + '/find/' + $(this).val();
            window.location.href = url;
        }
    });
    $(document).on('click', '.vote-star', function() {
        var here = $(this);
        var id = $(this).parent().parent().attr('data-vote-item');
        var level = $(this).attr('level');
        var data = {
            _token: $('base').attr('token'),
            toRateItem: id,
            rate: level,
            voteType: $(this).attr('data-vote-type'),
        };
        var url = baseUrl + '/user/rating';
        $.post(url, data, function(response, status, jqXHR) {
            if (response === 'ajax') {
                window.location.href = baseUrl + '/login';
            } else {
                if (response) {
                    if (data['voteType'] == 'food') {
                        var html = `
							<div class="card-comment card-parent">
		                        <p class="text-right mb-0"><span class="btnCloseWindow">&times;</span></p>
		                        <div class="form-group mb-0 comment-area">
		                            <label>You have rated this with <b class="text-warning">${level}</b> <i class="fa fa-star"></i>. You may want to add some comment for this item:</label>
		                            <textarea name="comment" id="commentCard" class="form-control"></textarea>
		                            <button name="addComment" value="${id}" class="btn btn-sm btn-outline-success mt-2"><i class="fa fa-check"></i></button>
		                        </div>
		                    </div>
						`;
                        $(here).parents('.list-food').find('.card-parent').remove();
                        $(here).parents('.list-food').append(html);
                    }


                    var rs = JSON.parse(response);
                    console.log(rs);
                    var mainStar = $(here).parents('.vote-frame').find('.vote-frame-main i');
                    var rateLine = $(here).parents('.star-line').find('.rating-line');
                    let avrScore = rs['total_score'] / rs['rate_times'];
                    avrScore = Math.round(avrScore * 100) / 100;
                    $(rateLine).html(avrScore + ' / ' + rs['rate_times'] + ' Rate');

                    for (let i = 0; i < rs['star'].length; i++) {
                        $(mainStar[i]).attr('class', 'fa fa-' + rs['star'][i] + ' mr-1');
                    }
                }
            }
        });
    });
    $(document).on('click', '.btnCloseWindow', function() {
        $(this).parents('.card-parent').remove();
    });
    $(document).on('click', '.btnHideWindow', function() {
        $(this).parents('.card-parent').fadeOut(0);
    });
    $(document).on('click', '#openAdrComtBox', function() {
        $('.display-comment').fadeOut(0);
        $(this).parents('.address-list').parent().find('.display-comment').fadeIn(0);
    });
    $(document).on('click', 'button[name=addComment]', function() {
        var here = $(this);
        var data = {
            comment: $(this).parent().find('textarea').val(),
            foodId: $(this).val()
        };
        var url = baseUrl + '/user/comment';
        $.post(url, data, function(response) {
            if (response > 0) {
                console.log(data.foodId);
                console.log($('infoSaveComment_' + data.foodId));

                $(here).parents('.card-comment').remove();
                $('#infoSaveComment_' + data.foodId).html('<i class="fa fa-check"></i> Your comment has been saved!');

                setTimeout(function() {
                    $('#infoSaveComment_' + data.foodId).html('');
                }, 2500);
            }
        });
    });
    $(document).on('click', 'button[name=anotherOption]', function() {
        $('#resultReturned').html('');
        $('#groupButtonChooseFood').fadeIn(500);
    });
    $(document).on('click', '.allRandom', function() {
        $('#groupButtonChooseFood').fadeOut(0);
        $('#resultReturned').html('');
        var url = baseUrl + '/search/' + $(this).val();
        $.getJSON(url, function(result) {
            createFoodWhenFound(result, url);
        }); //end get json
    });
    $(document).on('click', '.btn-findFoodRandom', function() {

        var listType = $(this).parent().parent().find('.take-this:checked');
        // console.log(listType);
        if (listType.length > 0) {
            $('#groupButtonChooseFood').fadeOut(0);
            $('.errorNotChoose').html('');
            var url = baseUrl + '/search/' + $(this).val() + '/';
            for (let i = 0; i < listType.length; i++) {
                url += $(listType[i]).val();
                if (i < listType.length - 1) {
                    url += '_';
                }
            }
            console.log(url);
            $.getJSON(url, function(result) {

                createFoodWhenFound(result, url);
            });
        } else {
            // console.log($(this).parent().find('.errorNotChoose'));
            $(this).parent().find('.errorNotChoose').html(`<p class="text-center mb-2"><small class="text-danger">You haven't choose any options yet!</small></p>`);
            return false;
        }
    });
    $(document).on('click', '.btn-findFood', function() {

        var listOptions = $(this).parent().parent().find('.take-this:checked');
        if (listOptions.length > 0) {
            var url = baseUrl + '/search/' + $(this).val() + '/';

            for (let i = 0; i < listOptions.length; i++) {
                url += $(listOptions[i]).val();
                if (i < listOptions.length - 1) {
                    url += '_';
                }
            }

            window.location.href = url;
        } else {
            $('#errorNotChoose').html(`<p class="text-center mb-2"><small class="text-danger">You haven't choose any option yet!</small></p>`);
            return false;
        }
    });
    $(document).on('click', '.btnRandomOneMoreTime', function() {
        var url = $(this).attr('data-url');

        $.getJSON(url, function(result) {
            createFoodWhenFound(result, url);
        });
    })
    $(document).on('click', '.openGroupChooseOptions', function() {
        $('#chooseGroup').fadeOut(0);
        $($(this).attr('data-open-target')).fadeIn(500);
        $($(this).attr('data-open-target')).attr('data-group-option-box-open', 'open');
        $($(this).attr('data-open-target')).append('<a href="javascript:void(0)" id="goBack"><i class="fa fa-arrow-left"></i> Go back</a>');
    });
    $(document).on('click', '#goBack', function() {
        $('[data-group-option-box-open=open]').fadeOut(0);
        $('[data-group-option-box-open=open]').attr('data-group-option-box-open', 'close');
        $('#chooseGroup').fadeIn(500);
        $('#errorNotChoose').html('');
        $(this).remove();
    });

    $(document).on('change', 'input[name=hideImg]', function() {
        readURL(this);
    });
    $(document).on('click', 'button[name=addLineAddress]', function() {
        var html = `
			<hr>
			<div class="row">
                <div class="col-lg-8">
                    <label>Address & Price:</label>
                    <input type="text" name="address[]" class="form-control add-address" placeholder="Address">
                    <div class="row pt-1 pl-3 pl-3 mb-3">
                        <input class="col-sm-4 form-control" name="price[]" type="text" placeholder="Price">
                        <select name="currency[]" class="form-control col-sm-3">
                            <option value="VND">VND</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="${baseUrl}/public/images/store.jpg" class="img-thumbnail avatar-img">
                    <input type="file" name="adrAvatar[]" class="hide adrAvatar">
                </div>
            </div>
		`;
        $(this).parent().find('.group-address').append(html);
    });
    $(document).on('keyup', '.add-address', function() {
        var url = baseUrl + '/api/findAddress/' + $(this).val();
        if ($(this).val()) {
            $.get(url, function(res) {
                // console.log(res);
                if (res.length > 0) {
                    var html = `
						<p class="p-0"><i class="fa fa-map-marker"></i> 434 Tran Khat Chan, toa nha HTP</p>
                        <p class="p-0"><i class="fa fa-map-marker"></i> 434 Tran Khat Chan, toa nha HTP</p>
					`;
                }
            });
        }
    })
    $(document).on('click', '.avatar-img', function() {
        $(this).parent().find('.adrAvatar').click();
    });
    $(document).on('change', '.adrAvatar', function() {
        readURL2(this)
    });
    $(document).on('keyup', 'input[name=food]', function() {
        $('#foodFound').remove();
        $('.find-back-food').remove();
        if ($(this).val()) {
            $.ajax({
                type: 'GET',
                url: baseUrl + '/api/findSomeFood/' + $(this).val(),
                dataType: 'JSON',
                success: function(response) {
                    if (response['listFood'].length > 0) {
                        $('button[name=submit]').remove();
                        var html = '<p id="foodFound" class="mt-1 mb-1 text-info">The food you have entered may be in one of the food on the list below. You are not allow editing this food but you may want to add another address and price for it.</p>';
                        html += `<div class="find-back-food"></div>`;
                        $('#foodName').append(html);
                        html = '';
                        response['listFood'].map(function(i, k) {
                            html += `
								<div class="row food-suggest-hover" data-foodId="${i['id']}">
	                                <div class="col-md-4">`;
                            if (i['images'].length > 0) {
                                html += `<img class="img-thumbnail mx-auto d-block fixed-size-head img-fluid" src=" ${baseUrl}/public/images/${i['images'][0]['url']}" alt="${i['food']}">`;
                            } else {
                                html += `<img class="img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="${baseUrl}/public/images/1462234361-thit-cho-la-mo_TQEL.jpg">`;
                            }
                            html += `
                                	</div>
	                                <div class="col-md-8">
	                                    <p class="mb-2 food-title">${i['food']}</p>`;
                            if (i['addresses'].length > 0) {
                                let address = i['addresses'];
                                for (let i = 0; i < address.length; i++) {
                                    html += `<blockquote class="blockquote little-quote">
			                                            <p class="mb-0 text-info"><small>${address[i]['address']}</small></p>
			                                            <footer class="blockquote-footer price-line"><small><i class="fa fa-money"></i> ${address[i]['price']} VND</small></footer>
			                                        </blockquote>`;
                                }
                            } else {
                                html += '<p class="mb-2"><small>This food has no address yet</small></p>';
                            }
                            html += `</div>
	                            </div>
	                            <hr>
			    			`;
                        });
                        $('.find-back-food').append(html);
                    } else {
                        if ($('button[name=submit]').length === 0) {
                            $('#foodForm').append('<button type="submit" class="btn btn-info" name="submit" value="insert">Suggest</button>');
                        } else {
                            $('button[name=submit]').val('insert');
                        }

                        $('#description').fadeIn(0);
                        $('#foodType').fadeIn(0);
                        $('#Ingredient').fadeIn(0);
                        $('#recommentAutoCaculate').fadeIn(0);
                    }

                    if (response['nutri'].length > 0) {
                        var rs = JSON.parse(response['nutri']);
                        var calo = 0;
                        for (let i = 0; i < rs['nutri'].length; i++) {
                            calo += parseInt($('#select2Nutri').find(`option[value=${rs['nutri'][i]}]`).attr('data-volume'));
                            $('#select2Nutri').find(`option[value=${rs['nutri'][i]}]`).attr('selected', '');
                        }
                        $('#caculateCalorie').val(calo);
                        $('#select2Nutri').select2();
                    }
                }
            }); //end ajax
        } //end if
    });
    $(document).on('change', '#select2Nutri', function() {
        var listNutri = $('#select2Nutri option:selected');
        var totalCalo = 0;
        // console.log(listNutri);
        for (let i = 0; i < listNutri.length; i++) {
            totalCalo += parseInt($(listNutri[i]).attr('data-volume'));
        }
        $('#caculateCalorie').val(totalCalo);
    });
    $(document).on('click', '.food-suggest-hover', function() {
        $('input[name=food]').val($(this).find('.food-title').text());
        $('#description').fadeOut(0);
        $('#foodType').fadeOut(0);
        $('#Ingredient').fadeOut(0);
        $('#recommentAutoCaculate').fadeOut(0);
        if ($('button[name=submit]').length === 0) {
            $('#foodForm').append('<button type="submit" class="btn btn-info" name="submit" value="update_' + $(this).attr('data-foodid') + '">Suggest</button>');
        } else {
            $('button[name=submit]').val('update_' + $(this).attr('data-foodid'));
        }
        var imgSrc = $(this).find('img').attr('src');
        $('#avatarImage').attr('src', imgSrc);
        $('#foodFound').remove();
        $('.find-back-food').remove();
    });
    $(document).on('keyup', 'input[name=editCommentAddressArea]', function(e) {
        if (e.keyCode === 13) {

            var here = $(this);
            var cmt = $(this).val();
            var url = baseUrl + '/user/addComment';
            var data = {
                address_id: $(this).attr('data-address-id'),
                comment: cmt,
                cmtType: 'address',
            };
            $.post(url, data, function(res) {
                $(here).val('');
                addCommentLine(res, here);
            });
        }
    });
    $(document).on('click', '#btnAddComt', function() {
        // $(this).html(`<div class="loader"></div>`)
        var here = $(this);
        var cmt = $('textarea[name=editCommentArea]').val();
        var url = baseUrl + '/user/addComment';
        var data = {
            food_id: $(this).val(),
            comment: cmt,
            cmtType: 'food',
        }
        if (cmt) {
            $.post(url, data, function(res) {
                $('textarea[name=editCommentArea]').val('');
                addCommentLine(res, here);
            });
        }
    });
    $('.moreOptionWith').on('ifChecked', function() {
        $(this).parents('.outside-parent').find('.allowMoreOptionArea').append(getOptionsWith($(this).val()));
    });

    //loader
    function increment() {
        // $('#counter').html(loaded+'%');
        $('#counter').html();
        $('#drink').css('top', (100 - loaded * .9) + '%');
        if (loaded == 25) $('#cubes div:nth-child(1)').fadeIn(100);
        if (loaded == 50) $('#cubes div:nth-child(2)').fadeIn(100);
        if (loaded == 75) $('#cubes div:nth-child(3)').fadeIn(100);
        if (loaded == 100) {
            $('#lemon').fadeIn(100);
            $('#straw').fadeIn(300);
            loaded = 0;
            stopLoading();
            setTimeout(startLoading, 1000);
        } else loaded++;
    }

    function startLoading() {
        $('#lemon').hide();
        $('#straw').hide();
        $('#cubes div').hide();
        worker = setInterval(increment, 30);
    }

    function stopLoading() {
        clearInterval(worker);
    }

    function createFoodWhenFound(result, url) {
        $('#resultReturned').html('abcd');
        var html = `
			<div class="col-md-12 text-center">
	            <p>
	                <h4 class="text-info pl-5 pr-5">Wait for a second.</h4>
	            </p>
	            <p>
	                <h4 class="text-info pl-5 pr-5">It's hard to make a choice, so many good things around!</h4>
	            </p>
	        </div>
			<div id="loader">
			    <div id="glass">
			        <div id="cubes">
			            <div></div>
			            <div></div>
			            <div></div>
			        </div>
			        <div id="drink"></div>
			        <span id="counter"></span>
			    </div>
			    <div id="coaster"></div>
			</div>
		`;
        $('#resultReturned').html(html);
        startLoading();
        setTimeout(function() {
            stopLoading();
            if (result) {
                var html = `
				<div class="col-md-12 mb-3">
					<p><h4 class="text-info">Well I have found something for you. Take a look!</h4></p>
				    <div class="row m-0">
				        <div class="col-lg-5 p-0">`;
                if (result['images'].length > 0) {
                    html += `<img class="img-thumbnail mx-auto d-block fixed-size-head img-fluid" src=" ${baseUrl}/public/images/${result['images'][0]['url']}" alt="${result['food']}">`;
                } else {
                    html += `<img class="img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="${baseUrl}/public/images/1462234361-thit-cho-la-mo_TQEL.jpg">`;
                }
                html += `
				        </div>
				        <div class="col-lg-7">
				            <p><small class="text-muted">`;
                if (result['types'].length > 0) {
                    html += `<small class="text-muted">`;
                    for (let i = 0; i < result['types'].length; i++) {
                        html += result['types'][i]['types'];
                        if (i < result['types'].length - 1) {
                            html += ' / ';
                        }
                    }
                    html += `</small>`;
                } else {
                    html += `<small>&nbsp;</small>`;
                }
                html += `
				            </small></p>
				            <p><h3 class="food-title">${result['food']}</h3></p>
				            <p>${result['description'] ? result['description'] : '&nbsp;'}</p>
				            <p class="calorie-line text-danger">
		                        <i class="fa fa-sun-o"></i> ${ result['total_calorie'] } Kcal
		                    </p>
				            <p>`;
                for (let i = 0; i < result['rateStar'].length; i++) {
                    html += `<i class="fa fa-${result['rateStar'][i]}  mr-1"></i>`;
                }
                html += `<i class="ml-3 rating-line">${(result['rate_times'] == 0) ? '0' : result['total_score'] / result['rate_times']}  /  ${result['rate_times']} Rate</i></p>`;
                html += `<p class="favorite-line">
				            			<span class="favorite-icon" id="${ result['id'] }" data-like="${ result['favorites'] ? 'like' : 'unlike' }">
				            				<i class="fa fa-heart${ (result['favorites'].length > 0) ? ' text-danger' : '-o' }"></i>
				            			</span> ${ (result['favorites'].length > 0) ? 'You liked this' : 'Add to favorite' }
				            		</p>`;

                if (result['addresses'].length > 0) {
                    let address = result['addresses'];
                    for (let i = 0; i < address.length; i++) {
                        html += `<blockquote class="blockquote little-quote">
				                                <p class="mb-0 text-info"><small>${address[i]['address']}</small></p>
				                                <footer class="blockquote-footer price-line"><small><i class="fa fa-money"></i> ${address[i]['price']} VND</small></footer>
				                            </blockquote>`;
                    }
                } else {
                    html += '<p class="mb-2"><small>This food has no address yet</small></p>';
                }
                html += `
				            </a>
				        </div>
				    </div>
				</div>
				<div class="col-lg-3">
				</div>
				<div class="col-lg-6">
					<a class="btn btn-outline-info btn-max mt-2" target="_blank()" href="${baseUrl}/details/item_${result['id']}"><i class="fa fa-map-marker"></i> Details</a>
					<button class="btn btn-outline-danger btn-max mt-2 btnRandomOneMoreTime" data-url="${url}">Random again</button>
					<button class="btn btn-outline-primary btn-max mt-2" name="anotherOption">Another option</button>
				</div>
				`;
            } else {
                var html = `<div class="col-lg-12"><p class="text-center text-danger">We couldn't find anything with these option.</p>`;
                html += '<p class="text-center text-danger">You might want to try again.<p>';
                html += `</div>
						<div class="col-lg-3">
						</div>
						<div class="col-lg-6">
							<button class="btn btn-outline-danger btn-max mt-3 allRandom">Random again</button>
							<button class="btn btn-outline-primary btn-max mt-2" name="anotherOption">Another option</button>
						</div>
					`;
            }
            $('#resultReturned').html(html);
        }, 3000);
    }

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#avatarImage').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(input).parent().find('img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addCommentLine(res, here) {
    if (res) {
        var rs = JSON.parse(res);
        var html = `
			<div class="col-lg-12 pt-2 pb-2 border-comment">
                <p class="mb-1"><b class="text-info">${rs['who']}</b> | `;
        if (rs['voted']) {
            html += `<span class="text-warning">${rs['voted']} <i class="fa fa-star"></i></span>`;
        } else {
            html += `<span class="no-vote">Has no vote</span>`;
        }
        html += `
                </p>
                <footer class="blockquote-footer font-comment">${rs['comment']}</footer>
                <p><small class="float-right">${rs['time']}</small></p>
            </div>
		`;

        $(here).parent().parent().find('.commentLine').prepend(html);
        $(here).parent().find('.notiAddComment').html('<span class="font-11 text-success"><i class="fa fa-check"></i> Your comment has been added!</span>');
    } else {
        $(here).parent().find('.notiAddComment').html('<span class="font-11 text-danger"><i class="fa fa-check"></i> You have entered some special charaters! Delete them and try again</span>');
    }
    setTimeout(function() {
        $(here).parent().find('.notiAddComment').html('');
    }, 3000);
}

function caculateBMI(here) {
    var ck = getCookie('userBMI');
    var human = {};
    console.log('ck => ', ck);
    console.log('here => ', here);
    if (ck && !here.length > 0) {
        human = JSON.parse(ck);
        $('input[name=heightBMI]').val(human.h);
        $('input[name=weightBMI]').val(human.w);
        $('input[name=age]').val(human.age);
        var sex = $('input[name=sex]').parent().parent().find('input[value=' + human.sex + ']').iCheck('check');;
        // if($(sex).)
    } else {
        var h = $('input[name=heightBMI]').val() / 100;
        var w = $('input[name=weightBMI]').val();
        var age = $('input[name=age]').val();
        var sex = $('input[name=sex]:checked').val();
        console.log(sex);
        human = {
            sex: sex,
            h: h * 100,
            w: w,
            age: age
        }
        var calo = caculateBMR(human);
        human['calo'] = calo;
    }
    console.log(human);
    if (human.h && human.w && human.age && human.sex) {

        if (human.h <= 0 || human.w <= 0 || human.age <= 0) {
            var html = `
                <span class="text-danger">Height & Weight & Age must be higher than 0!</span>
			`;
        } else {
            setCookie('userBMI', JSON.stringify(human), 365);
            h = human.h / 100;
            var bmi = human.w / (h * h);
            bmi = Math.round(bmi * 100) / 100;
            var bmiStatus = exportBMIinfo(bmi);
            calo = human.calo;
            var html = `
	                <span>Your BMI Score is <span class="text-info">${bmi}</span></span>
	                <br>
	                	<span class="text-${bmiStatus.effect}">${bmiStatus.info}</span>
	                <br>`;
            if (bmi >= 18.5 && bmi <= 24.9) {
                html += `<span>
            				<input type="radio" checked name="BMROption" value="${calo}" class="icheckbox take-this">
		                	You need <span class="text-${bmiStatus.effect}">${calo} calo/day</span> to <span class="text-${bmiStatus.effect}">${bmiStatus.status}</span> your weight
		                </span>`;
            } else {
                html += `<span>
            				<input type="radio" checked name="BMROption" value="${calo}" class="icheckbox take-this">
		                	You need <span class="text-info">${calo} calo/day</span> to <span class="text-info">maintain</span> your weight
		                </span>
		                <br>`;
                html += `
            				<span>
            					<input type="radio" name="BMROption" value="${calo + bmiStatus.need}" class="icheckbox take-this">
            					You need <span class="text-${bmiStatus.effect}">${calo + bmiStatus.need} calo/day</span> to <span class="text-${bmiStatus.effect}">${bmiStatus.status}</span> 0.5kg per week
            				</span>
            				<br>
            				<span>
            					<input type="radio" name="BMROption" value="${calo + (bmiStatus.need * 2)}" class="icheckbox take-this">
            					You need <span class="text-${bmiStatus.effect}">${calo + (bmiStatus.need * 2)} calo/day</span> to <span class="text-${bmiStatus.effect}">${bmiStatus.status}</span> 1kg per week
            				</span>
            			`;
            }
        }
        $('#returnBMIScore').html(html);
        $('input[name=BMROption]').iCheck({
            checkboxClass: 'icheckbox_flat-red',
            radioClass: 'iradio_flat-red'
        });
    }
}


function caculateBMR(human) {
    var BMR = 0;
    if (human.sex == 'Male') {
        BMR = (10 * human.w) + (6.25 * human.h) - (5 * human.age) + 5;
    } else {
        BMR = (10 * human.w) + (6.25 * human.h) - (5 * human.age) - 161;
    }

    return BMR;
}

function exportBMIinfo(bmi) {
    if (bmi < 18.5) {
        return {
            info: 'You are a little skinny',
            need: 500,
            effect: 'success',
            status: 'gain'
        };
    } else if (bmi >= 18.5 && bmi <= 24.9) {
        return {
            info: 'You are very healthy',
            need: 0,
            effect: 'info',
            status: 'maintain'
        };
    } else if (bmi >= 25 && bmi <= 29.9) {
        return {
            info: 'You are a little fat',
            need: -500,
            effect: 'danger',
            status: 'lose'
        };
    } else if (bmi >= 30 && bmi <= 34.9) {
        return {
            info: 'You are fat',
            need: -500,
            effect: 'danger',
            status: 'lose'
        };
    } else if (bmi > 35) {
        return {
            info: 'You are very fat',
            need: -500,
            effect: 'danger',
            status: 'lose'
        }
    }
}

function getOptionsWith(type) {
    switch (type) {
        case 'star':
            return getOptionStar();
            break;
        case 'type':
            return getOptionType();
            break;
    }
}

function getOptionType() {
    var inner = $('#groupFoodType')[0].innerHTML;
    html = '<div class="col-md-12 p-3" id="groupMoreOptionFoodType">';
    html += inner;
    html += '</div>';
    return html;
}

function getOptionStar() {
    var inner = $('#groupFoodRating')[0].innerHTML;
    html = '<div class="col-md-12 p-3" id="groupMoreOptionRating">';
    html += inner;
    html += '</div>';
    return html;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}