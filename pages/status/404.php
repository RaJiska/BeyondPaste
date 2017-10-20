<?php

function show_page()
{
	?>
	<div class="mt-3">
		<div class="row">
			<div class="col-8">
				<h2>404</h2>
			</div>

			<div class="col-2"></div>

			<div class="col-2 pl-2">
				<button type="button" class="btn btn-secondary btn-block" onclick="window.location='?page=paste'">
					<span class="pr-1">
						<img src="resources/external/octicons/img/file.svg" width=16 height=32 onerror="this.src='lib/octicons/grabber.png'">
					</span>
					NEW PASTE
				</button>
			</div>
		</div>
	</div>

	<hr>

	<div class="alert alert-danger">
		<span class="lead">Are you lost ?</span><br>
		The page you were looking for could not be found :(
	</div>
	<?php
}