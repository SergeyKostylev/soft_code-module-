$(document).ready(function () {

    // window.onbeforeunload = function (e) {
    //     var e = e || window.event;
    //     var myMessage= "Если вы закроете страницу сейчас, то она закроется";
    //     if (e)
    //     {
    //         e.returnValue = myMessage;
    //     }
    //     return myMessage;
    // };

// ВКЛЮЧИТЬ
var $allamount = $('#allAmount');
var $shownow =$('#showNow');
    setInterval(function() {
        var $rand =Math.floor( (Math.random() * 4)+1 );
        $shownow.text($rand);
        var $newsId = $('#newsid');
        var $id = $newsId.data('newsid');
        var $showing =$shownow.text();
        $.get('/showamount/' + $id +'/' + $showing, function(r) {
            $allamount.text(r['amount']);
        });
    }, 3000);




var $topfivebutton = $('#topfivebutton');
var $topfivebody = $('#topfivebody');

$topfivebutton.on('click',function () {
   $topfivebody.toggle(300);
});

    var $topfiveUserbutton = $('#topfiveUserbutton');
    var $topfiveUserbody = $('#topfiveUserbody');

    $topfiveUserbutton.on('click',function () {
        $topfiveUserbody.toggle(300);
    });

    var $toptreeNewsbutton = $('#toptreeNewsbutton');
    var $toptreeNewsbody = $('#topthreeNewsbody');

    $toptreeNewsbutton.on('click',function () {
        $toptreeNewsbody.toggle(300);
    });




var $addComment = $('.add-comment-button');
    $addComment.on('click', function () {


        var $commentField = $('.comment-textarea');
        var $comment = $commentField.val();
        var $blokOk = $('.get-message-ok');
        var $commentForm = $('.comment-form');

        if($comment.length < 20){
            alert('Слишком короткий комментарий');
        }
        else {
            $.post('/api/add/comment',{
                newsid : $('#newsid').data('newsid'),
                commentbody : $comment
                },'json');
            $commentForm.fadeOut(390);
            $blokOk.fadeIn(700);
            setTimeout(function () {
                $blokOk.fadeOut(390)
            }, 2100);

        }
    });



    var $likesButton = $('.likes-button');

    $likesButton.on('click', function () {
        var $this =$(this);
        var $commentId = $this.siblings().filter('.comment-id').text();
        var $footerMessage = $('#footer');
        $.post('/api/likes',{
            commentid : $commentId
        },'json')
            .done(function (r) {
                var $likesAmount = r.likes;
                var $disLikesAmount = r.dislikes;
                $footerMessage.text(r.answer);
                $footerMessage.fadeIn(210);
                setTimeout(function () {
                    $footerMessage.fadeOut(390)
                }, 1200);
                $this.siblings().filter('.likes-field').text(r.likes);
                $this.siblings().filter('.dislikes-field').text(r.dislikes);
            })
            .fail(function (r) {
                $footerMessage.text(r.answer);
                $footerMessage.fadeIn(210);
                setTimeout(function () {
                    $footerMessage.fadeOut(390)
                }, 1200);
            })
        ;
    });


    var $dislikesButton = $('.dislikes-button');

    $dislikesButton.on('click', function () {
        var $this =$(this);
        var $commentId = $this.siblings().filter('.comment-id').text();
        var $footerMessage = $('#footer');
        $.post('/api/dislikes',{
            commentid : $commentId
        },'json')
            .done(function (r) {
                var $likesAmount = r.likes;
                var $disLikesAmount = r.dislikes;
                $footerMessage.text(r.answer);
                $footerMessage.fadeIn(210);
                setTimeout(function () {
                    $footerMessage.fadeOut(390)
                }, 1200);

                $this.siblings().filter('.likes-field').text(r.likes);
                $this.siblings().filter('.dislikes-field').text(r.dislikes);
            })
            .fail(function (r) {
                $footerMessage.text(r.answer);
                $footerMessage.fadeIn(210);
                setTimeout(function () {
                    $footerMessage.fadeOut(390)
                }, 1200);
            })
        ;
    });












    setTimeout(function () {
        $('.add-category-message').fadeOut(390)
    }, 2100);

    var $invisibleCardButton = $('.invisible-card-button');
    $invisibleCardButton.on('click', function () {
        var $invisibleCardBody = $('.invisible-card-body');
        $invisibleCardBody.toggle(700);
    });

    var $visibleCardButton = $('.visible-card-button');
    $visibleCardButton.on('click', function () {
        var $visibleCardBody = $('.visible-card-body');
        $visibleCardBody.toggle(700);
    });


     resolutionComment = function () {
        var $approval = $('.approval-button');
        var $this = $(this);
        var $commentId = $this.siblings().filter('.comment-id').text();
        var $commentBody = $this.siblings().filter('.form-of-comment').children(":last").val();
        $this.parent().toggle(700);
        setTimeout(function () {
            $this.parent().remove();
        }, 5000);
            $.post('/api/applause',{
                commentid : $commentId,
                commentbody : $commentBody
            },'json');
    };

    $('.comment-block').on('click', '.approval-button' ,resolutionComment);





    var $serch = $('#serch-button');
    var $searchField = $('#search-field');
    var $lowpfield = $('#lowp-field');

    $searchField.on('input',function () {
        var $serchword = $searchField.val();
        var $serchImput = $('.serch-imput');

        $serchImput.children().remove();

        if ($serchword !== ""){
            $.get('/api/search',function (r)
            {
                var $allTegsWords = r.tagword;
                var $allIds = r.ids;
                var $amount = $allTegsWords.length;
                var $tegs = [];

                var w = $('.container-fluid');

                for (var i = 0; i <= $amount; i++) {
                    if ($allTegsWords[i] !== undefined){

                        $lowpfield.fadeIn();

                        var $ansver = $allTegsWords[i].indexOf($serchword);
                        console.log($ansver);
                        if( $ansver === 0 ){

                            $tegs[$allIds[i]] = $allTegsWords[i];

                            var $link  = $('#template-search').clone();
                            $link.removeClass('disp-none');
                            $link.children().filter('.search-link').attr("href", "/news/tag/" + $allIds[i]).text($allTegsWords[i]);
                            $serchImput.append($link);
                        }}
                }
            })
        }
        else {$lowpfield.fadeOut();}
    });





    var $pagination =$('.pagination');

    $pagination.children().filter( ':first , :last' ).remove();
    $pagination.removeClass('disp-none');
    var $bitweenbutton = $pagination.children().not( ':first , :last' );
    $bitweenbutton.toggle();
    var $template = $('.page-item-template');
    var $treepointButton = $template.clone();
    $treepointButton.removeClass('disp-none');
    $pagination.children().filter( ':first').after($treepointButton);
    $treepointButton.on('click', function () {
        $treepointButton.remove();
        $pagination.children().fadeIn(1200);
    });





    $('#search-field').keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });



    var $openSiteGroupButton = $('#open-site-group-button');
    var $dropdownMenu =$('.dropdown-menu');
    $openSiteGroupButton.on('click', function () {
        var $serchSiteGroup = $('#serch-site-group');
        $serchSiteGroup.toggle(300);
        $dropdownMenu.fadeIn();

    });






    var slideNow = 1;
    var slideCount = $('#slidewrapper').children().length;
    var slideInterval = 3000;
    var navBtnId = 0;
    var translateWidth = 0;

    $(document).ready(function() {
        var switchInterval = setInterval(nextSlide, slideInterval);

        $('#viewport').hover(function() {
            clearInterval(switchInterval);
        }, function() {
            switchInterval = setInterval(nextSlide, slideInterval);
        });

        $('#next-btn').click(function() {
            nextSlide();
        });

        $('#prev-btn').click(function() {
            prevSlide();
        });

        $('.slide-nav-btn').click(function() {
            navBtnId = $(this).index();

            if (navBtnId + 1 != slideNow) {
                translateWidth = -$('#viewport').width() * (navBtnId);
                $('#slidewrapper').css({
                    'transform': 'translate(' + translateWidth + 'px, 0)',
                    '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
                    '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
                });
                slideNow = navBtnId + 1;
            }
        });
    });


    function nextSlide() {
        if (slideNow == slideCount || slideNow <= 0 || slideNow > slideCount) {
            $('#slidewrapper').css('transform', 'translate(0, 0)');
            slideNow = 1;
        } else {
            translateWidth = -$('#viewport').width() * (slideNow);
            $('#slidewrapper').css({
                'transform': 'translate(' + translateWidth + 'px, 0)',
                '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
                '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
            });
            slideNow++;
        }
    }

    function prevSlide() {
        if (slideNow == 1 || slideNow <= 0 || slideNow > slideCount) {
            translateWidth = -$('#viewport').width() * (slideCount - 1);
            $('#slidewrapper').css({
                'transform': 'translate(' + translateWidth + 'px, 0)',
                '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
                '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
            });
            slideNow = slideCount;
        } else {
            translateWidth = -$('#viewport').width() * (slideNow - 2);
            $('#slidewrapper').css({
                'transform': 'translate(' + translateWidth + 'px, 0)',
                '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
                '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
            });
            slideNow--;
        }
    }







});




