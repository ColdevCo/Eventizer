<link rel="stylesheet" href="../assets/css/style.css" />

<style>
	.wrap {
		margin: 30px 30px 30px 10px;
		padding: 15px;
		background-color: #fff;
	}

    ul.cem-setting-tabs {
        list-style-type: none;
    }

    ul.cem-setting-tabs > li {
        display: inline-block;
    }

    ul.cem-setting-tabs > li > a {
        padding: 10px 20px;
        background-color: #E8E8E8;
        border: 1px solid #ddd;
        color: #222;
        text-decoration: none;
        outline: none;
    }

    ul.cem-setting-tabs > li:not(:first-child) {
        margin-left: 3px;
    }

	.ev_setting_wrapper {
		margin-bottom: 15px;
	}

      .ev_setting_wrapper > * {
            vertical-align: top;
      }

	.ev_setting_wrapper > label {
		display: inline-block;
		width: 210px;
	}

	.ev_setting_wrapper > input[type="text"],
	.ev_setting_wrapper > select,
      .ev_setting_wrapper > .ev_setting_details {
		display: inline-block;
		width: 250px;
	}

      .ev_setting_details > p {
            margin: 0 0 1em;
      }

	.ev_setting_wrapper > a {
		text-decoration: none;
	}

	.ev_setting_wrapper > input[type="submit"] {
		height: 30px;
		line-height: 28px;
		padding: 0 12px 2px;
		background: #2ea2cc;
		border: 1px solid #0074a2;
		color: #fff;
		text-decoration: none;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}
</style>

<div id="event-setting" class="wrap">
	
	<h2>Setting</h2>

    <ul class="cem-setting-tabs">
        <?php foreach( $setting_tabs as $tab => $view ) : ?>
        <li>
            <a href="<?= add_query_arg( array('tab' => $tab) ) ?>"><?= ucfirst( $tab ) ?></a>
        </li>
        <?php endforeach; ?>
    </ul>

    <hr />

    <?php include ! isset( $_GET['tab'] ) ? 'setting-general.php' : $setting_tabs[ $_GET['tab'] ]; ?>

</div>