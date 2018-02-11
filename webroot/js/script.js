$(document).ready(function () {

var $topfivebutton = $('#topfivebutton');
var $topfivebody = $('#topfivebody');

$topfivebutton.on('click',function () {
   $topfivebody.toggle(300);
});


var $addComment = $('.add-comment-button');
    $addComment.on('click', function () {

        var $commentField = $('.comment-textarea');
        var $comment = $commentField.val();
        var $blokOk = $('.get-message-ok');
        var $commentForm = $('.comment-form');
        $.post('/api/add/comment',{
            newsid : $('#newsid').data('newsid'),
            commentbody : $comment
            },'json')
        $commentForm.fadeOut(390);
        $blokOk.fadeIn(700);
        setTimeout( function() {$blokOk.fadeOut(390)} ,2100);


    });



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


    var $pagination =$('.pagination');

    $pagination.children().filter( ':first , :last' ).remove();
    $pagination.removeClass('disp-none');
    var $bitweenbutton = $pagination.children().not( ':first , :last' );
    $bitweenbutton.toggle();
    var $template = $('.page-item-template');
    var $treepointButton = $template.clone();
    console.log($treepointButton);
    $treepointButton.removeClass('disp-none');
    $pagination.children().filter( ':first').after($treepointButton);
    $treepointButton.on('click', function () {
        $treepointButton.remove();
        $pagination.children().fadeIn(1200);
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




