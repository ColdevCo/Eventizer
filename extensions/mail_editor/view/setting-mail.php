<?php

if ( $_POST ) {
    EventMail::update( $_GET['mail_id'], $_POST );
}
?>
<style type="text/css">

    #cem-mail-editor .input-group {
        margin-bottom: 20px;
    }

    #cem-mail-editor .input-group > * {
        display: block;
    }

    #cem_mail_editor_context,
    #cem_mail_editor_subject {
        padding: 5px 2px;
        width: 100%;
    }

    #cem-mail-editor .input-group > button {
        display: inline-block;
        cursor: pointer;
        background-color: #16499A;
        color: #FFF;
        padding: 6px 30px;
        margin: 25px 0 0;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

</style>

<div id="cem-mail-editor">

    <?php $form = new Form(); echo $form->open( '', array( 'method' => 'post' ) ); ?>

    <?php
    $mail = EventMail::get_mail( isset($_GET['mail_id']) ? $_GET['mail_id'] : 0 );
    if ( $mail ) :
    ?>

    <div class="input-group">

        <?php
        $mails = EventMail::get_mails();
        $mails = array_reduce($mails, function($result, $data){ $result["{$data->id} "] = $data->context; return $result; }, array());

        echo HTML::label( 'Context', 'cem_mail_editor_context' );
        echo HTML::dropdown( 'cem_mail_editor_context', $mails, array( 'id' => 'cem_mail_editor_context', 'value' => "{$mail->id} " ) );
        ?>

    </div>

    <div class="input-group">

        <?= HTML::label( 'Subject', 'cem_mail_editor_subject' ); ?>
        <?= HTML::text( 'cem_mail_editor_subject', array( 'id' => 'cem_mail_editor_subject', 'value' => $mail->subject ) ); ?>

    </div>

    <div class="input-group">

        <?php wp_editor( $mail->content, 'cem_mail_editor_content' ); ?>

    </div>

    <div class="input-group">

        <?= HTML::button( 'Save' ); ?>

    </div>

    <?php endif; echo $form->close(); ?>

</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#cem_mail_editor_context').on('change', function(){
            var id = jQuery(this).val();
            window.location.replace('<?= add_query_arg( array('mail_id' => false) ) ?>&mail_id=' + id);
        });
    });
</script>