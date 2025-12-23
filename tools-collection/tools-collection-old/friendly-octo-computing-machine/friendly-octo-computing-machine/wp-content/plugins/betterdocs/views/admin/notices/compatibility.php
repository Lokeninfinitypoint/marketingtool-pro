<div class="warning notice">
	<p>
		<?php
			printf(
				__( '<strong>BetterDocs Free 2.5</strong> requires <strong>BetterDocs Pro 2.5</strong> plugin to be installed. Please <strong><em>update</em></strong> the BetterDocs Pro plugin for a smooth experience.', 'betterdocs' ), //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaping Not Needed Here Because, This Is A Static String With Html Tags, Which Are Required
				esc_html( $version )
			);
			?>
	</p>
</div>
