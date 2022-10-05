$(function () {

    $.get('https://www.pharmanexo.com.br/pharmanexo_v2/notifications/getNotifications', function (response) {
        
        if (response !== "NULL"){
            $("#btnNotfifications").addClass("top-nav__notify");

            var notfication = '';
            $.each(response, function (i, v) {

                notfication += `<a href="${v.url}" class="listview__item">
                                        <img src="https://img.icons8.com/cotton/2x/appointment-reminders.png" class="listview__img" alt="">

                                        <div class="text-secondary">
                                            <p>${v.message}</p>
                                        </div>
                                    </a>`;


            });

            $('#containerNotifications').html(notfication);

        }else{
            $("#btnNotfifications").removeClass("top-nav__notify");
        }
    })

    $('[data-ma-action="notifications-clear"]').click(function () {
        $.get('https://www.pharmanexo.com.br/pharmanexo_v2/notifications/removeAll', function (response) {

            if(response.type == 'success'){
                $('#containerNotifications').html('');
                $("#btnNotfifications").removeClass("top-nav__notify");

            }

            formWarning(response);

        });
    });

});