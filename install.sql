/**
 * Install SQL
 * Required if the module has menu entries
 * - Add profile exceptions for the module to appear in the menu
 * - Add program config options if any (to every schools)
 * - Add module specific tables (and their eventual sequences & indexes)
 *   if any: see rosariosis.sql file for examples
 *
 * @package Reports module
 */

-- Fix #102 error language "plpgsql" does not exist
-- http://timmurphy.org/2011/08/27/create-language-if-it-doesnt-exist-in-postgresql/
--
-- Name: create_language_plpgsql(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION create_language_plpgsql()
RETURNS BOOLEAN AS $$
    CREATE LANGUAGE plpgsql;
    SELECT TRUE;
$$ LANGUAGE SQL;

SELECT CASE WHEN NOT (
    SELECT TRUE AS exists FROM pg_language
    WHERE lanname='plpgsql'
    UNION
    SELECT FALSE AS exists
    ORDER BY exists DESC
    LIMIT 1
) THEN
    create_language_plpgsql()
ELSE
    FALSE
END AS plpgsql_created;

DROP FUNCTION create_language_plpgsql();


--
-- Data for Name: profile_exceptions; Type: TABLE DATA;
--

INSERT INTO profile_exceptions (profile_id, modname, can_use, can_edit)
SELECT 1, 'LunchReport/LunchReport.php', 'Y', 'Y'
WHERE NOT EXISTS (SELECT profile_id
    FROM profile_exceptions
    WHERE modname='LunchReport/LunchReport.php'
    AND profile_id=1);

 

--
-- Name: Active Students
--
-- View: public.active_students

-- DROP VIEW public.active_students;

CREATE OR REPLACE VIEW public.active_students
 AS
 SELECT a.student_id,
    a.last_name,
    a.first_name,
    a.middle_name,
    a.name_suffix,
    a.username,
    a.password,
    a.last_login,
    a.failed_login,
    a.custom_200000000,
    a.custom_200000001,
    a.custom_200000002,
    a.custom_200000003,
    a.custom_200000004,
    a.custom_200000005,
    a.custom_200000006,
    a.custom_200000007,
    a.custom_200000008,
    a.custom_200000009,
    a.custom_200000010,
    a.custom_200000011,
    a.created_at,
    a.updated_at,
    a.custom_200000013,
    a.custom_200000014,
    a.custom_200000015,
    a.custom_200000016,
    a.custom_200000017,
    a.custom_200000018,
    a.custom_200000019,
    a.custom_200000020,
    a.custom_200000021,
    a.custom_200000022,
    a.custom_200000023,
    a.custom_200000024,
    a.custom_200000025,
    a.custom_200000026,
    a.custom_200000028,
    a.custom_200000029,
    a.custom_200000030,
    a.custom_200000033,
    a.syear,
    b.start_date,
    b.end_date,
    b.drop_code
   FROM (students a
     JOIN student_enrollment b ON ((a.student_id = b.student_id)))
  WHERE (b.drop_code IS NULL);

ALTER TABLE public.active_students
    OWNER TO rosariosis;


-- View: public.student_lunches

-- DROP VIEW public.student_lunches;

CREATE OR REPLACE VIEW public.student_lunches
 AS
 SELECT a.student_id,
    a.last_name,
    a.first_name,
    a.middle_name,
    a.name_suffix,
    a.username,
    a.password,
    a.last_login,
    a.failed_login,
    a.custom_200000000,
    a.custom_200000001,
    a.custom_200000002,
    a.custom_200000003,
    a.custom_200000004,
    a.custom_200000005,
    a.custom_200000006,
    a.custom_200000007,
    a.custom_200000008,
    a.custom_200000009,
    a.custom_200000010,
    a.custom_200000011,
    a.created_at,
    a.updated_at,
    a.syear,
    a.custom_200000013,
    a.custom_200000014,
    a.custom_200000015,
    a.custom_200000016,
    a.custom_200000017,
    a.custom_200000018,
    a.custom_200000019,
    a.custom_200000020,
    a.custom_200000021,
    a.custom_200000022,
    a.custom_200000023,
    a.custom_200000024,
    a.custom_200000025,
    a.custom_200000026,
    a.custom_200000028,
    a.custom_200000029,
    a.custom_200000030 AS class,
    a.custom_200000033,
    a.start_date,
    a.end_date,
    a.drop_code,
    b.school_date,
    b.period_id,
    b.attendance_code AS lunch_item,
    b.comment AS lunch_comment,
    c.title AS menu_item
   FROM ((active_students a
     JOIN lunch_period b ON ((a.student_id = b.student_id)))
     JOIN attendance_codes c ON ((b.attendance_code = c.id)))
  ORDER BY a.student_id;

ALTER TABLE public.student_lunches
    OWNER TO rosariosis;

