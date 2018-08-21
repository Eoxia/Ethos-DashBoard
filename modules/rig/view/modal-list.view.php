<?php
/**
 * Affiches le listing des rigs pour la génération des TXT dans la modaL.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2018 Eoxia <dev@eoxia.com>.
 *
 * @license   MIT <https://spdx.org/licenses/MIT.html>
 *
 * @package   Ethos_Dashboard\Templates
 *
 * @since     0.2.0
 */

namespace ethos_dashboard;

defined( 'ABSPATH' ) || exit; ?>

<input type="hidden" class="number-error" value="0" />
<input type="hidden" class="number-success" value="0" />
<input type="hidden" class="total" value="<?php echo esc_attr( $total ); ?>" />

<ul>
	<?php
	if ( ! empty( $group_rigs ) ) :
		foreach ( $group_rigs as $group_rig ) :
			?>
			<li data-nonce="<?php echo esc_attr( wp_create_nonce( 'regenerate_txt' ) ); ?>"
				data-ids="<?php echo esc_attr( $group_rig['ids'] ); ?>"
				class="active">
				<?php echo wp_kses( 'Generate TXT for the RIGS: ' . $group_rig['fullname'], array(
					'span' => array(
						'data-id' => array()
					),
				) ); ?>
				<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
			</li>
			<?php
		endforeach;
	endif;
	?>
</ul>

<h3><?php esc_html_e( 'Log', 'ethos-dashboard' ); ?></h3>

<ul class="log"></ul>
