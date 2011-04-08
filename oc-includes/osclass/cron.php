<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Load lots of fancy stuff
define('__FROM_CRON__', true);
if(!defined('__OSC_LOADED__')) {
	require_once '../../oc-load.php';
}




	// HOURLY CRONS
	$crons = Cron::newInstance()->getCronByType('HOURLY');
	if(isset($crons[0])) {
		$cron = $crons[0];
		unset($crons);

		$now_text = date('Y-m-d H:i:s');
		$now = strtotime($now_text);
		$next = strtotime($cron['d_next_exec']);

		if(($now-$next)>=-10) {//3590) {
			// executing HOURLY crons
			include_once "cron.hourly.php";

			// update the database
			while($next<=$now) {
				$next += 3600;
			}
			$next_text = date('Y-m-d H:i:s', $next);
			Cron::newInstance()->update(
				array('d_last_exec' => $now_text, 'd_next_exec' => $next_text),
				array('e_type' => 'HOURLY')
			);
		} else {
			//too early for crons
		}
	}


	// DAILY CRONS
	$crons = Cron::newInstance()->getCronByType('DAILY');
	if(isset($crons[0])) {
		$cron = $crons[0];
		unset($crons);

		//$now_text = date('Y-m-d H:i:s');
		$now = time();//strtotime($now_text);
		$next = strtotime($cron['d_next_exec']);
		if(($now-$next)>=-10) {//(24*3600-10)) {
			// executing HOURLY crons
			include_once "cron.daily.php";
			// update the database
			while($next<=$now) {
				$next += (24*3600);
			}
			$next_text = date('Y-m-d H:i:s', $next);
			Cron::newInstance()->update(
				array('d_last_exec' => $now_text, 'd_next_exec' => $next_text),
				array('e_type' => 'DAILY')
			);
		} else {
			//too early for crons
		}
	}

	// WEEKLY CRONS
	$crons = Cron::newInstance()->getCronByType('WEEKLY');
	if(isset($crons[0])) {
		$cron = $crons[0];
		unset($crons);

		$now_text = date('Y-m-d H:i:s');
		$now = strtotime($now_text);
		$next = strtotime($cron['d_next_exec']);

		if(($now-$next)>=-10) {//(7*24*3600-10)) {
			// executing HOURLY crons
			include_once "cron.weekly.php";

			// update the database
			while($next<=$now) {
				$next += (7*24*3600);
			}
			$next_text = date('Y-m-d H:i:s', $next);
			Cron::newInstance()->update(
				array('d_last_exec' => $now_text, 'd_next_exec' => $next_text),
				array('e_type' => 'WEEKLY')
			);
		} else {
			//too early for crons
		}
	}
	
	osc_run_hook('cron');

?>
