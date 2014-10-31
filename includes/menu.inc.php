	<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>
    <ul id="nav">
   	 	<li><a href="index.php" 
				<?php 
					if ($currentPage == 'index.php') {
						echo 'id="here"';
					} 
				?>
            >Home</a>
        </li>
    	<li><a href="#">Results</a></li>
    	<li><a href="#">Hotel</a></li>
    	<li><a href="#">Log In</a></li>
	</ul>