<style type="text/css">
    #cem-subscribe-mailchimp {
        background-color: #f3f3f7;
        padding: 10px;
        border: 1px solid #ddd;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    #cem-subscribe-mailchimp .input-group:not(:first-child) {
        margin-top: 7px;
    }

    #cem-subscribe-mailchimp .input-group:last-child {
        margin-top: 20px;
    }

    #cem-subscribe-mailchimp .input-group > * {
        display: block;
        width: 100%;
    }

    #cem-subscribe-mailchimp .input-group > label {
        cursor: pointer;
    }

    #cem-subscribe-mailchimp .input-group > button {
        width: auto;
        display: inline-block;
        padding: 10px 45px;
    }
</style>
<div id="cem-subscribe-mailchimp">

    <form role="form" action="?ma=subscribe" method="post">

        <div class="input-group">

            <?= HTML::label( 'Subscribe', 'cem_subscribe_mailchimp-email' ); ?>
            <?= HTML::text( 'cem_subscribe_mailchimp-email', array( 'id' => 'cem_subscribe_mailchimp-email', 'placeholder' => 'Email' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::button( 'Subscribe' ); ?>

        </div>

    </form>

</div>