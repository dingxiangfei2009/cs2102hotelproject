<?php
	$showMonth = $_POST['showMonth'];
	$showYear = $_POST['showYear'];

	$dayCount = cal_days_in_month(CAL_GREGORIAN, $showMonth, $showYear);
	$preDays = date('w', mktime(0, 0, 0, $showMonth, 1, $showYear));
	$postDays = (6 - (date('w', mktime(0, 0, 0, $showMonth, $dayCount, $showYear)));

	echo '<div id="calendar_wrap">';
		// build first line of displaying the month title
		echo '<div class="title_bar">';
			echo '<div class="previous_month"></div>';
			echo '<div class="shown_month">'.$showMonth.'/'.$showYear.'</div>';
			echo '<div class="next_month"></div>';
		echo '</div>';

		// display Sun - Sat title
		echo '<div class="week_days">';
			echo '<div class="days_of_week">Sun</div>';
			echo '<div class="days_of_week">Mon</div>';
			echo '<div class="days_of_week">Tue</div>';
			echo '<div class="days_of_week">Wed</div>';
			echo '<div class="days_of_week">Thur</div>';
			echo '<div class="days_of_week">Fri</div>';
			echo '<div class="days_of_week">Sat</div>';
			echo '<div class="clear"></div>';
		echo '</div>';

		// build preDays
		if ($preDays != 0) {
			for ($i=1; $i<$preDays; $i++) {
				echo '<div class="non_cal_day"></div>';
			}
		}

		// build days for current month
		for ($i=1; $i<$dayCount; $i++) {
			echo '<div class="cal_day">';
			echo '<div class="day_heading">'.$i.'</div>';
			echo '</div>';
		}

		// build postDays
		if ($postDays != 0) {
			for ($i=1; $i<$postDays; $i++) {
				echo '<div class="non_cal_day"></div>';
			}
		}

	echo '</div>'; 
?>