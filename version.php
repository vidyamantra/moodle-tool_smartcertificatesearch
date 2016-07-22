<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information for tool smartcertificatesearch
 *
 * File         version.php
 * Encoding     UTF-8
 *
 * @package     tool_smartcertificatesearch
 *
 * @copyright   Vidya Mantra EduSystems Pvt. Ltd.
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
$plugin = new stdClass();
$plugin->version   = 2016063000; // The current module version (Date: YYYYMMDDXX).
$plugin->requires  = 2015051100; // Requires this Moodle version.
$plugin->cron      = 0; // Period for cron to check this module (secs)
$plugin->component = 'tool_smartcertificatesearch'; // Full name of the plugin (used for diagnostics).
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = "1.0 (Build: 2016063000)"; // User-friendly version number.
