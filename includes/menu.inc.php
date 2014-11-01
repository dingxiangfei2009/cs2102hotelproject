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
    	<li><a href="hotel.php"
        		<?php 
					if ($currentPage == 'hotel.php') {
						echo 'id="here"';
					} 
				?>
            >Results</a>
        </li>
    	<li><a href="room.php"
        		<?php 
					if ($currentPage == 'room.php') {
						echo 'id="here"';
					} 
				?>
        	>Hotel Info</a>
        </li>
        <li><a href="payment.php"
        		<?php 
					if ($currentPage == 'payment.php') {
						echo 'id="here"';
					} 
				?>
        	>Payment</a>
        </li>
        <li><a href="login.php"
        		<?php 
					if ($currentPage == 'login.php') {
						echo 'id="here"';
					} 
				?>
        	>Login / Reg</a>
        </li>
	</ul>