<?php

function show_page()
{
	?>
	<div class="mt-3">
		<div class="row">
			<div class="col-8">
				<h2>403</h2>
			</div>

			<div class="col-2"></div>

			<div class="col-2 pl-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location='/'">
					<span class="pr-1">
						<img src="/resources/external/octicons/img/file.svg" width=12 height=24>
					</span>
					NEW PASTE
				</button>
			</div>
		</div>
	</div>

	<hr>

	<div class="alert alert-danger">
		<span class="lead"><strong>Forbidden</strong></span><br>
		Wandering around eh? Go back on trails.
	</div>
	<?php
}