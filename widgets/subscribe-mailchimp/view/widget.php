<style type="text/css">
    .widget_cem_subscribe_mailchimp {
        /* background-color: #f3f3f7; */
        border: 1px solid #ddd;
        /*
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        */
    }

    .widget_cem_subscribe_mailchimp h2 {
        margin: 0px 0px 22px;
    }

    .widget_cem_subscribe_mailchimp .input-group {
        width: auto;
        padding: 20px;
    }

    .widget_cem_subscribe_mailchimp .input-group:last-child {
        background-color: #E8E8E8;
        text-align: center;
    }

    .widget_cem_subscribe_mailchimp .input-group > * {
        display: block;
        width: 100%;
    }

    .widget_cem_subscribe_mailchimp .input-group > label {
        cursor: pointer;
    }

    .widget_cem_subscribe_mailchimp .input-group > button {
        width: auto;
        display: inline-block;
        padding: 10px 45px;
    }
</style>
<aside id="cem-subscribe-mailchimp-<?= $this->number; ?>" class="widget widget_cem_subscribe_mailchimp">

    <form role="form" action="?ma=subscribe" method="post">

        <div class="input-group">

            <?= HTML::label( '<h2>Subscribe</h2>', 'cem_subscribe_mailchimp-email' ); ?>
            <?= HTML::text( 'cem_subscribe_mailchimp-email', array( 'id' => 'cem_subscribe_mailchimp-email', 'placeholder' => 'Email' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::button( 'Subscribe' ); ?>

        </div>

    </form>

</aside>