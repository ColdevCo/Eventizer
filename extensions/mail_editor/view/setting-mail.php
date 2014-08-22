<div id="cem-mail-editor">

    <?php $form = new Form(); $form->open( '', array( 'method' => 'post' ) ); ?>

    <div class="input-group">

        <?= HTML::label( 'Context', 'cem_mail_editor_context' ); ?>
        <?= HTML::dropdown( 'cem_mail_editor_context', array( 'test' => 'test' ) ); ?>

    </div>

    <div class="input-group">

        <?= HTML::label( 'Subject', 'cem_mail_editor_subject' ); ?>
        <?= HTML::text( 'cem_mail_editor_subject' ); ?>

    </div>

    <div class="input-group">

        <?= HTML::label( 'Content', 'cem_mail_editor_content' ); ?>
        <?= HTML::textarea( 'cem_mail_editor_content', array( 'class' => 'wp-core-ui wp-editor-wrap tmce-active has-dfw' ) ); ?>

    </div>

    <div class="input-group">

        <?= HTML::button( 'Save' ); ?>

    </div>

    <?php $form->close(); ?>

</div>