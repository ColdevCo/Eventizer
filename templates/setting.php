<?php
global $wpdb;

if( $_POST ) {
      update_event_options( 'default_currency' , $_POST[ "event_default_currency" ] );
      update_event_options( 'enabled_extensions' , serialize( $_POST['enabled_event_extensions'] ) );
}

$currencies = array (
            'ALL' => 'Albania Lek',
            'AFN' => 'Afghanistan Afghani',
            'ARS' => 'Argentina Peso',
            'AWG' => 'Aruba Guilder',
            'AUD' => 'Australia Dollar',
            'AZN' => 'Azerbaijan New Manat',
            'BSD' => 'Bahamas Dollar',
            'BBD' => 'Barbados Dollar',
            'BDT' => 'Bangladeshi taka',
            'BYR' => 'Belarus Ruble',
            'BZD' => 'Belize Dollar',
            'BMD' => 'Bermuda Dollar',
            'BOB' => 'Bolivia Boliviano',
            'BAM' => 'Bosnia and Herzegovina Convertible Marka',
            'BWP' => 'Botswana Pula',
            'BGN' => 'Bulgaria Lev',
            'BRL' => 'Brazil Real',
            'BND' => 'Brunei Darussalam Dollar',
            'KHR' => 'Cambodia Riel',
            'CAD' => 'Canada Dollar',
            'KYD' => 'Cayman Islands Dollar',
            'CLP' => 'Chile Peso',
            'CNY' => 'China Yuan Renminbi',
            'COP' => 'Colombia Peso',
            'CRC' => 'Costa Rica Colon',
            'HRK' => 'Croatia Kuna',
            'CUP' => 'Cuba Peso',
            'CZK' => 'Czech Republic Koruna',
            'DKK' => 'Denmark Krone',
            'DOP' => 'Dominican Republic Peso',
            'XCD' => 'East Caribbean Dollar',
            'EGP' => 'Egypt Pound',
            'SVC' => 'El Salvador Colon',
            'EEK' => 'Estonia Kroon',
            'EUR' => 'Euro Member Countries',
            'FKP' => 'Falkland Islands (Malvinas) Pound',
            'FJD' => 'Fiji Dollar',
            'GHC' => 'Ghana Cedis',
            'GIP' => 'Gibraltar Pound',
            'GTQ' => 'Guatemala Quetzal',
            'GGP' => 'Guernsey Pound',
            'GYD' => 'Guyana Dollar',
            'HNL' => 'Honduras Lempira',
            'HKD' => 'Hong Kong Dollar',
            'HUF' => 'Hungary Forint',
            'ISK' => 'Iceland Krona',
            'INR' => 'India Rupee',
            'IDR' => 'Indonesia Rupiah',
            'IRR' => 'Iran Rial',
            'IMP' => 'Isle of Man Pound',
            'ILS' => 'Israel Shekel',
            'JMD' => 'Jamaica Dollar',
            'JPY' => 'Japan Yen',
            'JEP' => 'Jersey Pound',
            'KZT' => 'Kazakhstan Tenge',
            'KPW' => 'Korea (North) Won',
            'KRW' => 'Korea (South) Won',
            'KGS' => 'Kyrgyzstan Som',
            'LAK' => 'Laos Kip',
            'LVL' => 'Latvia Lat',
            'LBP' => 'Lebanon Pound',
            'LRD' => 'Liberia Dollar',
            'LTL' => 'Lithuania Litas',
            'MKD' => 'Macedonia Denar',
            'MYR' => 'Malaysia Ringgit',
            'MUR' => 'Mauritius Rupee',
            'MXN' => 'Mexico Peso',
            'MNT' => 'Mongolia Tughrik',
            'MZN' => 'Mozambique Metical',
            'NAD' => 'Namibia Dollar',
            'NPR' => 'Nepal Rupee',
            'ANG' => 'Netherlands Antilles Guilder',
            'NZD' => 'New Zealand Dollar',
            'NIO' => 'Nicaragua Cordoba',
            'NGN' => 'Nigeria Naira',
            'NOK' => 'Norway Krone',
            'OMR' => 'Oman Rial',
            'PKR' => 'Pakistan Rupee',
            'PAB' => 'Panama Balboa',
            'PYG' => 'Paraguay Guarani',
            'PEN' => 'Peru Nuevo Sol',
            'PHP' => 'Philippines Peso',
            'PLN' => 'Poland Zloty',
            'QAR' => 'Qatar Riyal',
            'RON' => 'Romania New Leu',
            'RUB' => 'Russia Ruble',
            'SHP' => 'Saint Helena Pound',
            'SAR' => 'Saudi Arabia Riyal',
            'RSD' => 'Serbia Dinar',
            'SCR' => 'Seychelles Rupee',
            'SGD' => 'Singapore Dollar',
            'SBD' => 'Solomon Islands Dollar',
            'SOS' => 'Somalia Shilling',
            'ZAR' => 'South Africa Rand',
            'LKR' => 'Sri Lanka Rupee',
            'SEK' => 'Sweden Krona',
            'CHF' => 'Switzerland Franc',
            'SRD' => 'Suriname Dollar',
            'SYP' => 'Syria Pound',
            'TWD' => 'Taiwan New Dollar',
            'THB' => 'Thailand Baht',
            'TTD' => 'Trinidad and Tobago Dollar',
            'TRY' => 'Turkey Lira',
            'TRL' => 'Turkey Lira',
            'TVD' => 'Tuvalu Dollar',
            'UAH' => 'Ukraine Hryvna',
            'GBP' => 'United Kingdom Pound',
            'USD' => 'United States Dollar',
            'UYU' => 'Uruguay Peso',
            'UZS' => 'Uzbekistan Som',
            'VEF' => 'Venezuela Bolivar',
            'VND' => 'Viet Nam Dong',
            'YER' => 'Yemen Rial',
            'ZWD' => 'Zimbabwe Dollar'
        );
$extensions = scan_extensions();
?>
<link rel="stylesheet" href="../assets/css/style.css" />

<style>
	.wrap {
		margin: 30px 30px 30px 10px;
		padding: 15px;
		background-color: #fff;
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
	<hr />

      <form method="post">
            <?php settings_fields( 'event-options-group' ); ?>
            <?php do_settings_sections( 'event-options-group' ); ?>

            <div id="setting-default-currency" class="ev_setting_wrapper">
                  <label for="ev_currency">Currency</label>
                  <select name="event_default_currency">
                        <?php foreach ( $currencies as $key => $currency ) : ?>
                              <option type="text" value="<?php echo $key; ?>" <?php echo get_event_options( 'default_currency' ) == $key ? 'selected' : ''; ?> ><?php echo $currency . " ({$key})"; ?></option>
                        <?php endforeach; ?>
                  </select>
            </div>

            <?php
            foreach( $extensions as $extension ) :
                  $extension[ 'slug' ] = strtolower( str_replace( ' ' , '-' , $extension[ 'Extension Name' ] ) );
            ?>
            <div id="setting-ext-<?php echo $extension[ 'slug' ] ?>" class="ev_setting_wrapper">
                  <label>Ext. <?php echo $extension[ 'Extension Name' ]; ?></label>
                  <div class="ev_setting_details">
                        <p><?php echo $extension[ 'Extension Description' ]; ?></p>
                        <input type="checkbox" name="enabled_event_extensions[]" value="<?php echo $extension[ 'Extension Name' ]; ?>" <?php echo is_array( unserialize( get_event_options( 'enabled_extensions' ) ) ) and in_array( $extension[ 'Extension Name' ] , unserialize( get_event_options( 'enabled_extensions' ) ) ) ? 'checked' : '' ?> >Enable</input>
                  </div>
            </div>      
            <?php endforeach; ?>

            <div id="setting-submit" class="ev_setting_wrapper">
                  <?php submit_button(); ?>
            </div>
      </form>

</div>