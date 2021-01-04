/**
 * Delete SQL
 *
 * Required if install.sql file present
 * - Delete profile exceptions
 * - Delete module specific tables
 * (and their eventual sequences & indexes) if any
 *
 * @package Lunch Report Plugin
 */

--
-- Delete from profile_exceptions table
--

DELETE FROM profile_exceptions WHERE modname='LunchReport/LunchReport.php';

DROP VIEW public.student_lunches;
DROP VIEW public.active_students;